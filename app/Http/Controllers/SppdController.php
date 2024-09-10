<?php

namespace App\Http\Controllers;

use App\Models\Sppd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SppdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $sppds = Sppd::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('sppd.index', compact('sppds'));
    }

    public function approval(){
        //
    }

    public function create()
    {
        $users = User::pluck('name', 'id'); //Select Nama Karyawan/User
        
        return view('cuti.create', compact('users',));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

    public function approve(string $id)
    {
        //
    }

    public function reject(string $id)
    {
        //
    }

    public function riwayat(string $id)
    {
        //
    }

    public function laporan(string $id)
    {
        //
    }

    public function search(string $id)
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
