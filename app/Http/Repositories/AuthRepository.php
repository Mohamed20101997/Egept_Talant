<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\ApiDesignTrait;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthRepository implements AuthInterface{

    use ApiDesignTrait;

    private $userModel;
    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->ApiResponse(422, 'Unauthorized');
        }

        return $this->respondWithToken($token);
    }


    public function profile($request)
    {
        $validation = Validator::make($request->all(),[
            'old_password'      => 'required|min:6',
            'password'          => 'required|min:6|confirmed',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $hashedPassword = auth()->user()->password;
        if(Hash::check($request->old_password , $hashedPassword )){

            $this->userModel->find(auth()->user()->id)->update([

               'password' =>   Hash::make($request->password),
            ]);

            return $this->ApiResponse(200 , 'password updated successfully');
        }

        return $this->ApiResponse(422, 'DontMatch');
    }





    protected function respondWithToken($token)
    {
        $userData =  $this->userModel->find(auth()->user()->id);
        $roleName = auth()->user()->roleName->name;
        $data = [
            'name' => $userData->name,
            'email' => $userData->email,
            'phone' => $userData->phone,
            'role_id' => $userData->role_id,
            'role' => $roleName,
            'access_token' => $token,
        ];

        return $this->ApiResponse(200,'Done', null, $data);
    }
}
