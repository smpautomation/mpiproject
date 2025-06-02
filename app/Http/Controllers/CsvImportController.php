<?php

// app/Http/Controllers/CsvImportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\YourModel;
use League\Csv\Reader;

class CsvImportController extends Controller
{
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid file.'], 422);
        }

        $path = $request->file('file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // assumes first row is header

        foreach ($csv as $row) {
            YourModel::create([
                'column1' => $row['Column1'],
                'column2' => $row['Column2'],
                // ...
            ]);
        }

        return response()->json(['message' => 'CSV imported successfully.']);
    }
}
