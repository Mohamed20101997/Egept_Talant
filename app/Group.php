<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name','body','image','teacher_id','created_by'];

    protected $hidden = [
        'created_at','updated_at','created_by','teacher_id'
    ];

    public function groupStudents()
    {
        return $this->hasMany(StudentGroup::class , 'group_id' , 'id');
    }

    public function groupDates()
    {
        return $this->hasMany(GroupDate::class , 'group_id' , 'id');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class , 'teacher_id' , 'id');
    }
}
