<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeePageController extends Controller
{
    public function index()
    {
        return view('employees.index');
    }
}

