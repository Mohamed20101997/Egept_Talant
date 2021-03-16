<?php

namespace App\Http\Interfaces;

interface SessionInterface{


    public function addSession($request);

    public function allSession();

    public function specificSession($request);

    public function updateSession($request);

    public function deleteSession($request);

}
