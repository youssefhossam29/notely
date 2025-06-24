<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NoteResource as NoteResource;

use App\Models\Note;
use App\Models\NoteImage;

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
        $notes = Note::where('user_id', Auth::id())
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();
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
        $notes = Note::where('user_id', Auth::id())->where(function($query) use ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('content', 'LIKE', "%{$search}%");
            })
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();
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
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, $note_slug)
    {
        //
        $note = Note::where('slug', $note_slug)->first();

        if (!$note) {
            return $this->SendError("Note not found!");
        }

        $this->authorize('update', $note);

        $note->title = $request->title;
        $note->content = ($request->has('content')) ? $request->content :  $note->content;
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

            $note->refresh();
            $note = new NoteResource($note);
            return $this->SendResponse($note, "Note updated Successfully");
        }else{
            return $this->SendError("Failed to update note!");
        }

    }


    public function softDelete($note_slug)
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
    public function destroy($note_slug)
    {
        //
        $note = Note::onlyTrashed()->where('slug', $note_slug)->first();
        if ($note) {
            $this->authorize('forceDelete', $note);
            $note_images = $note->noteImages;
            $deleted = $note->forceDelete();

            if ($deleted) {
                if($note_images->count()){
                    foreach ($note_images as $image) {
                        $path = 'uploads/notes/' . $image->name;
                        if (File::exists($path) && $image->name != "note.png") {
                            File::delete($path);
                        }
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


    public function togglePin($note_slug)
    {
        $note = Note::where('slug', $note_slug)->first();
        if($note){
            $this->authorize('update', $note);
            $note->is_pinned = !$note->is_pinned;
            $saved = $note->save();
        }else{
            return $this->SendError("Note not found!");
        }

        if($saved){
            $note = new NoteResource($note);
            return $this->SendResponse($note, "Success");
        }else{
            return $this->SendError("Failed!");
        }
    }

}
