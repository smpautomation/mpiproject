<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartUploadController extends Controller
{
    public function store(Request $request)
    {
        $base64 = $request->input('image');
        $filename = $request->input('filename', 'chart_' . time() . '.png');

        if (!$base64) {
            return response()->json(['error' => 'No image data'], 400);
        }

        // Strip base64 header
        $base64 = str_replace('data:image/png;base64,', '', $base64);
        $base64 = str_replace(' ', '+', $base64);

        // Ensure charts directory exists
        $dir = public_path('charts');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Save file
        $savePath = $dir . '/' . $filename;
        file_put_contents($savePath, base64_decode($base64));

        return response()->json(['path' => "charts/{$filename}"]);
    }

    public function storeSecondary(Request $request)
    {
        $base64 = $request->input('image');
        $filename = $request->input('filename', 'sec_chart_' . time() . '.png');

        if (!$base64) {
            return response()->json(['error' => 'No image data'], 400);
        }

        $base64 = str_replace('data:image/png;base64,', '', $base64);
        $base64 = str_replace(' ', '+', $base64);

        $dir = public_path('sec_charts');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $savePath = $dir . '/' . $filename;
        file_put_contents($savePath, base64_decode($base64));

        return response()->json(['path' => "sec_charts/{$filename}"]);
    }
}
