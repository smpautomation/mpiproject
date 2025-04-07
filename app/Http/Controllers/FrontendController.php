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

    public function manage(Request $request) {
        // Capture the manageSerialParam query parameter from the URL
        $manageSerialParam = $request->query('manageSerialParam'); // Use $request->query() to get the value

        // Return the Inertia response and pass the manageSerialParam to the Manage.vue component
        return Inertia::render('Frontend/Manage', [
            'manageSerialParam' => $manageSerialParam,  // Pass manageSerialParam as a prop to the Vue component
        ]);
    }

    public function reports(Request $request){
        // Capture the serialParam from the GET request
        $serial = $request->get('serialParam');

        // Return the Inertia response and pass serialParam to the Reports.vue component
        return Inertia::render('Frontend/Reports', [
            'serialParam' => $serial,  // Pass serialParam as a prop to the Reports.vue component
        ]);
    }

    public function inspection(){
        return Inertia::render('Frontend/Inspection');
    }

    public function approval(){
        return Inertia::render('Frontend/Approval');
    }

    public function settings(){
        return Inertia::render('Frontend/Settings');
    }
}
