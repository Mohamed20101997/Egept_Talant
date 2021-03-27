<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\StudentExamInterface;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    private $studentexamInterface;
    public function __construct(StudentExamInterface $studentexamInterface)
    {

        $this->studentexamInterface = $studentexamInterface;
    }

    public function newExams(){
         return $this->studentexamInterface->newExams();
    }

    public function oldExams(){
        return $this->studentexamInterface->oldExams();
    }


    public function newStudentExam(Request $request){
        return $this->studentexamInterface->newStudentExam($request);
    }

    public function storeStudentExam(Request $request){
        return $this->studentexamInterface->storeStudentExam($request);
    }

}
