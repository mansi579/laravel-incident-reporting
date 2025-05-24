<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;
Use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function userAjax(Request $request)
    {
        $query = User::where('name', '!=', 'super-admin');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('roles', function ($user) {
                return $user->getRoleNames()->implode(', ');
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('user.edit', $row->id) . '" class="btn text-primary"><i class="fa fa-edit"></i></a>';
                $btn .= '<a href="javascript:void(0)" class="btn text-danger delete" data-id="' . $row->id . '" target-url="' . route('user.destroy', $row->id) . '"><i class="fa fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = User::latest()->get();
        return view('super-admin.user.index', compact('categories'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign user role when new user created
        $user->assignRole($request->role);
    
        return redirect()->route('user.index')->with('success', 'User created and role assigned successfully.');
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
     public function edit(User $user)
    {
        $data = $user;
        return view('super-admin.user.create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        $request->validate(rules: [
            'name' => 'required|max:255',           
            'email' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        // $user->update([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => $request->password,
        //     'is_block' => $request->has('is_block') ? 1 : 0,
        // ]);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->is_block = $request->has('is_block') ? 1 : 0; // ✅ Save is_block value
        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
