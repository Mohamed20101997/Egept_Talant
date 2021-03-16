<?php

namespace App\Http\Repositories;

use App\GroupSession;
use App\Http\Interfaces\SessionInterface;
use App\StudentGroup;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiDesignTrait;

class SessionRepository implements SessionInterface
{

    use ApiDesignTrait;

    private $sessionModel;
    private $studentGroupModel;
    public function __construct(GroupSession $session,StudentGroup $studentGroup)
    {
        $this->sessionModel = $session;
        $this->studentGroupModel = $studentGroup;

    }

    public function addSession($request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'group_id' => 'required|exists:groups,id',
            'from' => 'required|date_format:H:i',
            'to'   => 'required|date_format:H:i|after:from',
            'link' => 'required|url',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $this->sessionModel->create([
            'name' => $request->name,
            'group_id' => $request->group_id,
            'from' => $request->from,
            'to' => $request->to,
            'link' => $request->link,
        ]);

        $this->studentGroupModel::where([ ['group_id', $request->group_id] , ['count', '>' , 0] ])->decrement('count');
        return $this->ApiResponse(200, 'Session Was Created');
    }

    public function allSession()
    {
        $allSession = $this->sessionModel->with('group')->get();
        return $this->ApiResponse(200, 'Done', null, $allSession);

    }

    public function updateSession($request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'group_id'   => 'required|exists:groups,id',
            'from'       => 'required|date_format:H:i',
            'to'         => 'required|date_format:H:i|after:from',
            'link'       => 'required|url',
            'session_id' => 'required|exists:group_sessions,id',
        ]);


        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $session = $this->sessionModel->find($request->session_id);

        if ($session) {
            $session->update([
                'name'  => $request->name,
                'group_id'  => $request->group_id,
                'from'      => $request->from,
                'to'        => $request->to,
                'link'      => $request->link,
            ]);
            return $this->ApiResponse(200, 'Session Was updated', null, $session);
        }

        return $this->ApiResponse(422, 'This Session  is not find');
    }

    public function deleteSession($request)
    {
        $validation = Validator::make($request->all(), [
            'session_id' => 'required|exists:group_sessions,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $session = $this->sessionModel->find($request->session_id);

        if ($session) {

            $session->delete();    // soft deleting

            return $this->ApiResponse(200, 'Session Was deleted');
        }
        return $this->ApiResponse(422, 'This Session is not find');
    }

    public function specificSession($request)
    {

        $validation = Validator::make($request->all(), [
            'session_id' => 'required|exists:group_sessions,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $session = $this->sessionModel->with('group')->find($request->session_id);

        if ($session) {
            return $this->ApiResponse(200, 'Done', null, $session);
        }

        return $this->ApiResponse(422, 'This Session is not find');
    }
}
