<?php

namespace App\Http\Repositories;

use App\GroupDate;
use App\Http\Interfaces\GroupInterface;
use App\Http\Traits\ApiDesignTrait;
use App\Group;
use App\Rules\ValidDay;
use Illuminate\Support\Facades\Validator;


class GroupRepository implements GroupInterface {

    use ApiDesignTrait;

    private $groupModel;
    private $GroupDateModel;
    public function __construct(Group $group , GroupDate $GroupDate)
    {
        $this->groupModel = $group;
        $this->GroupDateModel = $GroupDate;

    }


    public function addGroup($request)
    {

        $validation = Validator::make($request->all(),[
           'name'       => 'required|min:3',
           'body'       => 'required',
           'image'      => 'required',
           'teacher_id' => 'required|exists:users,id',
           'dates' => ['required',new ValidDay()],
        ]);


        if($validation->fails())
        {
             return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        /* validate that day is not duplicated*/
        $dates = $request->dates;
        for($i=0 ; $i <= count($dates) ; $i++) {
            for($j=$i+1 ; $j< count($dates)-1; $j++){
                if($dates[$i][0] == $dates[$j][0]){

                    return $this->ApiResponse(422 , 'Validation Error', 'Day is exist before');

                }
            }
        }

        $group = $this->groupModel->create([
            'name' => $request->name,
            'body' =>  $request->body,
            'image' =>  $request->image,
            'teacher_id' => $request->teacher_id ,
            'created_by' =>  auth()->user()->id,
        ]);

        for($i=0 ; $i< count($dates); $i++){
             $this->GroupDateModel::create([
                'day' => $dates[$i][0],
                'from' => $dates[$i][1],
                'to'   => $dates[$i][2],
                'group_id' => $group->id
            ]);
        }



        return $this->ApiResponse(200 , 'Group Was Created');
    }

    public function allGroup()
    {
        $allGroup = $this->groupModel->get();

        return $this->ApiResponse(200 , 'Done' , null , $allGroup);

    }

    public function updateGroup($request)
    {
            $validation = Validator::make($request->all(),[
                'name'       => 'required|min:3',
                'body'       => 'required',
                'image'      => 'required',
                'teacher_id' => 'required|exists:users,id',
                'dates' => ['required',new ValidDay()],
            ]);


            if($validation->fails())
            {
                return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
            }

        /* validate that day is not duplicated*/
        $dates = $request->dates;
        for($i=0 ; $i <= count($dates) ; $i++) {
            for($j=$i+1 ; $j< count($dates)-1; $j++){
                if($dates[$i][0] == $dates[$j][0]){

                    return $this->ApiResponse(422 , 'Validation Error', 'Day is exist before');

                }
            }
        }

            $group =  $this->groupModel->find($request->group_id);

            if($group)
            {
                $group->update(['name' => $request->name,
                    'name' => $request->name,
                    'body' =>  $request->body,
                    'image' =>  $request->image,
                    'teacher_id' => $request->teacher_id ,
                    'created_by' =>  auth()->user()->id,
                ]);


                for($i=0 ; $i< count($dates); $i++){
                    $GroupDate = $this->GroupDateModel::where('group_id' , $request->group_id)->first();
                    if($GroupDate){
                        $GroupDate->update([
                            'day' => $dates[$i][0],
                            'from' => $dates[$i][1],
                            'to'   => $dates[$i][2],
                            'group_id' => $request->group_id
                        ]);
                    }

                }

                return $this->ApiResponse(200 , 'Group Was updated' ,null ,$group);
            }

        return $this->ApiResponse(422 , 'This Group  is not find');
    }

    public function deleteGroup($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $group =  $this->groupModel->find($request->group_id);


        if($group){

            $group->delete();

            return $this->ApiResponse(200 , 'Group Was deleted');
        }
        return $this->ApiResponse(422 , 'This Group is not find');
    }

    public function specificGroup($request)
    {

        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $specificGroup = $this->groupModel->find($request->group_id);

        if($specificGroup)
        {
            return $this->ApiResponse(200 , 'Done' , null , $specificGroup);
        }

        return $this->ApiResponse(422 , 'This Group is not find');
    }
}
