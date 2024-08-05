<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Role::updateOrCreate(['name' => 'admin'],['name' => 'admin']);
        Role::updateOrCreate(['name' => 'karyawan'],['name' => 'karyawan']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'username' => 'admin',
            'role' => 'admin',
            'id_karyawan' => '1234567890',
            'password' => Hash::make('admin'),
            'enc_password' => Crypt::encryptString('admin')
        ]);

        $user->assignRole('admin');
    }
}
