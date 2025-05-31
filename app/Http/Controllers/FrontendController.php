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

    public function emailForm(){
        return Inertia::render('Frontend/Email');
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
        $fromApproval = $request->get('fromApproval');

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
            'ipAddress' => $ipAddress,  // Pass ipAddress as a prop to the Reports.vue component
            'fromApproval' => filter_var($request->fromApproval, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function inspection(){
        return Inertia::render('Frontend/Inspection');
    }

    public function approval(Request $request){
        // Get the real client IP address
        $xForwardedFor = $request->header('X-Forwarded-For');
        if ($xForwardedFor) {
            // The first IP address in the X-Forwarded-For list is the real client IP
            $ipAddress = explode(',', $xForwardedFor)[0];
        } else {
            // Fallback to REMOTE_ADDR if X-Forwarded-For is not present
            $ipAddress = $request->server('REMOTE_ADDR');
        }

        // Optionally log it
        Log::debug('Client IP Address (Approval): ' . $ipAddress);

        return Inertia::render('Frontend/Approval', [
            'ipAddress' => $ipAddress  // Now passed to Approval.vue
        ]);
    }

    public function settings(){
        return Inertia::render('Frontend/Settings');
    }

    public function data_ins(){
        return Inertia::render('Frontend/Data_INS');
    }

    public function sec_additional(Request $request) {
        $serial = $request->input('sec_serialParam');

        return Inertia::render('Frontend/SEC_Additional', [
            'sec_serialParam' => $serial
        ]);
    }

    public function generatePdf(Request $request){
        // Capture the serialParam from the GET request
        $serial = $request->get('serialParam');
        // Return the Inertia response and pass both serialParam and ipAddress to the Reports.vue component
        return Inertia::render('Frontend/PreviewPdf', [
            'serialParam' => $serial,  // Pass serialParam as a prop to the Reports.vue component
        ]);
    }
}
