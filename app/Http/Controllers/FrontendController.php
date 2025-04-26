<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function reports(Request $request)
    {
        // Capture the serialParam from the GET request
        $serial = $request->get('serialParam');

        // Debug: Log all the headers
        Log::debug($request->headers->all());  // <-- Now the Log class will work

        // Get the real client IP address
        $xForwardedFor = $request->header('X-Forwarded-For');
        if ($xForwardedFor) {
            // The first IP address in the X-Forwarded-For list is the real client IP
            $ipAddress = explode(',', $xForwardedFor)[0];
        } else {
            // Fallback to REMOTE_ADDR if X-Forwarded-For is not present
            $ipAddress = $request->server('REMOTE_ADDR');
        }

        Log::debug('Client IP Address: ' . $ipAddress);  // Log the IP Address

        // Return the Inertia response and pass both serialParam and ipAddress to the Reports.vue component
        return Inertia::render('Frontend/Reports', [
            'serialParam' => $serial,  // Pass serialParam as a prop to the Reports.vue component
            'ipAddress' => $ipAddress  // Pass ipAddress as a prop to the Reports.vue component
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

    public function data_ins(){
        return Inertia::render('Frontend/Data_INS');
    }
}
