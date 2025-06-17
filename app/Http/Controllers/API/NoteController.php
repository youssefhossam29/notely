<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NoteResource as NoteResource;

use App\Models\Note;
use App\Http\Requests\CreateNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class NoteController extends BaseController
{
    //
    // public function authorizeNote($note)
    // {
    //     if ($note->user_id != Auth::id()) {
    //         return $this->SendError("Unauthorized action", "Oops! you don't have permission to access this note", 403);
    //     }
    // }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $notes = Note::where('user_id', Auth::id())->latest()->get();
        if($notes->count() > 0){
            $notes = NoteResource::collection($notes);
            return $this->SendResponse($notes, "Notes selected Successfully");
        }else{
            return $this->SendError("There is no notes yet!");
        }
    }


    public function search(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->SendError("Validation error", $validator->errors());
        }

        $search = $request->input('search');
        $notes = Note::where('user_id', Auth::id())->where('title', 'LIKE', "%{$search}%")->latest()->get();
        $notes = NoteResource::collection($notes);
        return $this->SendResponse($notes, "Search results for $search");
    }

    /**
     * Display a listing of the trashed resource.
     */
    public function trash()
    {
        //
        $notes = Note::onlyTrashed()->where('user_id', Auth::id())->latest()->get();
        if($notes->count() > 0){
            $notes = NoteResource::collection($notes);
            return $this->SendResponse($notes, "Trashed notes selected Successfully");
        }else{
            return $this->SendError("There is no notes in trash!");
        }
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
            $note = new NoteResource($note);
            return $this->SendResponse($note, "Note added Successfully");
        } else {
            return $this->SendError("Unable to create note!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($note_slug)
    {
        //
        $note = Note::where('slug', $note_slug)->first();
        if ($note) {
            $this->authorize('view', $note);
            $note = new NoteResource($note);
            return $this->SendResponse($note, "Note selected Successfully");
        } else {
            return $this->SendError("Note not found!");
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
            $note = new NoteResource($note);
            return $this->SendResponse($note, "Note selected Successfully");
        }else {
            return $this->SendError("Note not found!");
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
            $note->content = ($request->has('content')) ? $request->content :  $note->content;
            $saved = $note->save();

            //delete old image
            if($saved && $old_image){
                $old_image = 'uploads/notes/' . $old_image;
                if (File::exists($old_image)) {
                    File::delete($old_image);
                }
            }

            if($saved){
                $note = new NoteResource($note);
                return $this->SendResponse($note, "Note updated Successfully");
            }else{
                return $this->SendError("Failed to update note!");
            }
        }else {
            return $this->SendError("Note not found!");
        }
    }





    public function destroy($note_slug)
    {
        //
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('delete', $note);
            $note->delete();
            return $this->SendResponse("Note moved to trash", "Note moved to trash");
        }else {
            return $this->SendError("Note not found!");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($note_slug)
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
                return $this->SendResponse("Note deleted successfully", "Note deleted successfully");
            } else {
                return $this->SendError("Failed to delete the note!");
            }
        } else {
            return $this->SendError("Note not found!");
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
            $note = new NoteResource($note);
            return $this->SendResponse($note, "Note restored successfully");
        }else{
            return $this->SendError("Note not found!");
        }

    }
}
