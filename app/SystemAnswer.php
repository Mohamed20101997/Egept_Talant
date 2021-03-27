<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemAnswer extends Model
{
    protected $fillable = ['answer' ,'type_id' ,'question_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
