<?php

namespace Database\Seeders;

use App\Models\Assistant;
use App\Models\Teacher;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AssistantSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['assistants']);

        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');
        $teachersIds = Teacher::pluck('id')->toArray();

        for ($i = 0; $i < 168; $i++) {
            Assistant::create([
                'username' => $fakerEn->unique()->userName,
                'password' => Hash::make('password'),
                'name' => ['en' => $fakerEn -> name, 'ar' => $fakerAr -> name],
                'phone' => '01' . $fakerEn->randomElement([0, 1, 2, 5]) . $fakerEn->numerify('########'),
                'email' => $fakerEn->unique()->safeEmail,
                'teacher_id' => $fakerEn->randomElement($teachersIds),
                'is_active' => $fakerEn->boolean(75),
            ]);
        }
    }
}
