<?php

namespace App\Http\Controllers;
use App\Http\Interfaces\SubscriptionInterface;


class SubscriptionController extends Controller
{
    private $SubscriptionInterface;

    public function __construct(SubscriptionInterface $SubscriptionInterface)
    {
        $this->SubscriptionInterface = $SubscriptionInterface;
    }

    public function limitSubscription()
    {
        return $this->SubscriptionInterface->limitSubscription();
    }

    public function closedSubscription( )
    {
        return $this->SubscriptionInterface->closedSubscription( );
    }



}
