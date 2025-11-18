<?php
namespace App\Http\Controllers;

use App\Models\ErrorLogs;
use Illuminate\Http\Request;

class ErrorLogsController extends Controller
{
    public function index()
    {
        return response()->json(ErrorLogs::latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user' => 'nullable|string',
            'title' => 'nullable|string',
            'details' => 'nullable|array',
            'trigger_function' => 'nullable|string',
            'section' => 'nullable|string',
        ]);

        $log = ErrorLogs::create($data);

        return response()->json($log, 201);
    }

    public function show(ErrorLogs $errorLog)
    {
        return response()->json($errorLog);
    }

    public function update(Request $request, ErrorLogs $errorLog)
    {
        $data = $request->validate([
            'user' => 'nullable|string',
            'title' => 'nullable|string',
            'details' => 'nullable|array',
            'trigger_function' => 'nullable|string',
            'section' => 'nullable|string',
        ]);

        $errorLog->update($data);

        return response()->json($errorLog);
    }

    public function destroy(ErrorLogs $errorLog)
    {
        $errorLog->delete();

        return response()->json(null, 204);
    }
}
