<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Student;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;

class StudentGroupSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['student_group']);

        // Eager load students with their teachers and grade
        $students = Student::with(['teachers.groups', 'grade'])->get();

        foreach ($students as $student) {
            // Get the student's grade ID
            $studentGradeId = $student->grade_id;

            // Process each teacher the student has
            foreach ($student->teachers as $teacher) {
                // Get groups for this teacher in the student's grade
                $teacherGroups = $teacher->groups()
                    ->where('grade_id', $studentGradeId)
                    ->get();

                if ($teacherGroups->isNotEmpty()) {
                    // Select one random group from this teacher's groups
                    $groupToJoin = $teacherGroups->random();

                    // Attach the student to this group
                    $student->groups()->syncWithoutDetaching($groupToJoin->id);
                }
            }
        }
    }

    private function getRandomElements(array $array, int $count)
    {
        // If the count is greater than the array size, just return the full array
        if ($count >= count($array)) {
            return $array;
        }

        // Shuffle the array and slice it to get the desired count
        shuffle($array);
        return array_slice($array, 0, $count);
    }
}
