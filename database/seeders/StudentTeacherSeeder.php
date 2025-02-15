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

    $students = Student::with('grade')->select('id', 'grade_id')->get();
    $teachers = Teacher::with('grades')->get();

    foreach ($students as $student) {
        // Filter teachers who teach the student's grade
        $eligibleTeachers = $teachers->filter(function ($teacher) use ($student) {
            return $teacher->grades->contains('id', $student->grade_id);
        })->pluck('id')->toArray();

        if (!empty($eligibleTeachers)) {
            // Get 1-2 random teachers from eligible ones
            $numTeachers = min(rand(1, 2), count($eligibleTeachers));
            $randomTeachersIds = array_rand(array_flip($eligibleTeachers), $numTeachers);

            $student->teachers()->attach($randomTeachersIds);
        }
    }
}
}
