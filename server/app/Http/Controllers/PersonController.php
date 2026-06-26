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
        return Person::with('users.doctor')
            ->whereHas('users', fn($query) => $query->whereIn('role', ['doctor', 'secretary']))->get();
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
            $photoPath = 'nophoto.webp';
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
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
        ]);

        $data = [
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $personnel->update($data);


        foreach ($personnel->users as $user) {
            if ($user && $user->role === 'doctor') {
                $user->doctor()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['specialty' => $validated['specialty'] ?? null]
                );

                break; // Assuming only one doctor per person, exit the loop after updating
            }

        }


        return response()->json(['message' => 'Personal actualizado']);
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
}
