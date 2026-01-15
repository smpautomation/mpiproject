<?php

namespace App\Http\Controllers;

use App\Models\HtMassProData;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;

class HtMassProDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HtMassProData $htMassProData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HtMassProData $htMassProData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HtMassProData $htMassProData)
    {
        //
    }

    public function upload(Request $request)
    {
        \Log::info('Upload attempt', [
            'all_request'       => $request->all(),
            'file_present'      => $request->hasFile('file'),
            'file_class'        => $request->file('file') ? get_class($request->file('file')) : null,
            'file_original_name'=> optional($request->file('file'))->getClientOriginalName(),
            'file_mime'         => optional($request->file('file'))->getMimeType(),
            'file_extension'    => optional($request->file('file'))->getClientOriginalExtension(),
        ]);

        // 1. Validate
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');

        try {
            // 2. Load workbook (no password needed)
            $reader = new Xlsx();
            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly(['Sheet1']);
            $spreadsheet = $reader->load($file->getPathname());

            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // 3. Generate upload_code
            $uploadCode = $this->generateUploadCode();

            // 4. Insert rows
            foreach ($rows as $row) {
                if (empty(array_filter($row))) continue; // skip empty rows

                $rawModelName = ($row[0] ?? null) === '#N/A' ? null : $row[0];
                $modelName = $rawModelName ? str_replace('-', '', $rawModelName) : null;

                HtMassProData::create([
                    'upload_code'    => $uploadCode,
                    'model_name'     => $modelName,                      // sanitized
                    'product_weight' => ($row[2] ?? 0) === '#N/A' ? 0 : $row[2], // Column C
                    '2nd_sl'         => ($row[3] ?? 0) === '#N/A' ? 0 : $row[3], // Column D
                    'qty'            => ($row[9] ?? 0) === '#N/A' ? 0 : $row[9], // Column J
                ]);
            }


            return response()->json([
                'message'     => 'Excel uploaded successfully',
                'upload_code' => $uploadCode,
            ]);

        } catch (SpreadsheetException $e) {
            \Log::error('Spreadsheet read error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to read Excel: ' . $e->getMessage()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Excel upload failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateUploadCode()
    {
        $currentYm = now()->format('Ym');

        $latest = HtMassProData::where('upload_code', 'like', $currentYm . '%')
            ->orderBy('upload_code', 'desc')
            ->value('upload_code');

        $letter = !$latest ? 'A' : chr(ord(substr($latest, -1)) + 1);

        return $currentYm . '-' . $letter;
    }

    public function checkLatestUpload(Request $request)
    {
        // Fetch the latest HtMassProData entry
        $latest = HtMassProData::orderBy('created_at', 'desc')->first();

        if (!$latest) {
            return response()->json([
                'message' => 'No uploads found.',
                'data' => null,
            ]);
        }

        return response()->json([
            'message' => 'Latest upload found.',
            'data' => [
                'upload_date' => $latest->created_at->toDateTimeString(),
                'upload_code' => $latest->upload_code,
            ],
        ]);
    }
}
