<?php

namespace App\Http\Controllers;
use App\Http\Interfaces\SessionInterface;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    private $sessionInterface;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface = $sessionInterface;
    }

    public function addSession(Request $request)
    {
        return $this->sessionInterface->addSession( $request);
    }

    public function allSession(Request $request)
    {
        return $this->sessionInterface->allSession( $request);
    }

    public function specificSession(Request $request)
    {
        return $this->sessionInterface->specificSession( $request);
    }


    public function updateSession(Request $request)
    {
        return $this->sessionInterface->updateSession( $request);
    }


    public function deleteSession(Request $request)
    {
        return $this->sessionInterface->deleteSession($request);
    }

}
