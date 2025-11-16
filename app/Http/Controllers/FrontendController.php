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

    public function massprod(){
        return Inertia::render('Frontend/Mass_Prod');
    }

    public function controlSheet(Request $request){

        $massProd = $request->get('massProd');
        $furnace = $request->get('furnace');

        return Inertia::render('Frontend/Control_Sheet',[
            'massProd' => $massProd,
            'furnace' => $furnace
        ]);
    }

    public function htgraph(Request $request){

        $massProd = $request->get('massProd');
        $furnace = $request->get('furnace');

        return Inertia::render('Frontend/HT_Graph',[
            'massProd' => $massProd,
            'furnace' => $furnace
        ]);
    }

    public function smpData(Request $request){

        $massProd = $request->get('massProd');
        $furnace = $request->get('furnace');

        return Inertia::render('Frontend/SMP_Data',[
            'massProd' => $massProd,
            'furnace' => $furnace
        ]);
    }

    public function massProdMonitoring(Request $request){
        return Inertia::render('Frontend/Mass_Prod_Monitoring');
    }

    public function heatTreatment(Request $request){
        return Inertia::render('Frontend/Heat_Treatment');
    }

    public function breakLots(Request $request){

        $massprod = $request->get('massprod');
        $furnace = $request->get('furnace');

        return Inertia::render('Frontend/HT_BreakLots',[
            'massprod' => $massprod,
            'furnace' => $furnace
        ]);
    }

    public function viewList(){
        return Inertia::render('Frontend/ViewList');
    }

    public function coating(){
        return Inertia::render('Frontend/Coating');
    }

    public function emailForm(){
        return Inertia::render('Frontend/Email');
    }

    public function emailFormManual(){
        return Inertia::render('Frontend/Email_Manual');
    }

    public function furnace(){
        return Inertia::render('Frontend/Furnace');
    }

    public function filmPasting(){
        return Inertia::render('Frontend/Film_Pasting');
    }

    public function heatTreatmentGraphPatterns(){
        return Inertia::render('Frontend/HTGraph_Patterns');
    }

    public function second_gbdp_models(){
        return Inertia::render('Frontend/Second_GBDP_Models');
    }

    public function viewSecondGbdp(){
        return Inertia::render('Frontend/View_Second_GBDP');
    }

    public function second_heat_treatment(Request $request){

        $furnace = $request->get('furnace');
        $massProd = $request->get('massProd');
        $layer = $request->get('layer');

        return Inertia::render('Frontend/Second_Heat_Treatment',[
            'furnace' => $furnace,
            'massProd' => $massProd,
            'layer' => $layer,
        ]);
    }

    public function manage(Request $request) {
        // Capture the manageSerialParam query parameter from the URL
        $manageSerialParam = $request->query('manageSerialParam'); // Use $request->query() to get the value
        $manageMassProd = $request->query('manageMassProd');
        $manageLayer = $request->query('manageLayer');

        // Return the Inertia response and pass the manageSerialParam to the Manage.vue component
        return Inertia::render('Frontend/Manage', [
            'manageSerialParam' => $manageSerialParam,  // Pass manageSerialParam as a prop to the Vue component
            'manageMassProd' => $manageMassProd,
            'manageLayer' => $manageLayer
        ]);
    }

    public function sec_additional(Request $request) {
        $serial = $request->input('sec_serialParam');
        $massProd = $request->input('sec_massProd');
        $layer = $request->input('sec_layer');

        return Inertia::render('Frontend/SEC_Additional', [
            'sec_serialParam' => $serial,
            'sec_massProd' => $massProd,
            'sec_layer' => $layer
        ]);
    }

    public function reports(Request $request)
    {
        // Capture the serialParam from the GET request
        $serial = $request->get('serialParam');
        $filterStatus = $request->get('filterStatus');
        $filterStatus_checked = $request->get('filterStatus_checked');
        $fromApproval = $request->get('fromApproval');
        $fromApproval_checked = $request->get('fromApproval_checked');
        $fromApproval_prepared = $request->get('fromApproval_prepared');
        $fromViewList = $request->get('fromViewList');

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
            'filterStatus' => $filterStatus,
            'filterStatus_checked' => $filterStatus_checked,
            'ipAddress' => $ipAddress,  // Pass ipAddress as a prop to the Reports.vue component
            'fromApproval' => filter_var($request->fromApproval, FILTER_VALIDATE_BOOLEAN),
            'fromApproval_checked' => filter_var($request->fromApproval_checked, FILTER_VALIDATE_BOOLEAN),
            'fromApproval_prepared' => filter_var($request->fromApproval_prepared, FILTER_VALIDATE_BOOLEAN),
            'fromViewList' => filter_var($request->fromViewList, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function inspection(){
        return Inertia::render('Frontend/Inspection');
    }

    public function approval(Request $request){

        $filterStatus = $request->get('filterStatus');
        $fromReports = $request->get('fromReports');

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
            'ipAddress' => $ipAddress,  // Now passed to Approval.vue
            'filterStatus' => $filterStatus,
            'fromReports' => filter_var($request->fromReports, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function approval_checked(Request $request){
        return Inertia::render('Frontend/Approval_Checked');
    }

    public function approval_prepared(Request $request){
        return Inertia::render('Frontend/Approval_Prepared');
    }

    public function admin(){
        return Inertia::render('Frontend/Admin');
    }

    public function mias_factor(){
        return Inertia::render('Frontend/Mias_factor');
    }

    public function instructions(){
        return Inertia::render('Frontend/Instructions');
    }

    public function data_ins(){
        return Inertia::render('Frontend/Data_INS');
    }

    public function generatePdf(Request $request){
        // Capture the serialParam from the GET request
        $serial = $request->get('serialParam');
        // Return the Inertia response and pass both serialParam and ipAddress to the PreviewPdf.vue component
        return Inertia::render('Frontend/PreviewPdf', [
            'serialParam' => $serial,  // Pass serialParam as a prop to the PreviewPdf.vue component
        ]);
    }
}
