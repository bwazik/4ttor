<?php

namespace Database\Seeders;

use App\Models\MyParent;
use App\Models\Student;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['students']);

        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');
        $parentsIds = MyParent::pluck('id')->toArray();

        for ($i = 0; $i < 361; $i++) {
            Student::create([
                'username' => $fakerEn->unique()->userName,
                'password' => Hash::make('123456789'),
                'name' => ['en' => $fakerEn -> name, 'ar' => $fakerAr -> name],
                'phone' => '01' . $fakerEn->randomElement([0, 1, 2, 5]) . $fakerEn->numerify('########'),
                'email' => $fakerEn->unique()->safeEmail,
                'gender' => rand(1, 2),
                'birth_date' => $fakerEn->dateTimeBetween('2004-01-01', '2015-12-31')->format('Y-m-d'),
                'grade_id' => rand(1, 6),
                'parent_id' => $fakerEn->randomElement($parentsIds),
                'balance' => $fakerEn->randomFloat(2, 0, 1000),
                'is_active' => $fakerEn->boolean(75),
                'is_exempted' => $fakerEn->boolean(10),
                'fees_discount' => $fakerEn->boolean(15) ? $fakerEn->randomFloat(2, 1, 100) : 0.00,
            ]);
        }
    }
}
