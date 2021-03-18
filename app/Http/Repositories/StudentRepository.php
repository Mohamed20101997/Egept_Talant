<?php

namespace App\Http\Repositories;

use App\Group;
use App\Http\Interfaces\StudentInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiDesignTrait;
use App\StudentGroup;
use App\Role;
use App\User;


class StudentRepository implements StudentInterface {

    use ApiDesignTrait;

    private $roleModel;
    private $groupModel;
    private $userModel;
    private $studentGroupModel;
    public function __construct(Role $role , User $user , StudentGroup $studentGroup, Group $group)
    {
        $this->roleModel = $role;
        $this->userModel = $user;
        $this->groupModel = $group;
        $this->studentGroupModel = $studentGroup;
    }

    public function addStudent($request)
    {
        $validation = Validator::make($request->all(),[
           'name' => 'required|min:3',
           'phone' => 'required|min:10',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:8',
            'groups' => 'required',
        ]);

        if($validation->fails())
        {
             return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }


        $groups = $request->groups;
        for($i = 0 ; $i <= count($groups) ; $i++){
            for($j = $i+1 ; $j < count($groups) ; $j++){
                if($groups[$i][0] == $groups[$j][0]){

                    return $this->ApiResponse(422 , 'Validation Error', 'Group is exist before');

                }
            } // end for
        } //end for

        for($i = 0 ; $i < count($groups) ; $i++){

            if(count($groups[$i]) != 3){
                return $this->ApiResponse(422 , 'Validation Error', 'every group must be three items');

            }elseif(! $this->groupModel::find($groups[$i][0])){

                return $this->ApiResponse(422 , 'Validation Error', 'This Group Is Not Exist');
            }

        } // end for


        $studentRole  = $this->roleModel->where([['is_teacher' , 0],['is_staff' , 0]])->first();

        $student =  $this->userModel->create([
            'name' => $request->name,
            'phone' =>  $request->phone,
            'email' =>  $request->email,
            'password' =>  Hash::make($request->password),
            'role_id'   => $studentRole->id,
        ]);

        for($i=0 ; $i < count($groups) ; $i++){

            $this->studentGroupModel->create([
               'student_id' => $student->id,
               'group_id' => $groups[$i][0],
               'count' => $groups[$i][1],
               'price' => $groups[$i][2],
            ]);

        } //end foreach for create groups


        return $this->ApiResponse(200 , 'Student Was Created');

    }  //end of add student with Groups


    public function allStudent()
    {
        $allStudent = $this->getUser()->withCount('studentGroups')->get();

        return $this->ApiResponse(200 , 'Done' , null , $allStudent);

    }


    public function updateStudent($request)
    {

            $validation = Validator::make($request->all(),[
                'name' => 'required|min:3',
                'phone' => 'required|min:10',
                'email' => 'required|email|unique:users,email,'.$request->student_id,
                'password' => 'sometimes|min:8',
                'student_id' => 'required|exists:users,id',
                'groups' => 'required',
            ]);

            if($validation->fails())
            {
                return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
            }

            $groups = $request->groups;

            for($i = 0 ; $i <= count($groups) ; $i++){
                for($j = $i+1 ; $j < count($groups) ; $j++){
                    if($groups[$i][0] == $groups[$j][0]){
                        return $this->ApiResponse(422 , 'Validation Error', 'Group is exist before');
                    }
                } // end for
            } //end for

            for($i = 0 ; $i < count($groups) ; $i++){
                 if(count($groups[$i]) != 3){

                     return $this->ApiResponse(422 , 'Validation Error', 'every group must be three items');

                 }elseif(! $this->groupModel::find($groups[$i][0])){

                     return $this->ApiResponse(422 , 'Validation Error', 'This Group Is Not Exist');
                 }
             } // end for

        $student =  $this->getUser()->find($request->student_id);

            if($student)
            {
                $student->update(['name' => $request->name,
                    'name' => $request->name,
                    'phone' =>  $request->phone,
                    'email' =>  $request->email,
                    'password' =>  Hash::make($request->password),

                ]);

                if($request->has('groups')){

                    $newGroups = [];

                    for($i=0 ; $i< count($groups) ; $i++){

                        $newGroups[] = $groups[$i][0];

                        $studentGroup = $this->studentGroupModel::where([ ['student_id',$request->student_id],['group_id', $groups[$i][0]] ])->first();

                        if($studentGroup){
                            $studentGroup->update([
                                'count' => $groups[$i][1],
                                'price' => $groups[$i][2],
                            ]);

                        }else{

                            $this->studentGroupModel::create([
                                'student_id' => $request->student_id,
                                'group_id' => $groups[$i][0],
                                'count' => $groups[$i][1],
                                'price' => $groups[$i][2],
                            ]);

                        } // end if and else

                    } // end for

                } // end if

                $this->studentGroupModel::whereNotIn('group_id' ,$newGroups)->where('student_id', $request->student_id)->delete();
//                if($request->has('groups'))
//                {
//                    foreach($request->groups as $group){
//                        $requestGroup = explode(',', $group);
//                        $this->studentGroupModel->create([
//                            'student_id' => $request->student_id,
//                            'group_id' => $requestGroup[0],
//                            'count' => $requestGroup[1],
//                            'price' => $requestGroup[2],
//                        ]);
//                    }
//                }
//
                return $this->ApiResponse(200 , 'Student Was updated' ,null ,$student);
            }

        return $this->ApiResponse(422 , 'This Student  is not find');
    }

    public function deleteStudent($request)
    {
        $validation = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $student =  $this->getUser()->find($request->student_id);


        if($student){

            $student->delete();

            return $this->ApiResponse(200 , 'Student Was deleted');
        }
        return $this->ApiResponse(422 , 'This Student is not find');
    }

    public function specificStudent($request)
    {

        $validation = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $specificStudent = $this->getUser()->with('roleName')->find($request->student_id);

        if($specificStudent)
        {
            return $this->ApiResponse(200 , 'Done' , null , $specificStudent);
        }

        return $this->ApiResponse(422 , 'This Student is not find');
    }


    public function updateStudentGroup($request){

    }


    public function deleteStudentGroup($request){

    }
}
