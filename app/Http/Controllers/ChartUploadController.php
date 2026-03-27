<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ChartUploadController extends Controller
{
    public function store(Request $request)
    {
        try {
            $base64 = $request->input('image');
            $filename = $request->input('filename', 'chart_' . time() . '.jpg'); // default to .jpg now

            if (!$base64) {
                return response()->json(['error' => 'No image data'], 400);
            }

            // Remove any data URI prefix (works for PNG or JPEG)
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);

            // Decode base64 safely
            $data = base64_decode($base64, true);
            if ($data === false) {
                return response()->json(['error' => 'Invalid base64 data'], 400);
            }

            // Ensure directory exists
            $dir = public_path('charts');
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $savePath = $dir . '/' . $filename;

            if (file_exists($savePath)) {
                return response()->json([
                    'message' => 'File already exists',
                    'path' => "charts/{$filename}"
                ], 200);
            }

            // Save file
            file_put_contents($savePath, $data);

            return response()->json([
                'message' => 'File saved successfully',
                'path' => "charts/{$filename}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
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
