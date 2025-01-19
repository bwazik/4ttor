<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Grade;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;

class TeacherGradeSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['teacher_grade']);

        $teachers = Teacher::select('id')->get();
        $gradesIds = Grade::pluck('id')->toArray();

        foreach ($teachers as $teacher) {
            $randomGradesIds = array_rand(array_flip($gradesIds), rand(1, 3));
            $teacher->grades()->attach($randomGradesIds);
        }
    }
}
