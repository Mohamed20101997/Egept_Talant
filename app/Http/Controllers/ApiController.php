<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiDesignTrait;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiDesignTrait;

    public function testApi($name){

        if($name == 'mohamed'){

            return $this->ApiResponse(200,'Done',null,$name);
        }else{

            return $this->ApiResponse(422,'Validation Errors','name just be mohamed');
        }

    }
}
