<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Person;
use App\Models\User;
use App\Mail\SetupPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    public function index()
    {
        return User::with(['person', 'doctor'])
            ->whereIn('role', ['doctor', 'secretary'])
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:doctor,secretary',
            'ci' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'specialty' => 'required_if:role,doctor|string|max:255',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // 1. Verificar rol único
            $person = Person::where('ci', $validated['ci'])->first();
            if ($person && $person->users()->where('role', $validated['role'])->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'role' => ['Esta persona ya tiene un usuario registrado con el rol seleccionado.'],
                ]);
            }

            // 2. Procesar foto
            $photoPath = 'nophoto.webp';
            if ($request->hasFile('photo')) {
                $photoPath = $this->processAndStorePhoto($request->file('photo'));
            }

            $person = Person::firstOrCreate(
                ['ci' => $validated['ci']],
                [
                    'full_name' => $validated['full_name'],
                    'phone_number' => $validated['phone_number'],
                    'photo' => $photoPath,
                ]
            );

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(16)),
                'role' => $validated['role'],
                'person_id' => $person->id,
            ]);

            if ($validated['role'] === 'doctor') {
                Doctor::create([
                    'user_id' => $user->id,
                    'specialty' => $validated['specialty'],
                ]);
            }

            $token = Password::createToken($user);
            $setupUrl = env('FRONTEND_URL') . '/reset-password?token=' . $token . '&email=' . urlencode($user->email);
            Mail::to($user->email)->send(new SetupPasswordNotification($setupUrl));

            return response()->json(['message' => 'Personal creado exitosamente', 'user' => $user], 201);
        });
    }

    public function update(Request $request, Person $personnel)
    {
        $validated = $request->validate([
            'full_name' => 'required|string',
            'phone_number' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'specialty' => 'nullable|string|max:255',
            'role' => 'nullable|in:doctor,secretary',
            'user_id' => 'required|exists:users,id', // Asegurar que recibimos el ID del usuario
        ]);

        return DB::transaction(function () use ($request, $personnel, $validated) {
            $user = $personnel->users()->findOrFail($validated['user_id']);

            // 1. Validar email único excluyendo al usuario actual
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);

            // 2. Verificar rol único excluyendo el usuario que se está editando
            if (isset($validated['role'])) {
                $roleExists = $personnel->users()
                    ->where('role', $validated['role'])
                    ->where('id', '!=', $user->id)
                    ->exists();

                if ($roleExists) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'role' => ['Esta persona ya tiene un usuario registrado con el rol seleccionado.'],
                    ]);
                }
            }

            // 3. Lógica de email y verificación
            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $user->email_verified_at = null;
                $user->save();
                $user->sendEmailVerificationNotification();
            }

            $data = [
                'full_name' => $validated['full_name'],
                'phone_number' => $validated['phone_number'],
            ];

            if ($request->hasFile('photo')) {
                // Eliminar foto anterior si no es la por defecto
                if ($personnel->photo && $personnel->photo !== 'nophoto.webp') {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($personnel->photo);
                }
                $data['photo'] = $this->processAndStorePhoto($request->file('photo'));
            }

            $personnel->update($data);

            if (isset($validated['specialty'])) {
                if ($user->role === 'doctor') {
                    $user->doctor()->updateOrCreate(
                        ['user_id' => $user->id],
                        ['specialty' => $validated['specialty']]
                    );
                }
            }

            return response()->json(['message' => 'Personal actualizado']);
        });
    }

    private function processAndStorePhoto($file)
    {
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        $filename = 'photos/' . Str::uuid() . '.webp';

        // Convertir a WebP
        imagewebp($image, storage_path('app/public/' . $filename), 80);
        imagedestroy($image);

        return $filename;
    }

    public function destroy(Person $personnel)
    {
        Log::info('Intentando eliminar personal con ID: ' . $personnel->id);
        Log::info('Usuarios asociados: ' . $personnel->users()->count());
        Log::info('Usuarios asociados con roles doctor o secretary: ' . $personnel->users()->whereIn('role', ['doctor', 'secretary'])->count());
        $hasStaffUser = $personnel->users()->whereIn('role', ['doctor', 'secretary'])->exists();
        if (!$hasStaffUser) {
            return response()->json(['message' => 'El personnelal no existe o no es válido'], 404);
        }

        try {
            DB::transaction(function () use ($personnel) {
                $personnel->load('users.doctor');

                foreach ($personnel->users as $user) {
                    if ($user->doctor) {
                        $user->doctor->delete();
                    }
                    $user->delete();
                }

                $personnel->delete();
            });

            return response()->json(['message' => 'Personal eliminado exitosamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar personal: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el personal'], 500);
        }
    }

    public function show(string $id)
    {
        $person = Person::findOrFail($id);
        $user = User::with('doctor')->where('person_id', $person->id)->first();
        return response()->json([
            'person' => $person,
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null,
            'role' => $user ? $user->role : null,
            'doctor' => $user ? $user->doctor : null,
        ]);
    }
}
