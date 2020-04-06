<?php

namespace Formbuilder\Http\Controllers;

use App\Http\Controllers\Controller;

class FormsController extends Controller
{

    public function index()
    {
        return view('formbuilder::test');
    }
}
