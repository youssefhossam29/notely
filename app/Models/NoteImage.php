<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteImage extends Model
{
    use HasFactory;

    protected $fillable = ['note_id', 'name'];

    public function note(){
        return $this->belongsTo('App\Models\Note', 'note_id');
    }
}
