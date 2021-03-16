<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupSession extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','group_id','from','to','link'] ;

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_at',
    ];


/////////////    Relations  /////////////

    public function group(){
        return $this->belongsTo(Group::class , 'group_id','id');
    }
}
