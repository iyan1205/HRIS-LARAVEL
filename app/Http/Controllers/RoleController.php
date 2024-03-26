<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super-Admin');
    }

    public function index()
    {
        $roles = Role::get();
        return view('role-permission.role.index', [
            'roles' => $roles
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role-permission.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Role::create([
            'name' => $request->input('name'),
        ]);
        // Tambahkan session flash message
        $message = 'Roles berhasil ditambahkan';
        Session::flash('successAdd', $message);

        return redirect()->route('roles.index');
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
    public function edit(Role $role)
    {
        return view('role-permission.role.edit',[
            'role' => $role
        ]);
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
    public function destroy($roleUuid)
    {
        $role = Role::find($roleUuid);
        $role->delete();

        return redirect()->route('roles.index');
    }

    public function addPermissionToRole($roleUuid)
    {
        $permissions = Permission::get();
        $role = Role::findOrFail($roleUuid);
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $role->uuid)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();
        return view('role-permission.role.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function givePermissionToRole(Request $request, $roleUuid)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleUuid);
        $role->syncPermissions($request->permission);

        $message = 'Permissions ditambahkan';
        Session::flash('successAdd', $message);
        return redirect()->back();
    }
}
