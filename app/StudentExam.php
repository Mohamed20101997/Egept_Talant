<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = ['student_id' , 'exam_id' ,'total_degree'];

    protected $hidden = ['created_at' , 'updated_at'];

    public function examData(){
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function studentData(){
        return $this->belongsTo(User::class, 'student_id', 'id');
    }


}
