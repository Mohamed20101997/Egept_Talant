<?php

namespace App\Http\Controllers;
use App\Http\Interfaces\AuthInterface;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    private $authInterface;
    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function login()
    {
        return $this->authInterface->login();
    }

    public function profile(Request $request)
    {
        return $this->authInterface->profile($request);
    }


}
