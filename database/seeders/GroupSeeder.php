<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Teacher;
use App\Traits\Truncatable;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    use Truncatable;

    private $dayMapping = [
        'Saturday' => 1,
        'Sunday' => 2,
        'Monday' => 3,
        'Tuesday' => 4,
        'Wednesday' => 5,
        'Thursday' => 6,
        'Friday' => 7,
    ];

    public function run()
    {
        $this->truncateTables(['groups']);

        $faker = Faker::create();


        for($i = 0; $i < 120; $i++)
        {
            $days = $faker->randomElements(array_keys($this->dayMapping), 2);
            $time = $faker->time('H:i');

            $groupNameEn = "{$days[0]} & {$days[1]} $time";
            $groupNameAr = "{$this->getArabicDay($days[0])} & {$this->getArabicDay($days[1])} $time";

            $day1Number = $this->dayMapping[$days[0]];
            $day2Number = $this->dayMapping[$days[1]];
            
            $teachersIds = Teacher::pluck('id')->toArray();
            $teacherId = $faker->randomElement($teachersIds);
            $teacher = Teacher::with('grades')->findOrFail($teacherId);
            $gradeId = $faker->randomElement($teacher->grades->pluck('id')->toArray());

            Group::create([
                'name' => ['en' => $groupNameEn, 'ar' => $groupNameAr],
                'teacher_id' => $teacherId,
                'grade_id' => $gradeId,
                'day_1' => $day1Number,
                'day_2' => $day2Number,
                'time' => $time,
                'is_active' => $faker->boolean(75),
            ]);
        }
    }

    private function getArabicDay($englishDay)
    {
        $arabicDays = [
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
        ];

        return $arabicDays[$englishDay] ?? $englishDay;
    }
}
