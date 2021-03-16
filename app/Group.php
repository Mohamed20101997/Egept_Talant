<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name','body','image','teacher_id','created_by'];

    protected $hidden = [
        'created_at','updated_at',
    ];
}
