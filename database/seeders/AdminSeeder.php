<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    use Truncatable;

    public function run(): void
    {
        $this->truncateTables(['users']);

        User::create([
            'name' => ['en' => 'Abdullah Mohamed', 'ar' => 'عبدالله محمد'],
            'email' => 'bwazik@outlook.com',
            'password' => Hash::make('bwazik@outlook.com'),
        ]);
    }
}
