<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use App\Mail\SetupPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    public function index()
    {
        return Person::with('users')
            ->whereHas('users', fn($query) => $query->whereIn('role', ['doctor', 'secretary']))->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:doctor,secretary',
            'ci' => 'required|string', // Se asume necesario para verificar existencia
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Buscar o crear persona
            $person = Person::firstOrCreate(
                ['ci' => $validated['ci']],
                [
                    'full_name' => $validated['full_name'],
                    'phone_number' => $validated['phone_number'],
                ]
            );

            // 2. Crear usuario
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(16)), // Dummy, se cambiará con el reset
                'role' => $validated['role'],
                'person_id' => $person->id,
            ]);

            // 3. Enviar correo de configuración
            $token = Password::createToken($user);
            $setupUrl = env('FRONTEND_URL') . '/reset-password?token=' . $token . '&email=' . urlencode($user->email);
            Mail::to($user->email)->send(new SetupPasswordNotification($setupUrl));

            return response()->json(['message' => 'Personal creado exitosamente', 'user' => $user], 201);
        });
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'full_name' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $person->update($validated);

        return response()->json(['message' => 'Personal actualizado']);
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json(['message' => 'Personal eliminado']);
    }
}
