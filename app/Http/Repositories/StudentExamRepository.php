<?php

namespace App\Http\Repositories;

use App\Exam;
use App\ExamType;
use App\Http\Interfaces\StudentExamInterface;
use App\Question;
use App\SystemAnswer;
use App\StudentExam;
use App\StudentExamAnswer;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiDesignTrait;

class StudentExamRepository implements StudentExamInterface
{

    use ApiDesignTrait;

    private $examTypeModel;
    private $examModel;
    private $questionModel;
    private $systemAnswerModel;
    private $studentExamModel;
    private $studentExamAnswerModel;

    public function __construct(ExamType $examType, Exam $exam ,StudentExamAnswer $studentExamAnswer , Question $question ,SystemAnswer $systemAnswer,StudentExam $studentExam)
    {
        $this->examTypeModel = $examType;
        $this->examModel = $exam;
        $this->questionModel = $question;
        $this->systemAnswerModel = $systemAnswer;
        $this->studentExamModel = $studentExam;
        $this->studentExamAnswerModel = $studentExamAnswer;
    }

    public function newExams()
    {
        $userId = auth()->user()->id;
        $newExam = $this->examModel::where('close', 0)->whereHas('studentGroups' , function($query) use($userId){
            $query->where([['student_id' ,$userId], ['count', '>' , 0]]);
        })->get();

        return $this->apiResponse(200 , 'Done' , null, $newExam) ;

    }

    public function oldExams()
    {
        $userId = auth()->user()->id;
        $newExam = $this->examModel::where('close', 1)->whereHas('studentGroups' , function($query) use($userId){
            $query->where([['student_id' ,$userId], ['count', '>' , 0]]);
        })->get();

        return $this->apiResponse(200 , 'Done' , null, $newExam) ;
    }

    public function newStudentExam($request)
    {
        $validation = Validator::make($request->all(),[
           'exam_id'=>'required|exists:exams,id'
        ]);

        if($validation->fails()) {
            return $this->ApiResponse(422 , 'Validation Error', $validation->errors());
        }

        $questionCount = $this->examModel::select('count')->find($request->exam_id);

        $questions = $this->questionModel::where('exam_id' , $request->exam_id)
                                            ->inRandomOrder()
                                            ->limit($questionCount->count)
                                            ->with('questionImage')
                                            ->get();

        return $this->ApiResponse(200 , 'Done' , null , $questions);

    }

    public function storeStudentExam($request)
    {

        $examData = $this->examModel::whereHas('examType', function($query){
            $query->where('is_mark' , 1);
        })->select('type_id' ,'degree' , 'count')->find($request->exam_id);

        $setStudentExam = $this->studentExamModel::create([
            'exam_id' => $request->exam_id ,
            'student_id' => auth()->user()->id ,
            'total_degree' => 0,
        ]);

        if($examData){

            $questionDegree = $examData->degree / $examData->count;
            $totalDegree = 0;

            foreach ($request->questions as $question)
            {
                $getSystemAnswer = $this->systemAnswerModel::where('question_id' , $question['question'])->first();

                if($question['answer'] == $getSystemAnswer['answer']){
                    $degree = $questionDegree;
                    $totalDegree += $questionDegree;
                }else{
                    $degree = 0;
                }

                $this->studentExamAnswerModel::create([
                   'student_exam_id' => $setStudentExam->id ,
                   'question_id' => $question['question'] ,
                   'degree' => $degree ,
                ]);

                $setStudentExam->update([
                    'total_degree' => $totalDegree
                ]);

            } //end of foreach

            return $this->ApiResponse(200, 'Done' , null , $totalDegree);

        }else{
            foreach ($request->questions as $question)
            {


            }

        } //end else

    } //end of storeStudentExam

}
