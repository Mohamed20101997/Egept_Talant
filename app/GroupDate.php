<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupDate extends Model
{
     protected $fillable = ['group_id', 'day', 'from' , 'to'];

    protected $hidden = [
        'created_at','updated_at','group_id'
    ];
}
