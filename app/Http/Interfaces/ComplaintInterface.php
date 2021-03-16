<?php

namespace App\Http\Interfaces;

interface ComplaintInterface{


    public function allComplaint();

    public function specificComplaint($request);

    public function deleteComplaint($request);

}
