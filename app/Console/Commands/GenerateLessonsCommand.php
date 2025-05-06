<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;
use App\Services\Admin\Tools\LessonService;

class GenerateLessonsCommand extends Command
{
    protected $signature = 'lessons:generate';
    protected $description = 'Generate lessons for all active groups for the next month';

    public function handle()
    {
        $groups = Group::active()->get();
        $service = new LessonService();

        foreach ($groups as $group) {
            $this->info("Generating lessons for group: {$group->name}");
            $service->generateLessonsForGroup($group->id);
        }

        $this->info('Lesson generation completed.');
    }
}
