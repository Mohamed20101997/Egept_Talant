<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
     protected $fillable = ['name','start' ,'end','time','degree','type_id','group_id' ,'teacher_id' ];

     protected $hidden = ['created_at', 'updated_at'];

     public function studentGroups()
     {
         return $this->hasOne(StudentGroup::class, 'group_id', 'group_id');
     }

     public function studentExams(){
         return $this->hasOne(StudentExam::class, 'exam_id', 'id');
     }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'type_id', 'id');
    }



}
