<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\ExamInterface;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private $examInterface;
    public function __construct(ExamInterface $examInterface)
    {

        $this->examInterface = $examInterface;
    }

    public function examTypes(){
         return $this->examInterface->examTypes();
    }

    public function creatExam(Request $request){
        return $this->examInterface->creatExam($request);
    }


    public function allExams(){
        return $this->examInterface->allExams();
    }

    public function deleteExam(Request $request){
        return $this->examInterface->deleteExam($request);
    }

    public function updateExam(Request $request){
        return $this->examInterface->updateExam($request);
    }

    public function updateExamStatus(Request $request){
        return $this->examInterface->updateExamStatus($request);
    }

    public function addQuestions(Request $request)
    {
        return $this->examInterface->addQuestions($request);
    }

    public function updateQuestions(Request $request)
    {
        return $this->examInterface->updateQuestions($request);
    }

    public function deleteQuestions(Request $request)
    {
        return $this->examInterface->deleteQuestions($request);
    }
}
