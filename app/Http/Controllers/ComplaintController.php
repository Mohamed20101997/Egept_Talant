<?php

namespace App\Http\Controllers;
use App\Http\Interfaces\ComplaintInterface;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    private $complaintInterface;


    public function __construct(ComplaintInterface $complaintInterface)
    {
        $this->complaintInterface = $complaintInterface;
    }


    public function allComplaint(Request $request)
    {
        return $this->complaintInterface->allComplaint($request);
    }


    public function specificComplaint(Request $request)
    {
        return $this->complaintInterface->specificComplaint( $request);
    }


    public function deleteComplaint(Request $request)
    {
        return $this->complaintInterface->deleteComplaint($request);
    }

}
