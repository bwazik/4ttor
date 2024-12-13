<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\Truncatable;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['grades']);

        $preparatory_grades = [
            ['en' => 'Grade 7', 'ar' => 'أولي إعدادي'],
            ['en' => 'Grade 8', 'ar' => 'تانية إعدادي'],
            ['en' => 'Grade 9', 'ar' => 'تالتة إعدادي'],
        ];

        foreach ($preparatory_grades as $preparatory_grade) {
            Grade::create([
                'name' => $preparatory_grade,
                'stage_id' => 1,
            ]);
        }

        $highschool_grades = [
            ['en' => 'Grade 10', 'ar' => 'أولي ثانوي'],
            ['en' => 'Grade 11', 'ar' => 'تانية ثانوي'],
            ['en' => 'Grade 12', 'ar' => 'تالتة ثانوي'],
        ];

        foreach ($highschool_grades as $highschool_grade) {
            Grade::create([
                'name' => $highschool_grade,
                'stage_id' => 2,
            ]);
        }
    }
}
