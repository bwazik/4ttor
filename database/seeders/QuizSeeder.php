<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Teacher;
use App\Models\Question;
use App\Traits\Truncatable;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    use Truncatable;

    public function run()
    {
        $this->truncateTables(['quizzes', 'quiz_group', 'questions', 'answers']);

        $faker = Faker::create();

        $quizNames = [
            ['en' => 'Midterm Quiz', 'ar' => 'اختبار منتصف الفصل'],
            ['en' => 'Final Quiz', 'ar' => 'اختبار النهائي'],
            ['en' => 'Chapter 1 Quiz', 'ar' => 'اختبار الفصل الأول'],
            ['en' => 'Chapter 2 Quiz', 'ar' => 'اختبار الفصل الثاني'],
            ['en' => 'Chapter 3 Quiz', 'ar' => 'اختبار الفصل الثالث'],
        ];

        $teachers = Teacher::with(['grades', 'groups'])->get();

        if ($teachers->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            $teacher = $teachers->random();
            if ($teacher->grades->isEmpty()) {
                continue;
            }

            $gradeId = $teacher->grades->pluck('id')->random();

            $groupIds = $teacher->groups->where('grade_id', $gradeId)->pluck('id')->toArray();
            if (empty($groupIds)) {
                continue;
            }

            $quizName = $quizNames[array_rand($quizNames)];

            $quiz = Quiz::create([
                'teacher_id' => $teacher->id,
                'grade_id' => $gradeId,
                'name' => $quizName,
                'duration' => $faker->numberBetween(30, 90),
                'start_time' => now(),
                'end_time' => now()->addHours(2),
            ]);


            $numElements = min(count($groupIds), rand(1, 3));
            $quiz->groups()->attach($faker->randomElements($groupIds, $numElements));

            $this->createQuestionsAndAnswers($quiz->id);
        }
    }

    private function createQuestionsAndAnswers($quiz_id)
    {
        $questions = [
            [
                'text' => [
                    'en' => 'What is the capital of France?',
                    'ar' => 'ما هي عاصمة فرنسا؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'London', 'ar' => 'لندن'], 'is_correct' => 0],
                    ['text' => ['en' => 'Berlin', 'ar' => 'برلين'], 'is_correct' => 0],
                    ['text' => ['en' => 'Paris', 'ar' => 'باريس'], 'is_correct' => 1],
                    ['text' => ['en' => 'Madrid', 'ar' => 'مدريد'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'What is 7 * 8?',
                    'ar' => 'ما هو 7 * 8؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => '42', 'ar' => '42'], 'is_correct' => 0],
                    ['text' => ['en' => '56', 'ar' => '56'], 'is_correct' => 1],
                    ['text' => ['en' => '49', 'ar' => '49'], 'is_correct' => 0],
                    ['text' => ['en' => '64', 'ar' => '64'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'Who wrote "Romeo and Juliet"?',
                    'ar' => 'من كتب "روميو وجولييت"؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Charles Dickens', 'ar' => 'تشارلز ديكنز'], 'is_correct' => 0],
                    ['text' => ['en' => 'Mark Twain', 'ar' => 'مارك توين'], 'is_correct' => 0],
                    ['text' => ['en' => 'Jane Austen', 'ar' => 'جين أوستن'], 'is_correct' => 0],
                    ['text' => ['en' => 'William Shakespeare', 'ar' => 'ويليام شكسبير'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => [
                    'en' => 'What planet is known as the Red Planet?',
                    'ar' => 'ما الكوكب المعروف باسم الكوكب الأحمر؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Venus', 'ar' => 'الزهرة'], 'is_correct' => 0],
                    ['text' => ['en' => 'Jupiter', 'ar' => 'المشتري'], 'is_correct' => 0],
                    ['text' => ['en' => 'Saturn', 'ar' => 'زحل'], 'is_correct' => 0],
                    ['text' => ['en' => 'Mars', 'ar' => 'المريخ'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => [
                    'en' => 'What is the largest ocean on Earth?',
                    'ar' => 'ما هو أكبر محيط على الأرض؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Pacific Ocean', 'ar' => 'محيط الهادئ'], 'is_correct' => 1],
                    ['text' => ['en' => 'Atlantic Ocean', 'ar' => 'المحيط الأطلسي'], 'is_correct' => 0],
                    ['text' => ['en' => 'Indian Ocean', 'ar' => 'المحيط الهندي'], 'is_correct' => 0],
                    ['text' => ['en' => 'Arctic Ocean', 'ar' => 'المحيط المتجمد الشمالي'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'In which year did World War II end?',
                    'ar' => 'في أي عام انتهت الحرب العالمية الثانية؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => '1940', 'ar' => '1940'], 'is_correct' => 0],
                    ['text' => ['en' => '1945', 'ar' => '1945'], 'is_correct' => 1],
                    ['text' => ['en' => '1939', 'ar' => '1939'], 'is_correct' => 0],
                    ['text' => ['en' => '1950', 'ar' => '1950'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'What is the chemical symbol for gold?',
                    'ar' => 'ما هو الرمز الكيميائي للذهب؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Ag', 'ar' => 'Ag'], 'is_correct' => 0],
                    ['text' => ['en' => 'Pb', 'ar' => 'Pb'], 'is_correct' => 0],
                    ['text' => ['en' => 'Au', 'ar' => 'Au'], 'is_correct' => 1],
                    ['text' => ['en' => 'Fe', 'ar' => 'Fe'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'Which planet is known as the "Giant Planet"?',
                    'ar' => 'أي كوكب يعرف باسم "الكوكب العملاق"؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Saturn', 'ar' => 'زحل'], 'is_correct' => 0],
                    ['text' => ['en' => 'Neptune', 'ar' => 'نبتون'], 'is_correct' => 0],
                    ['text' => ['en' => 'Jupiter', 'ar' => 'المشتري'], 'is_correct' => 1],
                    ['text' => ['en' => 'Uranus', 'ar' => 'أورانوس'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'What is the hardest natural substance on Earth?',
                    'ar' => 'ما هي أقسى مادة طبيعية على الأرض؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Gold', 'ar' => 'الذهب'], 'is_correct' => 0],
                    ['text' => ['en' => 'Iron', 'ar' => 'الحديد'], 'is_correct' => 0],
                    ['text' => ['en' => 'Quartz', 'ar' => 'الكوارز'], 'is_correct' => 0],
                    ['text' => ['en' => 'Diamond', 'ar' => 'الماس'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => [
                    'en' => 'Who painted the Mona Lisa?',
                    'ar' => 'من رسم لوحة الموناليزا؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Vincent van Gogh', 'ar' => 'فينسنت فان جوخ'], 'is_correct' => 0],
                    ['text' => ['en' => 'Pablo Picasso', 'ar' => 'بابلو بيكاسو'], 'is_correct' => 0],
                    ['text' => ['en' => 'Leonardo da Vinci', 'ar' => 'ليوناردو دا فينشي'], 'is_correct' => 1],
                    ['text' => ['en' => 'Claude Monet', 'ar' => 'كلود مونيه'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'What is the square root of 144?',
                    'ar' => 'ما هو الجذر التربيعي لـ 144؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => '12', 'ar' => '12'], 'is_correct' => 1],
                    ['text' => ['en' => '10', 'ar' => '10'], 'is_correct' => 0],
                    ['text' => ['en' => '14', 'ar' => '14'], 'is_correct' => 0],
                    ['text' => ['en' => '16', 'ar' => '16'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => [
                    'en' => 'What is the largest country in the world?',
                    'ar' => 'ما هي أكبر دولة في العالم؟',
                ],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Canada', 'ar' => 'كندا'], 'is_correct' => 0],
                    ['text' => ['en' => 'China', 'ar' => 'الصين'], 'is_correct' => 0],
                    ['text' => ['en' => 'United States', 'ar' => 'الولايات المتحدة'], 'is_correct' => 0],
                    ['text' => ['en' => 'Russia', 'ar' => 'روسيا'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => ['en' => 'What is the main ingredient in guacamole?', 'ar' => 'ما هو المكون الرئيسي في الجواكامولي؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Avocado', 'ar' => 'أفوكادو'], 'is_correct' => 1],
                    ['text' => ['en' => 'Tomato', 'ar' => 'طماطم'], 'is_correct' => 0],
                    ['text' => ['en' => 'Onion', 'ar' => 'بصل'], 'is_correct' => 0],
                    ['text' => ['en' => 'Pepper', 'ar' => 'فلفل'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'What is the smallest prime number?', 'ar' => 'ما هو أصغر عدد أولي؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => '1', 'ar' => '1'], 'is_correct' => 0],
                    ['text' => ['en' => '2', 'ar' => '2'], 'is_correct' => 1],
                    ['text' => ['en' => '3', 'ar' => '3'], 'is_correct' => 0],
                    ['text' => ['en' => '5', 'ar' => '5'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'Which gas is most abundant in the Earth’s atmosphere?', 'ar' => 'ما الغاز الأكثر وفرة في الغلاف الجوي للأرض؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Oxygen', 'ar' => 'أكسجين'], 'is_correct' => 0],
                    ['text' => ['en' => 'Carbon Dioxide', 'ar' => 'ثاني أكسيد الكربون'], 'is_correct' => 0],
                    ['text' => ['en' => 'Argon', 'ar' => 'أرجون'], 'is_correct' => 0],
                    ['text' => ['en' => 'Nitrogen', 'ar' => 'نيتروجين'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => ['en' => 'What is the main language spoken in Brazil?', 'ar' => 'ما هي اللغة الرئيسية المستخدمة في البرازيل؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Spanish', 'ar' => 'الإسبانية'], 'is_correct' => 0],
                    ['text' => ['en' => 'Portuguese', 'ar' => 'البرتغالية'], 'is_correct' => 1],
                    ['text' => ['en' => 'English', 'ar' => 'الإنجليزية'], 'is_correct' => 0],
                    ['text' => ['en' => 'French', 'ar' => 'الفرنسية'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'What is the longest river in the world?', 'ar' => 'ما هو أطول نهر في العالم؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Nile', 'ar' => 'النيل'], 'is_correct' => 1],
                    ['text' => ['en' => 'Amazon', 'ar' => 'الأمازون'], 'is_correct' => 0],
                    ['text' => ['en' => 'Yangtze', 'ar' => 'اليانغتسي'], 'is_correct' => 0],
                    ['text' => ['en' => 'Mississippi', 'ar' => 'الميسيسيبي'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'What is the currency of Japan?', 'ar' => 'ما هي عملة اليابان؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Won', 'ar' => 'وون'], 'is_correct' => 0],
                    ['text' => ['en' => 'Dollar', 'ar' => 'دولار'], 'is_correct' => 0],
                    ['text' => ['en' => 'Yen', 'ar' => 'ين'], 'is_correct' => 1],
                    ['text' => ['en' => 'Euro', 'ar' => 'يورو'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'What is the boiling point of water?', 'ar' => 'ما هي درجة غليان الماء؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => '90°C', 'ar' => '90°م'], 'is_correct' => 0],
                    ['text' => ['en' => '80°C', 'ar' => '80°م'], 'is_correct' => 0],
                    ['text' => ['en' => '110°C', 'ar' => '110°م'], 'is_correct' => 0],
                    ['text' => ['en' => '100°C', 'ar' => '100°م'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => ['en' => 'What is the capital city of Australia?', 'ar' => 'ما هي عاصمة أستراليا؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Sydney', 'ar' => 'سيدني'], 'is_correct' => 0],
                    ['text' => ['en' => 'Melbourne', 'ar' => 'ملبورن'], 'is_correct' => 0],
                    ['text' => ['en' => 'Canberra', 'ar' => 'كانبرا'], 'is_correct' => 1],
                    ['text' => ['en' => 'Brisbane', 'ar' => 'برسبان'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'Who discovered penicillin?', 'ar' => 'من اكتشف البنسلين؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Alexander Fleming', 'ar' => 'ألكسندر فليمنغ'], 'is_correct' => 1],
                    ['text' => ['en' => 'Marie Curie', 'ar' => 'ماري كوري'], 'is_correct' => 0],
                    ['text' => ['en' => 'Isaac Newton', 'ar' => 'إسحاق نيوتن'], 'is_correct' => 0],
                    ['text' => ['en' => 'Louis Pasteur', 'ar' => 'لويس باستور'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'Which element has the chemical symbol O?', 'ar' => 'أي عنصر له الرمز الكيميائي O؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Gold', 'ar' => 'ذهب'], 'is_correct' => 0],
                    ['text' => ['en' => 'Silver', 'ar' => 'فضة'], 'is_correct' => 0],
                    ['text' => ['en' => 'Osmium', 'ar' => 'أوزميوم'], 'is_correct' => 0],
                    ['text' => ['en' => 'Oxygen', 'ar' => 'أكسجين'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => ['en' => 'What is the capital of Italy?', 'ar' => 'ما هي عاصمة إيطاليا؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Venice', 'ar' => 'البندقية'], 'is_correct' => 0],
                    ['text' => ['en' => 'Rome', 'ar' => 'روما'], 'is_correct' => 1],
                    ['text' => ['en' => 'Milan', 'ar' => 'ميلانو'], 'is_correct' => 0],
                    ['text' => ['en' => 'Florence', 'ar' => 'فلورنسا'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'What is the chemical formula for water?', 'ar' => 'ما هي الصيغة الكيميائية للماء؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'CO2', 'ar' => 'CO2'], 'is_correct' => 0],
                    ['text' => ['en' => 'O2', 'ar' => 'O2'], 'is_correct' => 0],
                    ['text' => ['en' => 'H2O', 'ar' => 'H2O'], 'is_correct' => 1],
                    ['text' => ['en' => 'H2O2', 'ar' => 'H2O2'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'What is the fastest land animal?', 'ar' => 'ما هو أسرع حيوان بري؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Lion', 'ar' => 'أسد'], 'is_correct' => 0],
                    ['text' => ['en' => 'Cheetah', 'ar' => 'فهد'], 'is_correct' => 1],
                    ['text' => ['en' => 'Tiger', 'ar' => 'نمر'], 'is_correct' => 0],
                    ['text' => ['en' => 'Gazelle', 'ar' => 'غزال'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'Which planet is known for its rings?', 'ar' => 'أي كوكب معروف بحلقاته؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Saturn', 'ar' => 'زحل'], 'is_correct' => 1],
                    ['text' => ['en' => 'Jupiter', 'ar' => 'المشتري'], 'is_correct' => 0],
                    ['text' => ['en' => 'Mars', 'ar' => 'المريخ'], 'is_correct' => 0],
                    ['text' => ['en' => 'Neptune', 'ar' => 'نبتون'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'How many continents are there?', 'ar' => 'كم عدد القارات الموجودة؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => '5', 'ar' => '5'], 'is_correct' => 0],
                    ['text' => ['en' => '6', 'ar' => '6'], 'is_correct' => 0],
                    ['text' => ['en' => '8', 'ar' => '8'], 'is_correct' => 0],
                    ['text' => ['en' => '7', 'ar' => '7'], 'is_correct' => 1],
                ],
            ],
            [
                'text' => ['en' => 'Who was the first President of the United States?', 'ar' => 'من كان أول رئيس للولايات المتحدة؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Abraham Lincoln', 'ar' => 'أبراهام لينكولن'], 'is_correct' => 0],
                    ['text' => ['en' => 'Thomas Jefferson', 'ar' => 'توماس جيفرسون'], 'is_correct' => 0],
                    ['text' => ['en' => 'George Washington', 'ar' => 'جورج واشنطن'], 'is_correct' => 1],
                    ['text' => ['en' => 'John Adams', 'ar' => 'جون آدامز'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'What is the primary ingredient in bread?', 'ar' => 'ما هو المكون الرئيسي في الخبز؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Sugar', 'ar' => 'سكر'], 'is_correct' => 0],
                    ['text' => ['en' => 'Flour', 'ar' => 'دقيق'], 'is_correct' => 1],
                    ['text' => ['en' => 'Salt', 'ar' => 'ملح'], 'is_correct' => 0],
                    ['text' => ['en' => 'Yeast', 'ar' => 'خميرة'], 'is_correct' => 0],
                ],
            ],
            [
                'text' => ['en' => 'In which sport is the term "home run" used?', 'ar' => 'في أي رياضة يُستخدم مصطلح "هوم رن"؟'],
                'degree' => 5,
                'answers' => [
                    ['text' => ['en' => 'Baseball', 'ar' => 'بيسبول'], 'is_correct' => 1],
                    ['text' => ['en' => 'Football', 'ar' => 'كرة القدم'], 'is_correct' => 0],
                    ['text' => ['en' => 'Basketball', 'ar' => 'كرة السلة'], 'is_correct' => 0],
                    ['text' => ['en' => 'Cricket', 'ar' => 'كريكيت'], 'is_correct' => 0],
                ],
            ],
        ];


        foreach ($questions as $q) {
            $question = Question::create([
                'quiz_id' => $quiz_id,
                'question_text' => $q['text'],
            ]);

            foreach ($q['answers'] as $answer) {
                Answer::create([
                    'answer_text' => $answer['text'],
                    'question_id' => $question->id,
                    'is_correct' => $answer['is_correct'],
                    'score' => $answer['is_correct'] ? 5 : 0,
                ]);
            }
        }
    }
}
