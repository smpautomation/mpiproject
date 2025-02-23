<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FrontendController extends Controller
{
    public function index(){
        return Inertia::render('Frontend/Home');
    }

    public function viewList(){
        return Inertia::render('Frontend/ViewList');
    }

    public function manage(){
        return Inertia::render('Frontend/Manage');
    }

    public function reports(){
        return Inertia::render('Frontend/Reports');
    }

    public function settings(){
        return Inertia::render('Frontend/Settings');
    }
}
