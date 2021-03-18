<?php

namespace App\Http\Repositories;


use App\Complaints;
use App\Discussion;
use App\DiscussionComment;
use App\Http\Interfaces\EndUserInterface;
use App\Http\Traits\ApiDesignTrait;
use App\Group;
use App\StudentGroup;
use Illuminate\Support\Facades\Validator;

class EndUserRepository implements EndUserInterface
{

    use ApiDesignTrait;

    private $groupModel;
    private $studentGroup;
    private $complaintModel;
    private $discussionModel;
    private $discussionCommentModel;

    public function __construct(Group $group, StudentGroup $studentGroup, Complaints $complaint , Discussion $discussion , DiscussionComment $discussionComment)
    {
        $this->groupModel = $group;
        $this->studentGroup = $studentGroup;
        $this->complaintModel = $complaint;
        $this->discussionModel = $discussion;
        $this->discussionCommentModel = $discussionComment;
    }

    public function userGroups()
    {
        $userId = auth()->user()->id;
        $userRole = auth()->user()->roleName->name;

        if ($userRole == 'Teacher') {
            $data = $this->groupModel::where('teacher_id', $userId)->withCount('groupStudents')->get();

        } elseif ($userRole == 'Student') {

            $data = $this->groupModel::wherehas('groupStudents', function ($query) use ($userId) {
                return $query->where([['student_id', $userId], ['count', '>=', 1]]);

            })->withCount('groupStudents')->get();
        }

        return $this->ApiResponse(200, 'Done', null, $data);

    }

    public function complaint($request)
    {

        $userId = auth()->user()->id;

        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $this->complaintModel->create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $userId,
        ]);


        return $this->ApiResponse(200, 'Complaint Was Created');

    }

    public function schedule()
    {

        $userAuth = auth()->user();
        $userRole = $userAuth->roleName->name;
        $userId = $userAuth->id;

        if ($userRole == 'Teacher') {
            $schedule = $this->groupModel::where('teacher_id', $userId);

        } elseif ($userRole == 'Student') {

            $schedule = $this->groupModel::wherehas('groupStudents', function ($query) use ($userId) {
                $query->where('student_id', $userId);
            });

        }
        /**  end if and else if check*/

        $UserSchedule = $schedule->with('groupDates', 'teacher:id,name')->get();
        return $this->ApiResponse(200, 'Done', null, $UserSchedule);

    }

    public function timeLine($request)
    {

        $validation = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
        ]);

        if ($validation->fails()) {
            return $this->apiResponse(422, 'Error', $validation->errors());
        }


        $userAuth = auth()->user();
        $userRole = $userAuth->roleName->name;
        $userId = $userAuth->id;

        if ($userRole == 'Teacher') {
            $timeLine = $this->groupModel::where('teacher_id', $userId);

        } elseif ($userRole == 'Student') {

            $timeLine = $this->groupModel::wherehas('groupStudents', function ($query) use ($userId) {
                $query->where('student_id', $userId);
            });

        }

        $UsertimeLine = $timeLine->with('groupDates', 'teacher:id,name')->find($request->group_id);
        return $this->ApiResponse(200, 'Done', null, $UsertimeLine);

    }


    public function addDiscussion($request)
    {
        $validation = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
            'title' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->apiResponse(422, 'Error', $validation->errors());
        }

        if($this->validateUserGroup($request->group_id)){

            $this->discussionModel::create([
                'group_id' => $request->group_id,
                'title' => $request->title,
                'user_id' => auth()->id(),
            ]);

            return $this->ApiResponse(200, 'Added Discussion Successfully');
        }

        return $this->apiResponse(422,'You have no access to this group');

    }

    public function allDiscussion($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails()){
            return $this->apiResponse(422,'Error',$validation->errors());
        }

        if($this->validateUserGroup($request->group_id)){

            $discussions = $this->discussionModel::where('group_id', $request->group_id)->get();

            return $this->apiResponse(200,'All Group Discussions', null, $discussions);
        }

        return $this->apiResponse(422,'You have no access to this group');
    }


    public function discussionComment($request)
    {
        $validation = Validator::make($request->all(),[
            'discussion_id' => 'required|exists:discussions,id',
            'comment' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->apiResponse(422, 'Error', $validation->errors());
        }

        $discussion = $this->discussionModel->find($request->discussion_id);

        if($this->validateUserGroup($discussion->group_id)){

            $this->discussionCommentModel::create([
                'discussion_id' => $discussion->id,
                'comment' => $request->comment,
                'user_id' => auth()->id(),
            ]);

            return $this->ApiResponse(200, 'Added Comment Successfully');
        }

        return $this->apiResponse(422,'You have no access to this group');

    }

    private function validateUserGroup($group_id)
    {
        $user = auth()->user();
        $userRole = $user->rolename->name;
        $userId =  $user->id;

        if($userRole == 'Teacher'){
            $userGroup  = $this->groupModel::where('teacher_id', $userId)->find($group_id);

        }else if($userRole == 'Student'){

            $userGroup  = $this->groupModel::whereHas('groupStudents', function($query) use ($userId){
                $query->where('student_id', $userId);
            })->find($group_id);

        }
        return $userGroup ;
    }
}

