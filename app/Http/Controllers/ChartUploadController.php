<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChartUploadController extends Controller
{
    public function store(Request $request)
    {
        $base64 = $request->input('image');
        $filename = $request->input('filename', 'chart_' . time() . '.png');

        if (!$base64) {
            return response()->json(['error' => 'No image data'], 400);
        }

        $base64 = str_replace('data:image/png;base64,', '', $base64);
        $base64 = str_replace(' ', '+', $base64);

        $dir = public_path('charts');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $savePath = $dir . '/' . $filename;

        // Log what’s happening
        Log::info('Checking file existence:', [
            'savePath' => $savePath,
            'exists' => file_exists($savePath) ? 'yes' : 'no'
        ]);

        if (file_exists($savePath)) {
            Log::info('Skipped saving — file already exists');
            return response()->json([
                'message' => 'File already exists',
                'path' => "charts/{$filename}"
            ], 200);
        }

        file_put_contents($savePath, base64_decode($base64));
        Log::info('File saved successfully:', ['path' => $savePath]);

        return response()->json([
            'message' => 'File saved successfully',
            'path' => "charts/{$filename}"
        ]);
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

        // Check if file already exists
        if (file_exists($savePath)) {
            return response()->json(['message' => 'File already exists', 'path' => "sec_charts/{$filename}"], 200);
        }

        file_put_contents($savePath, base64_decode($base64));

        return response()->json(['path' => "sec_charts/{$filename}"]);
    }
}
