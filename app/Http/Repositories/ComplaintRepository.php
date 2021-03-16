<?php

namespace App\Http\Repositories;

use App\Complaints;
use App\Http\Interfaces\ComplaintInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiDesignTrait;

class ComplaintRepository implements ComplaintInterface
{

    use ApiDesignTrait;

    private $complaintModel;
    public function __construct(Complaints $complaint)
    {
        $this->complaintModel = $complaint;

    }


    public function allComplaint()
    {
        $allComplaint = $this->complaintModel::with('user:id,name')->get();
        if(count($allComplaint) > 0){

            return $this->ApiResponse(200, 'Done', null, $allComplaint);
        }
        return $this->ApiResponse(200, 'Done', null,'No record');

    }



    public function deleteComplaint($request)
    {
        $validation = Validator::make($request->all(), [
            'complaint_id' => 'required|exists:complaints,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $complaint = $this->complaintModel->find($request->complaint_id);

        if ($complaint) {

            $complaint->delete();
            return $this->ApiResponse(200, 'Complaint Was deleted');
        }
        return $this->ApiResponse(422, 'This Complaint is not find');
    }

    public function specificComplaint($request)
    {

        $validation = Validator::make($request->all(), [
            'complaint_id' => 'required|exists:complaints,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $complaint = $this->complaintModel->with('user:id,name')->find($request->complaint_id);

        if ($complaint) {

            return $this->ApiResponse(200, 'Done', null, $complaint);
        }

        return $this->ApiResponse(422, 'This Complaint is not find');
    }
}
