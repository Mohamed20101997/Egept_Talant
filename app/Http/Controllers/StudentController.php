<?php

namespace App\Http\Controllers;
use App\Http\Interfaces\StudentInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private $studentInterface;
    public function __construct(StudentInterface $studentInterface)
    {
        $this->studentInterface = $studentInterface;
    }

    public function addStudent(Request $request)
    {
        return $this->studentInterface->addStudent( $request);
    }

    public function allStudent(Request $request)
    {
        return $this->studentInterface->allStudent( $request);
    }

    public function specificStudent(Request $request)
    {
        return $this->studentInterface->specificStudent( $request);
    }


    public function updateStudent(Request $request)
    {
        return $this->studentInterface->updateStudent( $request);
    }


    public function deleteStudent(Request $request)
    {
        return $this->studentInterface->deleteStudent($request);
    }

    public function deleteStudentGroup(Request $request)
    {
        return $this->studentInterface->deleteStudentGroup($request);
    }

    public function updateStudentGroup(Request $request)
    {
        return $this->studentInterface->updateStudentGroup($request);
    }

}
