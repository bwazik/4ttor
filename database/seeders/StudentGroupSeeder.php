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

        $students = Student::with('teachers.groups')->select('id')->get();

        foreach ($students as $student) {
            $teachersIds = $student->teachers()->pluck('teachers.id')->toArray();

            $relatedGroupIds = Group::whereHas('teacher', function($query) use ($teachersIds) {
                $query->whereIn('id', $teachersIds);
            })->pluck('id')->toArray();

            if (!empty($relatedGroupIds)) {
                $randomGroupsIds = $this->getRandomElements($relatedGroupIds, rand(1, 2));
                $student->groups()->attach($randomGroupsIds);
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
