<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use App\Models\ReportData;
use App\Models\Coating;
use App\Models\HeatTreatment;
use App\Models\TPMDataCategory;
use App\Models\TPMData;
use App\Models\TPMDataAggregateFunctions;
use App\Models\VtModel;
use App\Models\CpkIhcModel;
use App\Models\GxModel;
use App\Models\TtmncModel;
use App\Models\BhModel;
use App\Models\RobModel;

class BackEndPdfController extends Controller
{

    public function apiGenerateAndSave($serial)
    {
        $this->generateAndMerge($serial); // already handles validation + save
        return response()->json(['message' => 'PDF saved successfully']);
    }

    public function generateAndMerge($serial)
    {
        $chartFilename = "chart_{$serial}.png";
        $chartPath = public_path("charts/{$chartFilename}");

        // Optional: check if image exists
        if (!file_exists($chartPath)) {
            // Fallback or skip image — up to you
            $chartFilename = null;
        }

        $reportData = ReportData::where('tpm_data_serial', $serial)->first();
        if (!$reportData) {
            return response()->json([
                'status' => false,
                'message' => "Report Data with serial: {$serial} not found",
            ], 404);
        }
        // Prepare data for the PDF views
        $magneticProperty = json_decode($reportData->magnetic_property_data, true);
        $brBounds = ['lower' => null, 'upper' => null];
        if (!empty($magneticProperty['brStandard']) && str_contains($magneticProperty['brStandard'], '~')) {
            [$lower, $upper] = explode('~', $magneticProperty['brStandard']);
            $brBounds['lower'] = trim($lower);
            $brBounds['upper'] = trim($upper);
        }
        // Calculate BR variance (if values exist)
        $brVariance = null;
        $ihcVariance = null;
        $ihkVariance = null;
        if (
            isset($magneticProperty['brMaximum'], $magneticProperty['brMinimum'], $magneticProperty['ihcMaximum'], $magneticProperty['ihcMinimum'], $magneticProperty['ihkMaximum'], $magneticProperty['ihkMinimum'])
        ) {
            $brVariance = $magneticProperty['brMaximum'] - $magneticProperty['brMinimum'];
            $ihcVariance = $magneticProperty['ihcMaximum'] - $magneticProperty['ihcMinimum'];
            $ihkVariance = $magneticProperty['ihkMaximum'] - $magneticProperty['ihkMinimum'];
        }

        $coatingData = Coating::where('serial', $serial)->first();
        if(!$coatingData) {
            return response()->json([
                'status' => false,
                'message' => "Coating Data with serial: {$serial} not found",
            ], 404);
        }

        $HTData = HeatTreatment::where('serial', $serial)->first();
        if (!$HTData) {
            return response()->json([
                'status' => false,
                'message' => "Heat Treatment Data with serial: {$serial} not found",
            ], 404);
        }

        $MBL = json_decode($HTData->magnet_box_location, true);
        if (!$MBL) {
            return response()->json([
                'status' => false,
                'message' => "Magnet Box Location for serial: {$serial} is invalid or missing",
            ], 404);
        }

        $preparedByDate = null;
        $checkedByDate = null;
        $approvedByDate = null;

        if(!empty($reportData->created_at)){
            $reportDate = \Carbon\Carbon::parse($reportData->created_at)->format('Y-m-d');
        }

        if (!empty($reportData->prepared_by_date)) {
            $preparedByDate = \Carbon\Carbon::parse($reportData->prepared_by_date)->format('Y-m-d');
        }

        if (!empty($reportData->checked_by_date)) {
            $checkedByDate = \Carbon\Carbon::parse($reportData->checked_by_date)->format('Y-m-d');
        }

        if (!empty($reportData->approved_by_date)) {
            $approvedByDate = \Carbon\Carbon::parse($reportData->approved_by_date)->format('Y-m-d');
        }

        $preparedFirstFontSize = $this->resolveFontSize($reportData->prepared_by_firstname ?? '');
        $preparedLastFontSize  = $this->resolveFontSize($reportData->prepared_by_surname ?? '');
        $checkedFirstFontSize = $this->resolveFontSize($reportData->checked_by_firstname ?? '');
        $checkedLastFontSize  = $this->resolveFontSize($reportData->checked_by_surname ?? '');
        $approvedFirstFontSize = $this->resolveFontSize($reportData->approved_by_firstname ?? '');
        $approvedLastFontSize  = $this->resolveFontSize($reportData->approved_by_surname ?? '');

        // === SORTED NOTE REASON LOGIC ===
        $noteReasonRaw = json_decode($reportData->note_reason_reject ?? '[]', true);

        // If it's just a plain string (like a line of text), fallback to array
        if (!is_array($noteReasonRaw)) {
            $noteReasonRaw = explode("\n", $reportData->note_reason_reject);
            $noteReasonRaw = array_filter(array_map('trim', $noteReasonRaw));
        }

        // Define custom priority order (lower number = higher priority)
        $priorityOrder = [
            "- N.G Br-4PIa" => 1,
            "- N.G iHc"     => 2,
            "- N.G Hr95"    => 3,
            "- N.G Hr98"    => 4,
            // fallback/default is 99
        ];

        // Sort using priority
        usort($noteReasonRaw, function ($a, $b) use ($priorityOrder) {
            $aPriority = $priorityOrder[$a] ?? 99;
            $bPriority = $priorityOrder[$b] ?? 99;
            return $aPriority - $bPriority;
        });

        $coatingDetails = json_decode($coatingData->coating_data, true);
        if (!$coatingDetails) {
            return response()->json([
                'status' => false,
                'message' => "Coating Data for serial: {$serial} is invalid or missing",
            ], 404);
        }

        $tpmCategories = TPMDataCategory::where('tpm_data_serial', $serial)->first();
        $tpmData = TPMData::where('serial_no', $serial)->first();
        if (!$tpmCategories || !$tpmData) {
            return response()->json([
                'status' => false,
                'message' => "TPM Data for serial: {$serial} is invalid or missing",
            ], 404);
        }
        $tpmDataAll = TPMData::with('remark')->where('serial_no', $serial)->get();
        if ($tpmDataAll->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => "TPM Data for serial: {$serial} is invalid or missing",
            ], 404);
        }

        $onlyFurnacePrefix = explode('-', $tpmData->sintering_furnace_no)[0] ?? null;
        $onlyFurnacePostfix = explode('-', $tpmData->sintering_furnace_no)[1] ?? null;

        $tpmDataAggregate = TPMDataAggregateFunctions::where('tpm_data_serial', $serial)->first();
        if (!$tpmDataAggregate) {
            return response()->json([
                'status' => false,
                'message' => "TPM Aggregate Data for serial: {$serial} is invalid or missing",
            ], 404);
        }
        $aggregateMaximum = json_decode($tpmDataAggregate->maximum, true); // now an associative array
        $aggregateMinimum = json_decode($tpmDataAggregate->minimum, true); // same
        $aggregateAverage = json_decode($tpmDataAggregate->average, true); // same

        $noteReasonRaw = json_decode($reportData->note_reason_reject ?? '[]', true);
        //dd($noteReasonRaw);
        // fallback if it’s malformed (just a string with newlines instead of valid JSON)
        if (!is_array($noteReasonRaw)) {
            $noteReasonRaw = explode("\n", $reportData->note_reason_reject);
            $noteReasonRaw = array_filter(array_map('trim', $noteReasonRaw));
        }

        $d1x1x1     = json_decode($reportData->data_1x1x1_info ?? '[]', true);
        $dVT        = json_decode($reportData->data_VT_info ?? '[]', true);
        $diHc_cpk   = json_decode($reportData->data_iHc_cpk_info ?? '[]', true);
        $dGX        = json_decode($reportData->data_GX_info ?? '[]', true);
        $dBH        = json_decode($reportData->data_bh_info ?? '[]', true);
        $dROB       = json_decode($reportData->data_ROB_info ?? '[]', true);

        $model = $tpmCategories->actual_model ?? '';
        $noteReasons = $noteReasonRaw;

        $MODELS_SHOW_VT_DATA = VtModel::pluck('model_name')->toArray();
        $MODELS_SHOW_CPK     = CpkIhcModel::pluck('model_name')->toArray();
        $MODELS_SHOW_GX      = GxModel::pluck('model_name')->toArray();
        $MODELS_1X1X1_NO_CORNER = TtmncModel::pluck('model_name')->toArray();
        $MODELS_SHOW_BH      = BhModel::pluck('model_name')->toArray();
        $MODELS_SHOW_ROB     = RobModel::pluck('model_name')->toArray();

        $showROB         = in_array($model, $MODELS_SHOW_ROB);
        $hasNGihc        = in_array('- N.G iHc', $noteReasons);

        $showCpkFrom_iHc = $hasNGihc && in_array($model, $MODELS_SHOW_CPK);
        $showGX          = $hasNGihc && in_array($model, $MODELS_SHOW_GX);
        $showBHData      = $hasNGihc && in_array($model, $MODELS_SHOW_BH);

        $showVTData         = false; // default to false
        $showVTData_default = false; // default to false

        // VT Logic
        if ($hasNGihc && in_array($model, $MODELS_SHOW_VT_DATA)) {
            if (!empty($dVT['sample_qty']) && $dVT['sample_qty'] > 0) {
                $showVTData = true;
            } else {
                $showVTData_default = true;
            }
        }

        // 1x1x1 Data Conditions
        $show1x1x1Data_withoutCorner = false;
        $show1x1x1Data_Corner        = false;
        $isTTM_model                 = str_contains($model, 'TTM');

        if ($isTTM_model && $hasNGihc) {
            $show1x1x1Data_withoutCorner = true;
            $show1x1x1Data_Corner = true;

            if (in_array($model, $MODELS_1X1X1_NO_CORNER)) {
                $show1x1x1Data_Corner = false;
            }
        }
        //dd($show1x1x1Data_Corner);


        $data = [
            'serial' => $serial,
            'chartFilename' => $chartFilename, // pass to Blade
            'reportData' => $reportData, // pass report data to Blade
            'magneticProperty' => $magneticProperty,
            'brBounds' => $brBounds,
            'brVariance' => $brVariance,
            'ihcVariance' => $ihcVariance,
            'ihkVariance' => $ihkVariance,
            'coatingData' => $coatingData, // pass coating data to Blade
            'heatTreatmentData' => $HTData, // pass heat treatment data to Blade
            'preparedByDate' => $preparedByDate, // pass prepared by
            'checkedByDate' => $checkedByDate, // pass checked by date
            'approvedByDate' => $approvedByDate, // pass approved by date
            'preparedFirstFontSize' => $preparedFirstFontSize, // pass font size for first name
            'preparedLastFontSize' => $preparedLastFontSize, // pass font size for
            'checkedFirstFontSize' => $checkedFirstFontSize, // pass font size for checked first name
            'checkedLastFontSize' => $checkedLastFontSize, // pass font size for
            'approvedFirstFontSize' => $approvedFirstFontSize, // pass font size for approved first name
            'approvedLastFontSize' => $approvedLastFontSize, // pass font size for
            'noteReasonsSorted' => $noteReasonRaw, // pass sorted note reasons
            'coatingDetails' => $coatingDetails, // pass coating details
            'tpmCat' => $tpmCategories, // pass TPM categories
            'tpmData' => $tpmData, // pass single TPM data
            'tpmDataAll' => $tpmDataAll, // pass all TPM data
            'magnetBoxLocation' => $MBL, // pass magnet box location
            'reportDate' => $reportDate ?? null, // pass report date
            'sinteringFurnaceNo' => $onlyFurnacePrefix, // pass only furnace prefix
            'sinteringNo' => $onlyFurnacePostfix, // pass only furnace postfix
            'tpmAggregateMax' => $aggregateMaximum, // pass TPM aggregate maximum
            'tpmAggregateMin' => $aggregateMinimum, // pass TPM aggregate minimum
            'tpmAggregateAvg' => $aggregateAverage, // pass TPM aggregate average
            'flags' => [
                'showROB' => $showROB,
                'showVTData' => $showVTData,
                'showVTData_default' => $showVTData_default,
                'showCpkFrom_iHc' => $showCpkFrom_iHc,
                'showGX' => $showGX,
                'showBHData' => $showBHData,
                'show1x1x1Data_withoutCorner' => $show1x1x1Data_withoutCorner,
                'show1x1x1Data_Corner' => $show1x1x1Data_Corner,
                'isTTM_model' => $isTTM_model,
            ],
            'modelData' => [
                'gx' => $dGX,
                'vt' => $dVT,
                'ihc_cpk' => $diHc_cpk,
                'bh' => $dBH,
                'rob' => $dROB,
                'd1x1x1' => $d1x1x1,
            ],
        ];

        $portrait = Pdf::loadView('pdf.report_page1_portrait', $data)
            ->setPaper('a4', 'portrait')
            ->output();

        $landscape = Pdf::loadView('pdf.report_page2_landscape', $data)
            ->setPaper('a4', 'landscape')
            ->output();

        $merger = new Merger();
        $merger->addRaw($portrait);
        $merger->addRaw($landscape);
        $mergedPdf = $merger->merge();

        $massProd = $tpmCategories->massprod_name ?? 'unknown';
        $rawFilename = "({$reportData->smp_judgement}) {$tpmCategories->actual_model} Lot No {$tpmCategories->jhcurve_lotno}";

        $savedPath = $this->saveMergedPdf($massProd, $rawFilename, $mergedPdf);

        return response($mergedPdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="report.pdf"');
    }


    private function resolveFontSize(string $name): string
    {
        if (empty(trim($name))) return '14px';

        $length = strlen(trim($name));

        return match (true) {
            $length <= 4  => '17px',
            $length === 5 => '16px',
            $length === 6 => '15px',
            $length === 7 => '14px',
            $length === 8 => '13px',
            $length === 9 => '12px',
            $length === 10 => '11px',
            $length === 11 => '10px',
            $length === 12 => '9px',
            $length === 13 => '8px',
            default       => '9px',
        };
    }

    private function saveMergedPdf(string $massProd, string $rawFilename, string $pdfContent): string
    {
        $massProd = preg_replace('/[^A-Za-z0-9\-\s#]/', '_', $massProd);
        $safeBaseName = preg_replace('/[^A-Za-z0-9\-\s\(\)#]/', '_', $rawFilename);
        $safeBaseName = preg_replace('/[\/\s]+/', '_', $safeBaseName);

        $fileName = $safeBaseName . '.pdf';
        $destinationPath = public_path("files/{$massProd}");

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        file_put_contents("{$destinationPath}/{$fileName}", $pdfContent);

        return "files/{$massProd}/{$fileName}";
    }
}
