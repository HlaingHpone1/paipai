<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Swan Yee Myo',
            'email' => 'swanyeemyo2001@gmail.com',
            'password' => Hash::make('password'),
            'address' => "Yangon",
            'phone' => '09950314865',
            'gender' => 0
        ]);

        $user->assignRole('admin');
    }
}
