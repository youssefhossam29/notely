<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'title', 'content', 'slug', 'image', 'is_pinned'];
    protected $dates = ['deleted_at'];


    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // public function getFeaturedAttribute()
    // {
    //     return asset('uploads/notes/' . $this->image);
    // }

}
