<?php

namespace App\Http\Traits;


use App\User;

trait ApiDesignTrait{

    public function ApiResponse($code = 200 , $message = null , $errors = null , $data = null){

        $array = [
            'status' => $code,
            'message' => $message,
        ];

        if(is_null($data) && !is_null($errors)){

            $array['errors'] = $errors;

        }elseif(!is_null($data) && is_null($errors)){

            $array['data'] = $data;
        }

        return response($array , 200);
    }


    public function getUser($type=null , $id=null){
        if($type == 'is_staff' || $type == 'is_teacher'){

             $user = User::whereHas('roleName', function($query) use ($type,$id) {
                return $query->where($type,$id);
            });

        }else{
            $user = User::whereHas('roleName', function($query){
                 $query->where([['is_teacher' , 0],['is_staff' , 0]]);
            });
        }

         return $user ;
    }

}
