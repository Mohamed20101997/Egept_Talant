<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    protected $fillable=['student_id' , 'group_id' , 'count' , 'price'];

    public function student(){

        return $this->belongsTo(User::class , 'student_id', 'id');
    }

    public function group(){

        return $this->belongsTo(Group::class , 'group_id', 'id');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'group_id',
        'student_id'
    ];
}
