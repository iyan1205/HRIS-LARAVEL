<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\LeaveApplication;
use App\models\Overtime;
use App\models\OnCall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{

    public function index()
    {
        $jumlahKaryawanAktif = Karyawan::countByStatus('active');
        $jumlahKaryawanResign = Karyawan::countByStatus('resign');
        $karyawanCount = User::countByRole('karyawan');
        $pengajuanReject = 0;
        $pengajuanApproved = 0;
        $lemburpending = 0;
        $lemburrejected = 0;
        $lemburapproved = 0;
        $oncallpending = 0;
        $oncallrejected = 0;
        $oncallapproved = 0;
        $leaveApplicationsToday = 0;

        if(Auth::check()){
            /** @var App\Models\User */
            $users = Auth::user();
            $userId = auth()->id();
        if ($users->hasRole(['Super-Admin', 'admin'])) {
            $pengajuanCuti = LeaveApplication::where('status', 'pending')->count();
            $leaveApplicationsToday = LeaveApplication::getApplicationsStartingToday();
        }else{
            $pengajuanCuti = LeaveApplication::countByStatusAndUser('pending', $userId);
            $pengajuanReject = LeaveApplication::countByStatusAndUser('rejected', $userId);
            $pengajuanApproved = LeaveApplication::countByStatusAndUser('approved', $userId);
            $lemburpending = Overtime::countByStatusAndUser('pending', $userId);
            $lemburrejected = Overtime::countByStatusAndUser('rejected', $userId);
            $lemburapproved = Overtime::countByStatusAndUser('approved', $userId);
            $oncallpending = OnCall::countByStatusAndUser('pending', $userId);
            $oncallrejected = OnCall::countByStatusAndUser('rejected', $userId);
            $oncallapproved = OnCall::countByStatusAndUser('approved', $userId);
        }

    }
        return view('dashboard', compact(
            'jumlahKaryawanAktif','jumlahKaryawanResign','karyawanCount',
            'pengajuanCuti','pengajuanReject', 'pengajuanApproved',
            'lemburpending','lemburrejected','lemburapproved',
            'oncallpending','oncallrejected','oncallapproved','leaveApplicationsToday',
        ));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $photo = $request->file('photo');
        $filename = str_replace(' ', '_', $request->nama) . '_' . date('Y-m-d') . '.' . $photo->getClientOriginalExtension();

        $photo->storeAs('public/avatar', $filename);

        $data['name'] = $request->nama;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['image'] = $filename;

        $user = User::create($data);
        $user->assignRole('karyawan');

        $message = 'User berhasil ditambahkan';
        Session::flash('successAdd', $message);
        return redirect()->route('user');
    }

    public function edit(Request $request, $id)
    {
        $data = User::find($id);
        return view('user.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'password' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Jangan gunakan 'required' untuk photo karena bisa saja user tidak mengganti foto
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($id);

        $data['name'] = $request->nama;
        $data['email'] = $request->email;

        if ($request->password) {
            $data['password'] = Hash::make($request->password); // Gunakan $request->password, bukan $request->email
        }

        // Hapus foto lama jika pengguna mengunggah foto baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->image) {
                Storage::disk('public')->delete('avatar/' . $user->image);
            }

            // Unggah dan simpan foto baru
            $photo = $request->file('photo');
            $photoName = str_replace(' ', '_', $request->nama) . '_' . date('Y-m-d') . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('avatar/', $photoName, 'public');
            $data['image'] = $photoName;
        }

        $user->update($data);

        $message = 'User berhasil diedit';
        Session::flash('successAdd', $message);

        return redirect()->route('user');
    }

    public function delete(Request $request, $id)
    {
        $data = User::find($id);

        if ($data) {
            $data->delete();
        }
        return redirect()->route('user');
    }
}
