<?php

namespace App\Http\Interfaces;

interface StudentInterface{


    public function addStudent($request);

    public function allStudent();

    public function specificStudent($request);

    public function updateStudent($request);

    public function deleteStudent($request);

    public function deleteStudentGroup($request);

    public function updateStudentGroup($request);

}
