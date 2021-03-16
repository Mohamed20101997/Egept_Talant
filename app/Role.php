<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name','is_staff'];

    public function roleUsers(){

        return $this->hasMany(User::class , 'role_id' , 'id');
    }

    protected $hidden = ['created_at','updated_at', 'is_staff'];
}
