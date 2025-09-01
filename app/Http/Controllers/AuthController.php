<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $user = User::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials.'])->withInput();
        }

        Auth::login($user);
        $request->session()->put('user_id', $user->id);

        return redirect()->route('vehicles.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // $request->session()->forget('user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
