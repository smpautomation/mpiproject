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

        $base64 = str_replace('data:image/png;base64,', '', $base64);
        $base64 = str_replace(' ', '+', $base64);

        $savePath = public_path('charts/' . $filename);
        file_put_contents($savePath, base64_decode($base64));

        return response()->json(['path' => "charts/{$filename}"]);
    }
}
