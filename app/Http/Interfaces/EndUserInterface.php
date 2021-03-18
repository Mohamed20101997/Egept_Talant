<?php

namespace App\Http\Interfaces;

interface EndUserInterface{


    public function userGroups();

    public function complaint($request);

    public function schedule();

    public function timeLine($request);

    public function addDiscussion($request);

    public function allDiscussion($request);

    public function discussionComment($request);

}
