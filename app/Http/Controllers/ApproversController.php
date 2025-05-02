<?php

namespace App\Http\Controllers;

use App\Models\Approvers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApproversController extends Controller
{
    public function index(Request $request)
    {
        $approver = $request->query("approver_name");
        if($approver){
            try{
                $approverData = Approvers::where('approver_name',  $approver)->get();

                if(!$approverData->isEmpty()){

                    return response()->json([
                        'status' => true,
                        'message' => 'Approver found successfully',
                        'data' => $approverData
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Approver found.'
                    ], 404);
                }
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while retrieving approver name',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }else{
            try{
                $approverData = Approvers::all();
                return response()->json([
                    'status' => true,
                    'message' => 'Approvers retrieved successfully',
                    'data' => [
                        'Approvers' => $approverData
                    ]
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving approvers',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $approverData = Approvers::create([
                'approver_name' => $request->input('approver_name'),
                'ip_address' => $request->input('ip_address'),
                'approval_stage' => $request->input('approval_stage')
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Approver created successfully',
                'data' => $approverData
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating Approver',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $approverData = Approvers::find($id);

            if(!empty($approverData)){
                return response()->json([
                    'status' => true,
                    'message' => 'Approver found successfully',
                    'data' => $approverData
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Approver not found'
                ], 404);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving approver',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approvers $approvers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $approverData = Approvers::findorfail( $id );

            $inputData = $request->all();
            $approverData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Approver updated successfully',
                'data' => $approverData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Approver',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $approverData = Approvers::findorfail($id);
            $approverData->delete();
            return response()->json([
                'status' => true,
                'message' => 'Approver deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting approver',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
