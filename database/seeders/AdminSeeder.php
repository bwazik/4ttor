<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use Truncatable;

    public function run(): void
    {
        $this->truncateTables(['users']);

        User::create([
            'username' => 'bwazik',
            'name' => ['en' => 'Abdullah Mohamed Fathy', 'ar' => 'عبدالله محمد فتحي'],
            'email' => 'bwazik@outlook.com',
            'password' => Hash::make('123456789'),
        ]);
    }
}
