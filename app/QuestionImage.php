<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionImage extends Model
{
    protected $fillable = ['image' ,'question_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
