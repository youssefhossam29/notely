<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Requests\CreateNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class NoteController extends Controller
{

    public function authorizeNote($note)
    {
        if ($note->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $notes = Note::where('user_id', Auth::id())->latest()->paginate(10);
        return view('note.index')->with('notes', $notes);
    }


    /**
     * Display a listing of the trashed resource.
     */
    public function trash()
    {
        //
        $notes = Note::onlyTrashed()->where('user_id', Auth::id())->latest()->paginate(10);
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
            $this->authorizeNote($note);
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
            $this->authorizeNote($note);
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
            $this->authorizeNote($note);

            $old_image = $note->image;
            if($request->hasfile('image')){
                $newImage = $this->handleImageUpload($request->image);
                $note->image = $newImage;
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
            $this->authorizeNote($note);
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
        $note = Note::withTrashed()->where('slug', $note_slug)->first();
        if ($note) {
            $this->authorizeNote($note);
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
        $note = Note::withTrashed()->where('slug', $note_slug)->first();
        if($note){
            $this->authorizeNote($note);
            $note->restore();
            return redirect()->route("my.notes")->with('success', "Note restored successfully");
        }else{
            return redirect()->back()->with('error', 'Note not found');
        }

    }
}
