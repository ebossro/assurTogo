<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.auth', ['action' => 'register']);
    }

    // Traiter l'inscription
    public function register(Request $request)
    {
        $data = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Récupérer le rôle 'client' par défaut
        $clientRole = Role::where('typeRole', 'client')->first();

        $user = User::create([
            'prenom' => $request->prenom,
            'name' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role_id' => $clientRole ? $clientRole->id : null,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    // Afficher le formulaire de connexion
    public function showLogin()
    {
        return view('auth.auth', ['action' => 'login']);
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirection selon le rôle
            $role = Auth::user()->role ? Auth::user()->role->typeRole : 'client';

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'assure') {
                return redirect()->route('dashboard.index');
            }

            // Si client simple (pas encore souscrit ou rôle par défaut)
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Afficher le formulaire de mot de passe oublié
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
}
