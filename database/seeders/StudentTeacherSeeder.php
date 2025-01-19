<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Student;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;

class StudentTeacherSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['student_teacher']);

        $students = Student::select('id')->get();
        $teachersIds = Teacher::pluck('id')->toArray();

        foreach ($students as $student) {
            $randomTeachersIds = array_rand(array_flip($teachersIds), rand(1, 2));
            $student->teachers()->attach($randomTeachersIds);
        }
    }
}
