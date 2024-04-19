<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Overtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /// Ambil pengguna yang sedang login
        $user = Auth::user();

        // Ambil pengajuan cuti yang diajukan oleh pengguna yang sedang login
        $overtimes = Overtime::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('overtime.index', compact('overtimes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::pluck('name', 'id');
        return view('overtime.create', compact('users'));
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
