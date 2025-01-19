<?php

namespace Database\Seeders;

use App\Models\MyParent;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ParentSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['parents']);

        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        for ($i = 0; $i < 190; $i++) {
            MyParent::create([
                'username' => $fakerEn->unique()->userName,
                'password' => Hash::make('password'),
                'name' => ['en' => $fakerEn -> name, 'ar' => $fakerAr -> name],
                'phone' => '01' . $fakerEn->randomElement([0, 1, 2, 5]) . $fakerEn->numerify('########'),
                'email' => $fakerEn->unique()->safeEmail,
                'gender' => rand(1, 2),
                'is_active' => $fakerEn->boolean(75),
            ]);
        }
    }
}
