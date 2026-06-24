<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear el primer usuario administrador
        $admin1 = User::create([
            'email' => 'juandonquis07@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        Person::create([
            'user_id' => $admin1->id,
            'full_name' => 'Juan Donquis',
            'ci' => '12345678',
            'phone_number' => '+584123456789',
            'photo' => null,
            'date_birth' => '1990-01-01',
            'address' => 'Caracas, Venezuela',
        ]);

        // 2. Crear el segundo usuario administrador
        $admin2 = User::create([
            'email' => 'deivis.don@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        Person::create([
            'user_id' => $admin2->id,
            'full_name' => 'Deivis Don',
            'ci' => '87654321',
            'phone_number' => '+584129876543',
            'photo' => null,
            'date_birth' => '1992-05-15',
            'address' => 'Maracaibo, Venezuela',
        ]);
    }
}
