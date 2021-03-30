<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EssayAnswerCheck extends Model
{
    protected $fillable = ['student_exam_id'];
    protected $hidden = ['created_at', 'updated_at'];


    public function studentExam()
    {
        return $this->hasOne(StudentExam::class, 'student_exam_id', 'id');
    }


}
