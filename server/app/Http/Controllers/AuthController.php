<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Mail\ResetPasswordNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class AuthController extends Controller
{
    /**
     * Autenticar al usuario.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Intentar autenticar con el guard 'web'
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas no son correctas.'],
            ]);
        }

        $request->session()->regenerate();

        // Obtener el usuario autenticado con su relación 'person'
        $user = Auth::user()->load('person');

        return response()->json([
            'user' => $user,
            'message' => 'Autenticación exitosa',
        ]);
    }

    /**
     * Cerrar la sesión del usuario.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function forgotPassword(Request $request): JsonResponse
        {
            $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ], [
                'email.exists' => 'No se encontró ninguna cuenta asociada a este correo electrónico.'
            ]);
   
            $user = User::where('email', $request->email)->first();
   
            $token = Password::createToken($user);
   
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($user->email);
   
            Mail::to($user->email)->send(new ResetPasswordNotification($resetUrl));
   
            return response()->json([
                'message' => 'Le hemos enviado un enlace para restablecer su contraseña a su bandeja de entrada.'
            ]);
        }


    /**
     * Resetear contraseña del usuario.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Contraseña restablecida exitosamente']);
        }

        // Si falla, el token es inválido o expiró
        return response()->json(['message' => 'El enlace ha expirado o es inválido.'], 422);
    }
}
