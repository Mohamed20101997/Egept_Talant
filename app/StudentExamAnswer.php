<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExamAnswer extends Model
{
    protected $fillable = ['student_exam_id' , 'degree' ,'question_id'];
}
