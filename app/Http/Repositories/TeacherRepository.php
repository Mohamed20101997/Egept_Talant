<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\TeacherInterface;
use App\Http\Traits\ApiDesignTrait;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class TeacherRepository implements TeacherInterface {

    use ApiDesignTrait;

    private $roleModel;
    private $userModel;
    public function __construct(Role $role , User $user)
    {
        $this->roleModel = $role;
        $this->userModel = $user;

    }

    public function addTeacher($request)
    {
        $validation = Validator::make($request->all(),[
           'name' => 'required|min:3',
           'phone' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:8',
           'role_id' => 'required|exists:roles,id',
        ]);

        if($validation->fails())
        {
             return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $this->userModel->create([
            'name' => $request->name,
            'phone' =>  $request->phone,
            'email' =>  $request->email,
            'password' =>  Hash::make($request->password),
            'role_id' =>  $request->role_id,
        ]);

        return $this->ApiResponse(200 , 'Teacher Was Created');
    }

    public function allTeacher()
    {
        $allTeacher = $this->getUser( 'is_teacher' , 1 )->with('roleName')->get();

        return $this->ApiResponse(200 , 'Done' , null , $allTeacher);

    }

    public function updateTeacher($request)
    {
            $validation = Validator::make($request->all(),[
                'name' => 'required|min:3',
                'phone' => 'required',
                'email' => 'required|email|unique:users,email,'.$request->teacher_id,
                'password' => 'sometimes|min:8',
                'role_id' => 'required|exists:roles,id',
                'teacher_id' => 'required|exists:users,id',
            ]);


            if($validation->fails())
            {
                return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
            }

            $teacher =  $this->getUser( 'is_teacher' , 1)->with('roleName')->find($request->teacher_id);

            if($teacher)
            {
                $teacher->update(['name' => $request->name,
                    'phone' =>  $request->phone,
                    'email' =>  $request->email,
                    'password' =>  Hash::make($request->password),
                    'role_id' =>  $request->role_id,
                ]);
                return $this->ApiResponse(200 , 'Teacher Was updated' ,null ,$teacher);
            }

        return $this->ApiResponse(422 , 'This Teacher  is not find');
    }

    public function deleteTeacher($request)
    {
        $validation = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $teacher =  $this->getUser( 'is_teacher' ,  1 )->find($request->teacher_id);


        if($teacher){

            $teacher->delete();

            return $this->ApiResponse(200 , 'Teacher Was deleted');
        }
        return $this->ApiResponse(422 , 'This Teacher is not find');
    }

    public function specificTeacher($request)
    {

        $validation = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $specificTeacher = $this->getUser( 'is_teacher' , 1 )->with('roleName')->find($request->teacher_id);

        if($specificTeacher)
        {
            return $this->ApiResponse(200 , 'Done' , null , $specificTeacher);
        }

        return $this->ApiResponse(422 , 'This Teacher is not find');
    }
}
