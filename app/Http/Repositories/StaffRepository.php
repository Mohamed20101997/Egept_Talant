<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\StaffInterface;
use App\Http\Traits\ApiDesignTrait;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class StaffRepository implements StaffInterface{

    use ApiDesignTrait;


    private $roleModel;
    private $userModel;
    public function __construct(Role $role , User $user)
    {
        $this->roleModel = $role;
        $this->userModel = $user;

    }

    public function addStaff($request)
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

        return $this->ApiResponse(200 , 'User Was Created');
    }

    public function allStaff()
    {
        $allStaff = $this->getUser( 'is_staff' , 1 )->with('roleName')->get();

        return $this->ApiResponse(200 , 'Done' , null , $allStaff);

    }

    public function updateStaff($request)
    {
            $validation = Validator::make($request->all(),[
                'name' => 'required|min:3',
                'phone' => 'required',
                'email' => 'required|email|unique:users,email,'.$request->staff_id,
                'password' => 'sometimes|min:8',
                'role_id' => 'required|exists:roles,id',
                'staff_id' => 'required|exists:users,id',
            ]);


            if($validation->fails())
            {
                return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
            }

            $staff =  $this->getUser( 'is_staff' , 1)->with('roleName')->find($request->staff_id);

            if($staff)
            {
                $staff->update(['name' => $request->name,
                    'phone' =>  $request->phone,
                    'email' =>  $request->email,
                    'password' =>  Hash::make($request->password),
                    'role_id' =>  $request->role_id,
                ]);
                return $this->ApiResponse(200 , 'User Was updated' ,null ,$staff);
            }

        return $this->ApiResponse(422 , 'This user is not find');
    }

    public function deleteStaff($request)
    {
        $validation = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $staff =  $this->getUser( 'is_staff' ,  1 )->find($request->staff_id);


        if($staff){
            $staff->delete();

            return $this->ApiResponse(200 , 'User Was deleted');
        }
        return $this->ApiResponse(422 , 'This user is not find');
    }

    public function specificStaff($request)
    {

        $validation = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $specificStaff = $this->getUser( 'is_staff' , 1 )->with('roleName')->find($request->staff_id);


        if($specificStaff)
        {
            return $this->ApiResponse(200 , 'Done' , null , $specificStaff);
        }

        return $this->ApiResponse(422 , 'This user is not find');
    }
}
