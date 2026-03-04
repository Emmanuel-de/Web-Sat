<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Login ───────────────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'rfc'      => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i'],
            'password' => ['required', 'string'],
        ], [
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.regex'    => 'El formato del RFC no es válido.',
        ]);

        $credentials = [
            'rfc'      => strtoupper($request->rfc),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json([
                    'message'  => 'Sesión iniciada correctamente.',
                    'redirect' => route('home'),
                    'user'     => Auth::user()->nombre_completo,
                ]);
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Bienvenido, ' . Auth::user()->nombre_completo);
        }

        $error = 'El RFC o la contraseña son incorrectos.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $error], 401);
        }

        return back()->withErrors(['rfc' => $error])->onlyInput('rfc');
    }

    // ─── Registro ────────────────────────────────────────────────
    public function showRegistro()
    {
        return view('pages.auth.registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
        'tipo'             => ['required', 'in:fisica,moral'],
        'rfc'              => ['required', 'string', 'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/i', 'unique:users,rfc'],
        'email'            => ['required', 'email', 'unique:users,email', 'confirmed'],
        'telefono'         => ['required', 'string', 'digits:10'],
        'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        
        // Solo obligatorios si es persona física
        'nombres'          => ['required_if:tipo,fisica', 'nullable', 'string', 'max:80'],
        'primer_apellido'  => ['required_if:tipo,fisica', 'nullable', 'string', 'max:50'],
        'curp'             => ['required_if:tipo,fisica', 'nullable', 'string', 'size:18', 'unique:users,curp'],
        'fecha_nacimiento' => ['required_if:tipo,fisica', 'nullable', 'date'],

        // Solo obligatorios si es persona moral
        'razon_social'     => ['required_if:tipo,moral', 'nullable', 'string', 'max:200'],
    ]);

        $user = User::create([
        'tipo'             => $request->tipo,
        'rfc'              => strtoupper($request->rfc),
        'email'            => $request->email,
        'telefono'         => $request->telefono,
        'password'         => Hash::make($request->password),
        'activo'           => true,

        // Datos Persona Física (nota que ahora usamos 'nombres' con S)
        'curp'             => $request->curp ? strtoupper($request->curp) : null,
        'nombres'          => $request->nombres,
        'primer_apellido'  => $request->primer_apellido,
        'segundo_apellido' => $request->segundo_apellido,
        'fecha_nacimiento' => $request->fecha_nacimiento,
        
        // Datos Persona Moral
        'razon_social'     => $request->razon_social,
        'tipo_sociedad'    => $request->tipo_sociedad,
        'rep_nombre'       => $request->rep_nombre,
        'rep_rfc'          => $request->rep_rfc ? strtoupper($request->rep_rfc) : null,
        'acepta_notificaciones' => $request->has('acepta_notificaciones'),
    ]);

        Auth::login($user);

    if ($request->expectsJson()) {
        return response()->json([
            'message'  => 'Cuenta creada exitosamente. ¡Bienvenido al SAT!',
            'redirect' => route('dashboard'),
        ]);
    }

        return redirect()->route('dashboard')->with('success', '¡Cuenta creada exitosamente!');
    }

    // ─── Logout ──────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente.');
    }
}
