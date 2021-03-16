<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    protected $fillable = ['title', 'body', 'user_id'];

    protected $hidden = ['created_at', 'updated_at', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
