<?php

namespace App\Http\Controllers;

use App\Models\Note;
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
        $notes = Note::where('user_id', Auth::id())->where('title', 'LIKE', "%{$search}%")->orderBy('is_pinned', 'DESC')
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

        if($request->hasfile('image')){
            $newImage = $this->handleImageUpload($request->image);
        }
        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => (isset($newImage))? $newImage:null,
            'slug' => Str::random(10) . request()->server('REQUEST_TIME'),
        ]);

        if ($note) {
            return redirect()->route("my.notes")->with('success', "Note added successfully");
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
            return view('note.show')->with('note', $note);
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
            return view('note.edit')->with('note', $note);
        }else {
            return redirect()->back()->with('error', 'Note not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, $note_slug)
    {
        //
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('update', $note);
            $old_image = null;
            if($request->hasfile('image')){
                $newImage = $this->handleImageUpload($request->image);
                $old_image = $note->image;
                $note->image = $newImage;
            }

            if ($request->input('delete_image') == "delete" && $note->image) {
                $imagePath = 'uploads/notes/' . $note->image;
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
                $old_image = $note->image;
                $note->image = null;
            }

            $note->title = $request->title;
            $note->content = $request->content;
            $saved = $note->save();

            //delete old image
            if($saved && $old_image){
                $old_image = 'uploads/notes/' . $old_image;
                if (File::exists($old_image)) {
                    File::delete($old_image);
                }
            }

            if($saved){
                return redirect()->back()->with('success', 'Note updated succefully');
            }else{
                return redirect()->back()->with('error', "Failed to update note");
            }
        }else {
            return redirect()->back()->with('error', 'Note not found');
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
            return redirect()->route("my.notes")->with('success', "Note moved to trash");
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
            $note_image = $note->image;
            $deleted = $note->forceDelete();

            if ($deleted) {
                if($note_image){
                    $note_image = 'uploads/notes/' . $note_image;
                    if (File::exists($note_image)) {
                        File::delete($note_image);
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
            return redirect()->route("my.notes")->with('success', "Note restored successfully");
        }else{
            return redirect()->back()->with('error', 'Note not found');
        }
    }

    public function togglePin(Request $request, $note_slug)
    {
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('update', $note);
            $note->is_pinned = !$note->is_pinned;
            $saved = $note->save();
        }else {
            return redirect()->back()->with('error', 'Note not found');
        }
        return response()->json(['success' => true]);
    }
}
