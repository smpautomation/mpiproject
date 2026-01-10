<?php

namespace App\Http\Controllers;

use App\Models\HtGraphPatterns;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class HtGraphPatternsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(HtGraphPatterns::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pattern_no' => 'required|integer',
                'pattern_no_hours' => 'nullable|numeric',
                'furnace_no' => 'required|string|max:255',
                'encoded_by' => 'nullable|string|max:255',
            ]);

            $pattern = HtGraphPatterns::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pattern created successfully.',
                'data' => $pattern,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (QueryException $e) {
            // MySQL duplicate key error code = 1062
            if (($e->errorInfo[1] ?? null) == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => 'This pattern number already exists for this furnace.',
                ], 422);
            }

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HtGraphPatterns $htGraphPattern)
    {
        return response()->json($htGraphPattern, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pattern = HtGraphPatterns::findOrFail($id);

        $request->validate([
            'pattern_no' => 'required|integer',
            'pattern_no_hours' => 'nullable|numeric',
            'furnace_no' => 'required|string|max:50',
            'encoded_by' => 'required|string|max:100',
            'graph'      => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $oldPatternNo = $pattern->pattern_no;
        $newPatternNo = $request->input('pattern_no');
        $pattern->pattern_no = $newPatternNo;
        $pattern->furnace_no = $request->input('furnace_no');
        $pattern->pattern_no_hours = $request->input('pattern_no_hours');
        $pattern->encoded_by = $request->input('encoded_by');

        $uploadDir = public_path('htgraph_patterns');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // 1️⃣ Rename existing file if pattern_no changed and no new file uploaded
        if ($oldPatternNo != $newPatternNo && !$request->hasFile('graph')) {
            $existingFiles = glob("$uploadDir/pattern_{$oldPatternNo}.{png,jpg,jpeg}", GLOB_BRACE);
            if (count($existingFiles)) {
                $extension = pathinfo($existingFiles[0], PATHINFO_EXTENSION);
                $newFileName = "pattern_{$newPatternNo}.{$extension}";
                rename($existingFiles[0], "$uploadDir/$newFileName");
            }
        }

        // 2️⃣ Handle file replacement if new file uploaded
        if ($request->hasFile('graph')) {
            $file = $request->file('graph');

            // Delete old files for this pattern_no (any extension)
            foreach (glob("$uploadDir/pattern_{$newPatternNo}.*") as $oldFile) {
                @unlink($oldFile);
            }

            $extension = $file->getClientOriginalExtension();
            $filename = "pattern_{$newPatternNo}.{$extension}";
            $file->move($uploadDir, $filename);
        }

        $pattern->save();

        return response()->json([
            'success' => true,
            'message' => 'Pattern updated successfully',
            'pattern' => $pattern,
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HtGraphPatterns $htGraphPattern)
    {
        $htGraphPattern->delete();

        return response()->json([
            'message' => 'Pattern deleted successfully.'
        ], 200);
    }

    public function uploadGraphPattern(Request $request)
    {
        // Validate inputs
        $request->validate([
            'pattern_no' => 'required|integer',
            'furnace_no' => 'required|string|max:255',
            'graph' => 'required|image|mimes:jpeg,jpg,png|max:2048', // max 2MB
        ]);

        $file = $request->file('graph');
        $patternNo = $request->input('pattern_no');
        $furnaceNo = $request->input('furnace_no');

        // Ensure public folder exists
        $uploadDir = public_path('htgraph_patterns');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Extract extension and build filename based on pattern_no
        $extension = $file->getClientOriginalExtension(); // png, jpg, jpeg
        // Safe filename
        $safeFurnace = preg_replace('/[^A-Za-z0-9_\-]/', '_', $furnaceNo);

        $filename = "pattern_{$patternNo}_{$safeFurnace}.{$extension}";

        // Move file directly to public folder
        $file->move($uploadDir, $filename);

        // Return public URL
        return response()->json([
            'message' => 'Graph uploaded successfully.',
            'path' => "htgraph_patterns/{$filename}",
            'url' => asset("htgraph_patterns/{$filename}"),
        ], 201);
    }

    public function listGraphs()
    {
        $patterns = HtGraphPatterns::all()->map(function($pattern) {
            $folder = public_path('htgraph_patterns');

            // Scan for files starting with pattern_<pattern_no>
            $files = glob("$folder/pattern_{$pattern->pattern_no}_{$pattern->furnace_no}.{png,jpg,jpeg}", GLOB_BRACE); // matches png, jpg, jpeg
            $url = count($files)
                ? asset('htgraph_patterns/' . basename($files[0])) . '?t=' . time()
                : null;

            return [
                'id' => $pattern->id,
                'pattern_no' => $pattern->pattern_no,
                'pattern_no_hours' => $pattern->pattern_no_hours,
                'furnace_no' => $pattern->furnace_no,
                'encoded_by' => $pattern->encoded_by,
                'url' => $url,
            ];
        })->filter(fn($item) => $item['url'] !== null);

        return response()->json($patterns);
    }

    public function getHours($patternNo)
    {
        // Validate the parameter manually
        if (!is_numeric($patternNo)) {
            return response()->json(['error' => 'Pattern number must be numeric.'], 422);
        }

        // Fetch the record
        $pattern = HtGraphPatterns::where('pattern_no', $patternNo)->first();

        if (!$pattern) {
            return response()->json(['error' => 'Pattern not found.'], 404);
        }

        // Return the hours
        return response()->json([
            'pattern_no' => $pattern->pattern_no,
            'pattern_no_hours' => $pattern->pattern_no_hours,
        ]);
    }

    public function getAssociatedPattern(Request $request)
    {
        $request->validate([
            'furnace_no' => 'required|string',
        ]);

        $patterns = HtGraphPatterns::where('furnace_no', $request->furnace_no)
            ->orderBy('pattern_no')
            ->pluck('pattern_no');

        return response()->json([
            'patterns' => $patterns,
        ]);
    }

}
