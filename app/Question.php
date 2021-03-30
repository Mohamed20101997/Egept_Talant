<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title' ,'exam_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function questionImage(){
        return $this->hasOne(QuestionImage::class , 'question_id' , 'id');
    }

    public function answer(){
        return $this->hasOne(SystemAnswer::class, 'question_id', 'id');
    }
}
