<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\Truncatable;
use App\Models\Faq;
use App\Models\Category;

class FaqSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['faqs']);

        $faqs = [
            // Payment & Fees (Students, Parents)
            [
                'category_id' => Category::where('slug', 'payment-fees')->first()->id,
                'audience' => 6, // Students & Parents
                'question' => ['en' => 'How do I pay my course fees?', 'ar' => 'إزاي أدفع مصاريف الكورس؟'],
                'answer' => ['en' => 'Log in, go to Payment & Fees, select your course, and pay via Visa or PayPal.', 'ar' => 'سجل دخولك، روح على الدفع والمصاريف، اختار الكورس، وادفع بفيزا أو باي بال.'],
                'is_active' => true,
                'is_at_landing' => true,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'payment-fees')->first()->id,
                'audience' => 6,
                'question' => ['en' => 'Can I get a payment plan?', 'ar' => 'في تقسيط للمصاريف؟'],
                'answer' => ['en' => 'Yes, contact support to set up a monthly payment plan.', 'ar' => 'أيوه، كلم الدعم عشان يعملولك خطة تقسيط شهري.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Payment & Subscriptions (Teachers)
            [
                'category_id' => Category::where('slug', 'payment-subscriptions')->first()->id,
                'audience' => 1, // Teachers
                'question' => ['en' => 'How do I choose a subscription plan?', 'ar' => 'إزاي أختار خطة اشتراك؟'],
                'answer' => ['en' => 'Go to Payment & Subscriptions, compare plans, and select one.', 'ar' => 'روح على الدفع والاشتراكات، قارن الخطط، واختار واحدة.'],
                'is_active' => true,
                'is_at_landing' => true,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'payment-subscriptions')->first()->id,
                'audience' => 1, // Teachers
                'question' => ['en' => 'When do I get paid?', 'ar' => 'الفلوس بتدخل الحساب إمتى؟'],
                'answer' => ['en' => 'Payments are processed monthly on the 5th.', 'ar' => 'الفلوس بتتحول كل شهر يوم 5.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Cancellation & Return (Teachers, Students, Parents)
            [
                'category_id' => Category::where('slug', 'cancellation-return')->first()->id,
                'audience' => 7, // All users
                'question' => ['en' => 'Can I cancel my subscription?', 'ar' => 'أقدر ألغي الاشتراك؟'],
                'answer' => ['en' => 'Yes, cancel anytime via your account settings. Refunds depend on terms.', 'ar' => 'أيوه، ألغي في أي وقت من إعدادات الحساب. الاسترداد بيعتمد على الشروط.'],
                'is_active' => true,
                'is_at_landing' => true,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'cancellation-return')->first()->id,
                'audience' => 7, // All users
                'question' => ['en' => 'How do I request a refund?', 'ar' => 'إزاي أطلب استرداد فلوس؟'],
                'answer' => ['en' => 'Submit a refund request via the support portal within 7 days.', 'ar' => 'قدم طلب استرداد من خلال بوابة الدعم خلال 7 أيام.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Security (All)
            [
                'category_id' => Category::where('slug', 'security')->first()->id,
                'audience' => 7, // All users
                'question' => ['en' => 'How do I enable two-factor authentication?', 'ar' => 'إزاي أفعل المصادقة الثنائية؟'],
                'answer' => ['en' => 'Go to Security settings and enable 2FA with your phone number.', 'ar' => 'روح على إعدادات الأمان وفعل المصادقة الثنائية برقم موبايلك.'],
                'is_active' => true,
                'is_at_landing' => true,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'security')->first()->id,
                'audience' => 7, // All users
                'question' => ['en' => 'Is my data secure?', 'ar' => 'بياناتي في أمان؟'],
                'answer' => ['en' => 'Yes, we use encryption and comply with privacy laws.', 'ar' => 'أيوه، بنستخدم تشفير وبنحترم قوانين الخصوصية.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Personal Data (All)
            [
                'category_id' => Category::where('slug', 'personal-data')->first()->id,
                'audience' => 7, // All users
                'question' => ['en' => 'How do I update my profile?', 'ar' => 'إزاي أعدل البروفايل بتاعي؟'],
                'answer' => ['en' => 'Go to Personal Data and edit your profile details.', 'ar' => 'روح على البيانات الشخصية وعدل تفاصيل البروفايل.'],
                'is_active' => true,
                'is_at_landing' => true,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'personal-data')->first()->id,
                'audience' => 7, // All users
                'question' => ['en' => 'Can I change my email?', 'ar' => 'أقدر أغير الإيميل؟'],
                'answer' => ['en' => 'Yes, update it in Personal Data after verifying your identity.', 'ar' => 'أيوه، غيره في البيانات الشخصية بعد ما تثبت هويتك.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Users Management (Teachers, Assistants)
            [
                'category_id' => Category::where('slug', 'users-managment')->first()->id,
                'audience' => 6, // Teachers & Assistants
                'question' => ['en' => 'How do I add a student?', 'ar' => 'إزاي أضيف طالب؟'],
                'answer' => ['en' => 'Go to Users Management, click Add User, and enter student details.', 'ar' => 'روح على إدارة المستخدمين، اضغط إضافة مستخدم، ودخل بيانات الطالب.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'users-managment')->first()->id,
                'audience' => 6, // Teachers & Assistants
                'question' => ['en' => 'How do I assign roles?', 'ar' => 'إزاي أعين أدوار؟'],
                'answer' => ['en' => 'In Users Management, edit a user and select their role.', 'ar' => 'في إدارة المستخدمين، عدل المستخدم واختار دوره.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Groups & Lessons (Teachers, Students)
            [
                'category_id' => Category::where('slug', 'groups-lessons')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I schedule a lesson?', 'ar' => 'إزاي أحدد موعد درس؟'],
                'answer' => ['en' => 'Go to Groups & Lessons, select a group, and add a lesson slot.', 'ar' => 'روح على المجاميع والحصص، اختار مجموعة، وأضف موعد درس.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'groups-lessons')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'Can I join multiple groups?', 'ar' => 'أقدر أنضم لأكتر من مجموعة؟'],
                'answer' => ['en' => 'Yes, students can join multiple study groups.', 'ar' => 'أيوه، الطلبة يقدروا ينضموا لأكتر من مجموعة دراسية.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Attendance & Absence (Teachers, Students)
            [
                'category_id' => Category::where('slug', 'attendance-absence')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I mark attendance?', 'ar' => 'إزاي أسجل الحضور؟'],
                'answer' => ['en' => 'In Attendance & Absence, select a lesson and mark students present.', 'ar' => 'في الحضور والغياب، اختار الدرس وسجل الطلبة الحاضرين.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'attendance-absence')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I report an absence?', 'ar' => 'إزاي أبلغ عن غياب؟'],
                'answer' => ['en' => 'Students can report absences via Attendance & Absence.', 'ar' => 'الطلبة يقدروا يبلغوا عن الغياب من الحضور والغياب.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Resources & Assignments (Teachers, Students)
            [
                'category_id' => Category::where('slug', 'resources-assignments')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I upload an assignment?', 'ar' => 'إزاي أرفع واجب؟'],
                'answer' => ['en' => 'Go to Resources & Assignments, select a course, and upload your file.', 'ar' => 'روح على الموارد والواجبات، اختار الكورس، وارفع الملف.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'resources-assignments')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'Where do I find study materials?', 'ar' => 'فين ألاقي المواد الدراسية؟'],
                'answer' => ['en' => 'Check Resources & Assignments for teacher-uploaded materials.', 'ar' => 'دور في الموارد والواجبات على المواد اللي رفعها المدرس.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Quizzes (Teachers, Students)
            [
                'category_id' => Category::where('slug', 'quizzes')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I create a quiz?', 'ar' => 'إزاي أعمل كويز؟'],
                'answer' => ['en' => 'In Quizzes, click Create Quiz and add questions.', 'ar' => 'في الكويزات، اضغط إنشاء كويز وأضف الأسئلة.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'quizzes')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I view my quiz results?', 'ar' => 'إزاي أشوف نتايج الكويز؟'],
                'answer' => ['en' => 'Go to Quizzes and select View Results.', 'ar' => 'روح على الكويزات واختار عرض النتايج.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Zoom (Teachers, Students)
            [
                'category_id' => Category::where('slug', 'zoom')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I set up a Zoom class?', 'ar' => 'إزاي أعمل درس على زووم؟'],
                'answer' => ['en' => 'In Zoom, create a meeting link and share it with students.', 'ar' => 'في زووم، اعمل لينك اجتماع وشاركه مع الطلبة.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'zoom')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'What if Zoom doesn’t work?', 'ar' => 'لو زووم مش شغال أعمل إيه؟'],
                'answer' => ['en' => 'Check your internet or contact support.', 'ar' => 'تأكد من الإنترنت أو كلم الدعم.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],

            // Finance (Teachers)
            [
                'category_id' => Category::where('slug', 'finance')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I track my earnings?', 'ar' => 'إزاي أتابع أرباحي؟'],
                'answer' => ['en' => 'Go to Finance to view your earning reports.', 'ar' => 'روح على إدارة المالية وشوف تقارير أرباحك.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 1,
            ],
            [
                'category_id' => Category::where('slug', 'finance')->first()->id,
                'audience' => 1,
                'question' => ['en' => 'How do I generate a financial report?', 'ar' => 'إزاي أعمل تقرير مالي؟'],
                'answer' => ['en' => 'In Finance, select Report and choose a date range.', 'ar' => 'في إدارة المالية، اختار تقرير وحدد المدة.'],
                'is_active' => true,
                'is_at_landing' => false,
                'order' => 2,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}