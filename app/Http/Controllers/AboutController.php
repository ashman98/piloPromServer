<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{

    public function index(Request $request)
    {
        $name = $request->query('name');
        $age = $request->query('age');

        $data  = ['message' => "Hello {$name}! Age {$age}"];

        return view('about', $data);
    }

    public function user($id)
    {
        $data  = ['message' => "Hello {$id}!"];

        return view('about', $data);
    }
}
