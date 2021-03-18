<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussionComment extends Model
{
    protected $fillable = ['user_id' , 'discussion_id' , 'comment'];

    protected $hidden = [
        'created_at','updated_at','discussion_id' ,'user_id'
    ];
}
