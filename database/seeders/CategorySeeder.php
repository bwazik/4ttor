<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\Truncatable;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['categories']);

        $categories = [
            // Students
            [
                'name' => ['en' => 'Payment & Fees', 'ar' => 'الدفع والمصاريف'],
                'slug' => 'payment-fees',
                'icon' => 'bank-card-line',
                'description' => ['en' => 'Managing payments, viewing fees, and handling financial transactions with teachers', 'ar' => 'إدارة الأموال وعرض المصاريف والتعامل مع الحسابات المالية مع المدرسين'],
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Teachers
            [
                'name' => ['en' => 'Payment & Subscriptions', 'ar' => 'الدفع والإشتراكات'],
                'slug' => 'payment-subscriptions',
                'icon' => 'bank-card-line',
                'description' => ['en' => 'Subscription plans, payment methods, and earning history', 'ar' => 'خطط الاشتراك وطرق الدفع وسجل الأموال'],
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Cancellation & Return', 'ar' => 'الإلغاء والإسترجاع'],
                'slug' => 'cancellation-return',
                'icon' => 'refresh-line',
                'description' => ['en' => 'Cancellation policies and subscription refund procedures', 'ar' => 'قواعد الإلغاء وطريقة استرداد الأموال'],
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Security', 'ar' => 'الأمان'],
                'slug' => 'security',
                'icon' => 'lock-line',
                'description' => ['en' => 'Account security, two-factor authentication, and privacy settings', 'ar' => 'أمان الحساب وإعدادات الخصوصية بتاعتك'],
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Personal Data', 'ar' => 'البيانات الشخصية'],
                'slug' => 'personal-data',
                'icon' => 'user-settings-line',
                'description' => ['en' => 'Profile settings, contact information, and notification preferences', 'ar' => 'إعدادات البروفايل بتاعك وبيانات الاتصال وإعدادات الإشعارات'],
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Users Managment', 'ar' => 'إدارة المستخدمين'],
                'slug' => 'users-managment',
                'icon' => 'group-line',
                'description' => ['en' => 'Managing student accounts, roles, and platform permissions', 'ar' => 'إدارة حسابات الطلبة وباقي المستخدمين'],
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Groups & Lessons', 'ar' => 'المجاميع والحصص'],
                'slug' => 'groups-lessons',
                'icon' => 'group-2-line',
                'description' => ['en' => 'Study groups, lesson scheduling, and teaching calendar management', 'ar' => 'المجاميع والدروس وتنظيم جدول التدريس'],
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Attendance & Absence', 'ar' => 'الحضور والغياب'],
                'slug' => 'attendance-absence',
                'icon' => 'calendar-check-line',
                'description' => ['en' => 'Attendance tracking, absence management, and attendance reporting', 'ar' => 'متابعة الحضور والغياب وتقارير الحضور'],
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Resources & Assignments', 'ar' => 'الموارد والواجبات'],
                'slug' => 'resources-assignments',
                'icon' => 'file-copy-2-line',
                'description' => ['en' => 'Study materials, assignments, and submission tracking', 'ar' => 'المواد الدراسية والواجبات ومتابعة التسليمات'],
                'order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Quizzes', 'ar' => 'الكويزات'],
                'slug' => 'quizzes',
                'icon' => 'brain-line',
                'description' => ['en' => 'Assessment creation, grading systems, and quiz result analysis', 'ar' => 'عمل الكويزات وطريقة التصحيح وتحليل النتايج'],
                'order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Zoom', 'ar' => 'زووم'],
                'slug' => 'zoom',
                'icon' => 'video-on-line',
                'description' => ['en' => 'Zoom integration, online class setup, and virtual classroom features', 'ar' => 'ربط الزووم وإعداد الفصول الافتراضية'],
                'order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ['en' => 'Finance', 'ar' => 'إدارة المالية'],
                'slug' => 'finance',
                'icon' => 'money-dollar-circle-line',
                'description' => ['en' => 'Income tracking, financial reporting, and teaching business finances', 'ar' => 'متابعة الأموال والتقارير المالية وإدارة الحسابات في المنصة'],
                'order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
