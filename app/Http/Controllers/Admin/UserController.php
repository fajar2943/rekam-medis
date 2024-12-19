<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $roles = $request->roles ? [$request->roles] : ['admin', 'doctor', 'pharmacist', 'patient'];
        $users = User::search($request->search)->whereIn('role', $roles)->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request){
        $this->_validation($request);
        $request['rm'] = $request->role == 'patient' ? setRm() : null;
        User::create($request->only('name', 'email', 'phone', 'id_number', 'rm', 'role', 'address', 'password'));
        return back()->with('success', 'Data created successfully');
    }

    public function update(Request $request, User $user){
        $request['id'] = $user->id;
        $this->_validation($request);
        $user->update($request->only('name', 'email', 'phone', 'id_number', 'role', 'address'));
        return back()->with('success', 'Data updated successfully');
    }

    public function destroy(User $user){
        $user->delete();
        return back()->with('success', 'Data removed');
    }

    private function _validation(Request $request){
        return $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:users|email|max:50',
            'phone' => 'required|max:50',
            'id_number' => 'required|unique:users|max:50',
            'role' => 'required|max:50',
            'address' => 'required|max:255',
            'password' => 'required|max:255',
        ]);
    }
}
