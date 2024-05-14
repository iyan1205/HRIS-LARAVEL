<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function getLeaveTypeByCategory($kategori_cuti){
        $leaveTypes = LeaveType::where('kategori_cuti', $kategori_cuti)->pluck('name', 'id');
        return response()->json($leaveTypes);
    }

    public function getMaxAmount($id)
    {
        $leaveType = LeaveType::find($id);
        if ($leaveType) {
            return response()->json([
                'max_amount' => $leaveType->max_amount,
                'file_upload' => $leaveType->file_upload
            ]);
        } else {
            return response()->json(['error' => 'Leave type not found'], 404);
        }
    }

    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
