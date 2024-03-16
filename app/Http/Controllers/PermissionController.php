<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::get();
        return view('role-permission.permission.index', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role-permission.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Permission::create([
            'name' => $request->input('name'),
        ]);

        // Tambahkan session flash message
        $message = 'Permissions berhasil ditambahkan';
        Session::flash('successAdd', $message);

        return redirect()->route('permissions.index');
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
    public function edit(Permission $permission)
    {
        return view('role-permission.permission.edit',[
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
          // Validasi input dari form
          $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions'
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $permission->update([
            'name' => $request->input('name'),
        ]);

        // Tambahkan session flash message
        $message = 'Permissions berhasil diedit';
        Session::flash('successAdd', $message);

        return redirect()->route('permissions.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($permissionUuid)
    {
        $permission = Permission::find($permissionUuid);
        $permission->delete();

        return redirect()->route('permissions.index');
    }
}
