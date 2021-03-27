<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = ['student_id' , 'exam_id' ,'total_degree'];

    protected $hidden = ['created_at' , 'updated_at'];
}
