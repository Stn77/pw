<?php

namespace App\Http\Controllers\Data;

use App\Export\Excel;
use App\Http\Controllers\Controller;
// use ;
use Illuminate\Http\Request;

class Export extends Controller
{

    public $pdf, $excel;

    public function __construct()
    {
        $this->excel = Excel::class;
        
    }

    public function export()
    {

    }
}
