<?php

namespace App\Http\Repositories;

use App\Exam;
use App\ExamType;
use App\Http\Interfaces\ExamInterface;
use App\Question;
use App\StudentExam;
use App\StudentExamAnswer;
use App\StudentGroup;
use App\SystemAnswer;
use App\EssayAnswerCheck;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiDesignTrait;


class ExamRepository implements ExamInterface
{

    use ApiDesignTrait;

    private $examTypeModel;
    private $examModel;
    private $studentGroupModel;
    private $questionModel;
    private $systemAnswerModel;
    private $studentExamModel;
    private $studentExamAnswerModel;
    private $esaayMarkedCheckModel;


    public function __construct(ExamType $examType,EssayAnswerCheck $esaayMarkedCheck,Exam $exam , StudentExamAnswer $studentExamAnswer,StudentGroup $studentGroup, Question $question ,SystemAnswer $systemAnswer ,StudentExam $studentExam)
    {
        $this->examTypeModel = $examType;
        $this->examModel = $exam;
        $this->studentGroupModel = $studentGroup;
        $this->questionModel = $question;
        $this->systemAnswerModel = $systemAnswer;
        $this->studentExamModel = $studentExam;
        $this->studentExamAnswerModel = $studentExamAnswer;
        $this->esaayMarkedCheckModel = $esaayMarkedCheck;
    }


    public function examTypes()
    {
        $examTypes = $this->examTypeModel::get();
        return $this->ApiResponse(200, 'Done', null, $examTypes);
    }

    public function creatExam($request)
    {
        $validation = Validator::make($request->all() , [
            'name' => 'required',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
            'time' => 'required|date_format:H:i',
            'degree' => 'required',
            'count' => 'required',
            'type_id' => 'required|exists:exam_types,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $this->examModel::create([
            'name' => $request->name,
            'start' => $request->start,
            'end' =>  $request->end,
            'time' =>  $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'type_id' =>  $request->type_id,
            'group_id' => $request->group_id,
            'teacher_id' => auth()->user()->id,
        ]);

        return $this->ApiResponse(200 , 'Exam was Created Successfully');
    }

    public function allExams()
    {
        $userRole = auth()->user()->roleName->name;
        $userId = auth()->user()->id;

        if($userRole == 'Teacher'){

            $exams = $this->examModel::where('teacher_id', $userId)->get();

        }elseif ($userRole == 'Student'){

            $groups = $this->studentGroupModel::where([['student_id', $userId] ,['count' ,'>' ,0]])->get();
            $groupIds = [];

            foreach ($groups as $group){
                $groupIds [] = $group->group_id;
            }

            $exams = $this->examModel::whereIn('group_id', $groupIds)->get();

        } //end if condition

        return $this->ApiResponse(200, 'Done', null, $exams);
    }

    public function deleteExam($request)
    {
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $exam = $this->examModel->find($request->exam_id);

        if ($exam) {

            $exam->delete();    // soft deleting

            return $this->ApiResponse(200, 'Exam Was deleted');
        }
        return $this->ApiResponse(422, 'This Exam is not find');
    }

    public function updateExam($request)
    {
        $validation = Validator::make($request->all() , [
            'name' => 'required',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
            'time' => 'required|date_format:H:i',
            'degree' => 'required',
            'count' => 'required',
            'group_id' => 'required|exists:groups,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        if($validation->fails()) {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $this->examModel::find($request->exam_id)->update([
            'name' => $request->name,
            'start' => $request->start,
            'end' =>  $request->end,
            'time' =>  $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'group_id' => $request->group_id,
        ]);

        return $this->ApiResponse(200 , 'Exam was Updated Successfully');

    }

    public function updateExamStatus($request)
    {
        $validation = Validator::make($request->all , [
            'exam_id' => 'required|exists:exams,id',
            'status' => 'required',
        ]);

        if($validation->fails())
        {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $exam =  $this->examModel::where('id', $request->exam_id)->first();

        $exam->update([
            'close'  => $request->status,
        ]);

        return $this->ApiResponse(200 , 'Exam Status was Updated' );
    }

    public function addQuestions($request)
    {
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'title'   => 'required|min:5',
            'type_id' => 'required|exists:exam_types,id',
            'answer'  => 'required_Unless:type_id,3|integer'
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $question = $this->questionModel::create([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
        ]);

        if($request->has('answer')){
            $this->systemAnswerModel::create([
                'answer' => $request->answer,
                'question_id' => $question->id,
                'type_id' => $request->type_id,
            ]);
        }

        return $this->ApiResponse(200, 'Created Question');

    }

    public function updateQuestions($request)
    {

        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'title'   => 'required|min:5',
            'answer'   => 'integer',
            'question_id' => 'required|exists:questions,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

            $this->questionModel::find($request->question_id)->update([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
        ]);

        if($request->has('answer')){

            $this->systemAnswerModel::where('question_id' , $request->question_id)->update([
                'answer' => $request->answer,
            ]);

        }

        return $this->ApiResponse(200, 'Udated Question');

    }

    public function deleteQuestions($request)
    {
        $validation = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $question = $this->questionModel->find($request->question_id);

        if ($question) {

            $question->delete();

            return $this->ApiResponse(200, 'Question Was deleted');
        }
        return $this->ApiResponse(422, 'This Question is not find');

    }

    public function examStudents($request)
    {
        $validation = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $exam = $this->studentExamModel::where('exam_id' , $request->exam_id)->with('studentData')->get();

        return $this->ApiResponse(200, 'Done', null , $exam);
    }

    public function examStudentDetails($request)
    {
        $validation = Validator::make($request->all(), [
            'student_exam_id' => 'required|exists:student_exams,id',
        ]);

        if ($validation->fails()) {
            return $this->ApiResponse(422, 'Validation Error', $validation->errors());
        }

        $markedExam = $this->studentExamModel::where('id' , $request->student_exam_id)
            ->whereHas('examData' , function ($query){
                $query->whereHas('examType', function($q){
                   $q->where('is_mark', 1);
                });
            })->first();

        if($markedExam){
            $data = $this->studentExamAnswerModel::where('student_exam_id' , $request->student_exam_id)->with('studentQuestion')->get();
        }else{

            $examMarked = $this->esaayMarkedCheckModel::where('student_exam_id' , $request->student_exam_id)->first();

            if($examMarked){
                $data = $this->studentExamAnswerModel::where('student_exam_id' , $request->student_exam_id)->with('studentQuestion')->get();

            }else{
                $data = $this->studentExamAnswerModel::where('student_exam_id' , $request->student_exam_id)->with('studentQuestion')->get(['id' ,'question_id' ,'answer']);

            }//end of $examMarked check
        } //end of $markedExam check

        return $this->ApiResponse(200, 'Done', null , $data);
    }
}
