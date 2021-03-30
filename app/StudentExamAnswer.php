<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExamAnswer extends Model
{
    protected $fillable = ['student_exam_id' , 'degree' ,'question_id','answer'];
    protected $hidden = ['created_at', 'updated_at'];

    public function studentQuestion(){
        return $this->belongsTo(Question::class, 'question_id', 'id')->with('answer');
    }

//
//    public function defaultAnswer(){
//        return $this->hasOne(SystemAnswer::class, 'question_id', 'question_id');
//    }

}
