<?php

namespace Funblr;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $fillable = ['title', 'name', 'image_url', 'user_id', 'created_at', 'updated_at'];
    
    public function user() {
        return $this->belongsTo('Funblr\User');
    }
}
