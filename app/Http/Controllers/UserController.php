<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view user', ['only' => ['index']]);
        $this->middleware('permission:create user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }

    public function index()
    {
        $users = User::get();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'roles' => 'required|array',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data['name'] = $request->nama;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['roles'] = $request->roles;

        $user = User::create($data);

        $user->syncRoles($request->roles);

        // Buat saldo cuti baru dengan nilai awal 0
        $leaveBalance = LeaveBalance::create([
            'user_id' => $user->id,
            'saldo_cuti' => 0,
        ]);


        $message = 'User berhasil ditambahkan';
        Session::flash('successAdd', $message);
        return redirect()->route('user');
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view('user.edit', compact('user','roles','userRoles'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'password' => 'nullable',
            'roles' => 'required|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($id);

        $data['name'] = $request->nama;
        $data['email'] = $request->email;
        $data['roles'] = $request->roles;

        if ($request->password) {
            $data['password'] = Hash::make($request->password); //
        }

        // Hapus foto lama jika pengguna mengunggah foto baru
        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($user->image) {
                Storage::disk('public')->delete('avatar/' . $user->image);
            }

            // Unggah dan simpan foto baru
            $image = $request->file('image');
            $imageName = $user->name . '.' . $image->getClientOriginalExtension();
            $image->storeAs('avatar/', $imageName, 'public');
            $data['image'] = $imageName;
        }

        $user->update($data);
        $user->syncRoles($request->roles);

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
    
    public function addSaldoCutiToExistingUsers()
    {
        // Ambil semua pengguna dari tabel users
        $users = User::all();

        // Iterasi semua pengguna
        foreach ($users as $user) {
            // Periksa apakah saldo cuti sudah ada untuk pengguna ini
            $existingLeaveBalance = LeaveBalance::where('user_id', $user->id)->first();

            // Jika saldo cuti belum ada untuk pengguna ini, tambahkan saldo cuti baru
            if (!$existingLeaveBalance) {
                LeaveBalance::create([
                    'user_id' => $user->id,
                    'saldo_cuti' => 0, // Saldo awal diatur ke 0
                ]);
            }
        }

        // Tambahkan pesan sukses
        $message = 'Saldo cuti berhasil ditambahkan kepada pengguna yang sudah ada.';
        Session::flash('success', $message);

        // Redirect ke halaman tertentu atau tampilkan pesan sukses sesuai kebutuhan
        return redirect()->route('user');
    }
}
