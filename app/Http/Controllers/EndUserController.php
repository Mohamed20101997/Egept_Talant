<?php

namespace App\Http\Controllers;
use App\Http\Interfaces\EndUserInterface;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    private $endUserInterface;

    public function __construct(EndUserInterface $endUserInterface)
    {
        $this->endUserInterface = $endUserInterface;
    }

    public function userGroups()
    {
        return $this->endUserInterface->userGroups();
    }

    public function complaint(Request $request)
    {
        return $this->endUserInterface->complaint($request);
    }

    public function schedule(){
        return $this->endUserInterface->schedule();
    }

    public function timeLine(Request $request){
        return $this->endUserInterface->timeLine($request);
    }


    public function addDiscussion(Request $request){
        return $this->endUserInterface->addDiscussion($request);
    }

    public function allDiscussion(Request $request){
        return $this->endUserInterface->allDiscussion($request);
    }

    public function discussionComment(Request $request){
        return $this->endUserInterface->discussionComment($request);
    }

}
