<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteImage;
use Illuminate\Http\Request;
use App\Http\Requests\CreateNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{

    // public function authorizeNote($note)
    // {
    //     if ($note->user_id != Auth::id()) {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $notes = Note::where('user_id', Auth::id())
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(8);
        return view('note.index')->with('notes', $notes);
    }


    public function search(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $search = $request->input('search');
        $notes = Note::where('user_id', Auth::id())->where(function($query) use ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('content', 'LIKE', "%{$search}%");
            })
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(9);

        return view('note.index')->with('notes', $notes)->with('search', $search);
    }

    /**
     * Display a listing of the trashed resource.
     */
    public function trash()
    {
        //
        $notes = Note::onlyTrashed()->where('user_id', Auth::id())->latest()->paginate(9);
        return view('note.trash')->with('notes', $notes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('note.create');
    }


    public function handleImageUpload($image)
    {
        $imageName = Str::random(10) . time() . $image->getClientOriginalName();
        $image->move('uploads/notes/', $imageName);
        return $imageName;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNoteRequest $request)
    {
        //

        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::random(10) . request()->server('REQUEST_TIME'),
            'is_pinned' => $request->is_pinned ? 1 : 0
        ]);

        if ($note) {
            if ($request->hasFile('images')) {
                $noteImages = [];
                foreach ($request->file('images') as $image) {
                    $newImage = $this->handleImageUpload($image);
                    $noteImages[] = [
                        'note_id' => $note->id,
                        'name' => $newImage,
                    ];
                }
                $images = NoteImage::insert($noteImages);
            }
            return redirect()->route("notes.index")->with('success', "Note added successfully");
        } else {
            return redirect()->back()->with('error', "Unable to create note");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($note_slug)
    {
        //
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('view', $note);
            $noteImages = $note->noteImages;
            return view('note.show')->with( ['note'=> $note, 'noteImages' => $noteImages]);
        }else {
            return redirect()->back()->with('error', 'Note not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($note_slug)
    {
        //
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('update', $note);
            $noteImages = $note->noteImages;
            return view('note.edit')->with( ['note'=> $note, 'noteImages' => $noteImages]);
        }else {
            return redirect()->back()->with('error', 'Note not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, $note_slug)
    {
        $note = Note::where('slug', $note_slug)->first();

        if (!$note) {
            return redirect()->back()->with('error', 'Note not found');
        }

        $this->authorize('update', $note);

        $note->title = $request->title;
        $note->content = $request->content;
        $saved = $note->save();

        if($saved){

            // Remove deleted images from storage
            if ($request->has('delete_images') && $note->noteImages->count()) {
                $imageIds = $request->delete_images;

                $images = NoteImage::where('note_id', $note->id)
                                ->whereIn('id', $imageIds)
                                ->get();

                foreach ($images as $image) {
                    $path = 'uploads/notes/' . $image->name;
                    if (File::exists($path) && $image->name != "note.png") {
                        File::delete($path);
                    }
                }

                NoteImage::where('note_id', $note->id)
                        ->whereIn('id', $imageIds)
                        ->delete();
            }

            // Upload new images
            if ($request->hasFile('images')) {
                $noteImages = [];
                foreach ($request->file('images') as $image) {
                    $newImage = $this->handleImageUpload($image);
                    $noteImages[] = [
                        'note_id' => $note->id,
                        'name' => $newImage,
                    ];
                }
                $images = NoteImage::insert($noteImages);
            }

            return redirect()->back()->with('success', 'Note updated succefully');
        }else{
            return redirect()->back()->with('error', "Failed to update note");
        }
    }

    /**
     * Move the specified resource to trash.
     */
    public function softDelete($note_slug)
    {
        //
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('delete', $note);
            $note->delete();
            return redirect()->route("notes.index")->with('success', "Note moved to trash");
        }else {
            return redirect()->back()->with('error', 'Note not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($note_slug)
    {
        //
        $note = Note::onlyTrashed()->where('slug', $note_slug)->first();
        if ($note) {
            $this->authorize('forceDelete', $note);
            $note_images = $note->noteImages;
            $deleted = $note->forceDelete();

            if ($deleted) {
                if ($note_images->count()) {
                    foreach ($note_images as $image) {
                        $path = 'uploads/notes/' . $image->name;
                        if (File::exists($path) && $image->name != "note.png") {
                            File::delete($path);
                        }
                    }
                }
                return redirect()->route('notes.trash')->with('success', 'Note deleted successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to delete the note');
            }
        } else {
            return redirect()->back()->with('error', 'Note not found');
        }
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore($note_slug)
    {
        //
        $note = Note::onlyTrashed()->where('slug', $note_slug)->first();
        if($note){
            $this->authorize('restore', $note);
            $note->restore();
            return redirect()->route("notes.index")->with('success', "Note restored successfully");
        }else{
            return redirect()->back()->with('error', 'Note not found');
        }
    }

    public function togglePin($note_slug)
    {
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('update', $note);
            $note->is_pinned = !$note->is_pinned;
            $saved = $note->save();
        }else {
            return redirect()->back()->with('error', 'Note not found');
        }

        if($saved){
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false]);
        }
    }
}
