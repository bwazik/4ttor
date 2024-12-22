<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Traits\Truncatable;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['plans']);

        $plan1 = [
                'name' => ['en' => 'Basic Plan', 'ar' => 'الخطة الأولي'],
                'description' => [
                    'en' => 'A basic plan to get started, with essential features and limited reports.',
                    'ar' => 'خطة بسيطة للبداية، فيها المميزات الأساسية مع عدد محدود من التقارير.'
                ],
                'monthly_price' => 800,
                'term_price' => 3960,
                'year_price' => 6273,
                'student_limit' => 120,
                'parent_limit' => 120,
                'assistant_limit' => 2,
                'group_limit' => 3,
                'quiz_monthly_limit' => 2,
                'quiz_term_limit' => 6,
                'quiz_year_limit' => 12,
                'assignment_monthly_limit' => 4,
                'assignment_term_limit' => 16,
                'assignment_year_limit' => 48,
                'attendance_reports' => true,
                'financial_reports' => false,
                'performance_reports' => false,
                'whatsapp_messages' => false,
                'is_active' => true,
        ];

        $plan2 = [
            'name' => ['en' => 'Standard Plan', 'ar' => 'الخطة الثانية'],
            'description' => [
                'en' => 'A plan with more features and options for better student and parent management.',
                'ar' => 'خطة فيها مميزات أكتر وإدارة للطلاب وأولياء الأمور بشكل أفضل.'
            ],
            'monthly_price' => 1500,
            'term_price' => 7260,
            'year_price' => 11000,
            'student_limit' => 250,
            'parent_limit' => 250,
            'assistant_limit' => 6,
            'group_limit' => 8,
            'quiz_monthly_limit' => 6,
            'quiz_term_limit' => 18,
            'quiz_year_limit' => 36,
            'assignment_monthly_limit' => 6,
            'assignment_term_limit' => 24,
            'assignment_year_limit' => 72,
            'attendance_reports' => true,
            'financial_reports' => true,
            'performance_reports' => true,
            'whatsapp_messages' => true,
            'is_active' => true,
        ];

        $plan3 = [
            'name' => ['en' => 'Premium Plan', 'ar' => 'الخطة الثالثة'],
            'description' => [
                'en' => 'A comprehensive plan with advanced features for managing a larger group of students.',
                'ar' => 'خطة متكاملة فيها مميزات متقدمة لإدارة عدد أكبر من الطلاب.'
            ],
            'monthly_price' => 2500,
            'term_price' => 11687,
            'year_price' => 17450,
            'student_limit' => 600,
            'parent_limit' => 600,
            'assistant_limit' => 12,
            'group_limit' => 12,
            'quiz_monthly_limit' => 12,
            'quiz_term_limit' => 36,
            'quiz_year_limit' => 72,
            'assignment_monthly_limit' => 10,
            'assignment_term_limit' => 40,
            'assignment_year_limit' => 120,
            'attendance_reports' => true,
            'financial_reports' => true,
            'performance_reports' => true,
            'whatsapp_messages' => true,
            'is_active' => true,
        ];

        $plan4 = [
            'name' => ['en' => 'Elite Plan', 'ar' => 'الخطة الرابعة'],
            'description' => [
                'en' => 'The ultimate plan with unlimited features for complete control and management.',
                'ar' => 'خطة نهائية فيها مميزات غير محدودة للتحكم الكامل في الإدارة.'
            ],
            'monthly_price' => 3500,
            'term_price' => 15785,
            'year_price' => 22233,
            'student_limit' => 1200,
            'parent_limit' => 1200,
            'assistant_limit' => 30,
            'group_limit' => 25,
            'quiz_monthly_limit' => 30,
            'quiz_term_limit' => 60,
            'quiz_year_limit' => 120,
            'assignment_monthly_limit' => 20,
            'assignment_term_limit' => 80,
            'assignment_year_limit' => 240,
            'attendance_reports' => true,
            'financial_reports' => true,
            'performance_reports' => true,
            'whatsapp_messages' => true,
            'is_active' => true,
        ];

        $plans = [$plan1, $plan2, $plan3, $plan4];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}

