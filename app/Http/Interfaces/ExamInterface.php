<?php

namespace App\Http\Interfaces;

interface ExamInterface{

    public function examTypes();

    public function creatExam($request);

    public function allExams();

    public function deleteExam($request);

    public function updateExam($request);

    public function updateExamStatus($request);

    /** Start Questions Sections */

    public function addQuestions($request);
    public function updateQuestions($request);
    public function deleteQuestions($request);
}
