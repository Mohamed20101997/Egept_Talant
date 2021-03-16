<?php

namespace App\Http\Repositories;


use App\Http\Interfaces\SubscriptionInterface;
use App\Http\Traits\ApiDesignTrait;
use App\StudentGroup;

class SubscriptionRepository implements SubscriptionInterface
{

    use ApiDesignTrait;

    private $studentGroupModel;
    public function __construct(StudentGroup $studentGroup)
    {
        $this->studentGroupModel = $studentGroup;

    }


    public function limitSubscription()
    {
        $limitSubscription  = $this->studentGroupModel::whereIn('count',[1,2])->with('student' ,'group')->get();
        if(count($limitSubscription) > 0 ){

        return $this->ApiResponse(200 , 'Done' , null , $limitSubscription);
        }
        return $this->ApiResponse(200 , 'Done' , null , 'No record');
    }


    public function closedSubscription()
    {
        $closedSubscription  = $this->studentGroupModel::where('count',0)->with('student' ,'group')->get();

        if(count($closedSubscription) > 0 ){

            return $this->ApiResponse(200 , 'Done' , null , $closedSubscription);
        }
        return $this->ApiResponse(200 , 'Done' , null , 'No record');

    }


}

