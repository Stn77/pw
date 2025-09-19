<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestAlgorithm extends Controller
{
    public function index()
    {
        dd($this->getTeacher());
    }

    function getTeacher()
    {
        return User::role('teacher')->get();
    }
}
