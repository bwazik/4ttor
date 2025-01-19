<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\Truncatable;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['subjects']);

        $subjects = [
            ['en' => 'Mathematics', 'ar' => 'رياضة'],
            ['en' => 'Pure Mathematics', 'ar' => 'بحتة'],
            ['en' => 'Applied Mathematics', 'ar' => 'تطبيقية'],
            ['en' => 'Science', 'ar' => 'علوم'],
            ['en' => 'Physics', 'ar' => 'فيزياء'],
            ['en' => 'Chemistry', 'ar' => 'كيمياء'],
            ['en' => 'Biology', 'ar' => 'أحياء'],
            ['en' => 'Geography', 'ar' => 'جغرافيا'],
            ['en' => 'History', 'ar' => 'تاريخ'],
            ['en' => 'Arabic', 'ar' => 'اللغة العربية'],
            ['en' => 'English', 'ar' => 'إنجليزي'],
            ['en' => 'French', 'ar' => 'فرنساوي'],
            ['en' => 'German', 'ar' => 'ألماني'],
            ['en' => 'Spanish', 'ar' => 'أسباني'],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject,
                'is_active' => rand(0, 1),
            ]);

        }
    }
}
