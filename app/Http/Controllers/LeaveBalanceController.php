<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LeaveBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view saldo', ['only' => ['index']]);
        $this->middleware('permission:create saldo', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit saldo', ['only' => ['edit','update']]);
        $this->middleware('permission:delete saldo', ['only' => ['destroy']]);
    }

    public function index()
    {
        // Ambil saldo cuti berdasarkan pengguna yang sedang login
         /** @var App\Models\User */
        $user = Auth::user();
        
            // Jika pengguna adalah Super-Admin atau Admin, tampilkan semua data saldo cuti
        if ($user->hasRole(['Super-Admin', 'admin'])) {
            $leaveBalance = LeaveBalance::all();
        } else {
            // Ambil saldo cuti berdasarkan pengguna yang sedang login
            $leaveBalance = LeaveBalance::where('user_id', $user->id)->get();

            // Jika saldo cuti belum ada, buat saldo cuti baru untuk pengguna
            if ($leaveBalance->isEmpty()) {
                $leaveBalance = LeaveBalance::create([
                    'user_id' => $user->id,
                    'saldo_cuti' => 0 // Atur saldo awal sesuai kebutuhan
                ]);
            }
        }

        return view('cuti.saldo-cuti.index', compact('leaveBalance'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); //Select Nama Karyawan/User        
        return view('cuti.saldo-cuti.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validasi input dari form
         $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|max:255|unique:leave_balances',
            'saldo_cuti' => 'required'
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Buat dan simpan leave_balances baru
        $leave_balances = LeaveBalance::create([
            'user_id' => $request->input('user_id'),
            'saldo_cuti' => $request->input('saldo_cuti'),
            // Tambahkan kolom lain yang perlu disimpan
        ]);

        // Tambahkan session flash message
        $message = 'Saldo Cuti Berhasil Ditambahkan';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('saldo-cuti');
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
    public function edit(Request $request, $id)
    {
        $leaveBalance = LeaveBalance::find($id);
        return view('cuti.saldo-cuti.edit', compact('leaveBalance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'saldo_cuti' => 'required|numeric|min:0',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Temukan saldo cuti yang ingin diperbarui
        $leaveBalance = LeaveBalance::findOrFail($id);

        // Perbarui atribut saldo cuti sesuai dengan data yang diterima dari request
        $leaveBalance->update([
        'saldo_cuti' => $request->input('saldo_cuti'),

        ]);

        // Tambahkan session flash message
        $message = 'Saldo Cuti Berhasil Diedit';
        Session::flash('successAdd', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses
        return redirect()->route('saldo-cuti');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
