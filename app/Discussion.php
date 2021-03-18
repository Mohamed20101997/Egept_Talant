<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = ['title' ,'user_id' ,'group_id'];


    protected $hidden = [
        'created_at','updated_at','group_id' ,'user_id'
    ];
}
