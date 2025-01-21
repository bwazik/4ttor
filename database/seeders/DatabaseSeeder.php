<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(StageSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(TeacherGradeSeeder::class);
        $this->call(AssistantSeeder::class);
        $this->call(ParentSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(StudentTeacherSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(StudentGroupSeeder::class);
    }
}
