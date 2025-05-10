<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Models\Module;
use App\Domain\Models\Skill;
use App\Domain\Models\Lesson;
use App\Domain\Models\Exercise;

class DbtContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the four core DBT modules
        $mindfulnessModule = Module::create([
            'name' => 'Mindfulness',
            'slug' => 'mindfulness',
            'description' => 'The foundation of all DBT skills. Mindfulness helps you observe, describe, and participate in the present moment without judgment.',
            'icon' => 'meditation',
            'color_code' => '#4CAF50', // Green
            'order' => 1,
            'is_active' => true,
        ]);

        $distressToleranceModule = Module::create([
            'name' => 'Distress Tolerance',
            'slug' => 'distress-tolerance',
            'description' => 'Skills to help you cope with crisis situations and accept reality as it is in the moment.',
            'icon' => 'shield',
            'color_code' => '#2196F3', // Blue
            'order' => 2,
            'is_active' => true,
        ]);

        $emotionRegulationModule = Module::create([
            'name' => 'Emotion Regulation',
            'slug' => 'emotion-regulation',
            'description' => 'Skills to help you understand, name, and manage your emotions more effectively.',
            'icon' => 'heart',
            'color_code' => '#F44336', // Red
            'order' => 3,
            'is_active' => true,
        ]);

        $interpersonalModule = Module::create([
            'name' => 'Interpersonal Effectiveness',
            'slug' => 'interpersonal-effectiveness',
            'description' => 'Skills to help you communicate effectively, maintain relationships, and respect yourself.',
            'icon' => 'people',
            'color_code' => '#9C27B0', // Purple
            'order' => 4,
            'is_active' => true,
        ]);

        // === MINDFULNESS MODULE SKILLS AND LESSONS ===
        
        // Skill: Wise Mind
        $wiseMindSkill = Skill::create([
            'module_id' => $mindfulnessModule->id,
            'name' => 'Wise Mind',
            'slug' => 'wise-mind',
            'description' => 'Balancing emotional mind and reasonable mind to access wisdom and intuition.',
            'icon' => 'balance-scale',
            'order' => 1,
            'is_active' => true,
            'is_premium' => false,
            'prerequisites' => [],
        ]);

        // Lesson 1: Introduction to Wise Mind
        $wiseMindLesson1 = Lesson::create([
            'skill_id' => $wiseMindSkill->id,
            'name' => 'Introduction to Wise Mind',
            'slug' => 'intro-to-wise-mind',
            'description' => 'Learn about the three states of mind: emotional mind, reasonable mind, and wise mind.',
            'content' => 'Wise Mind is the integration of emotional mind and reasonable mind. It\'s a state of mind where you can access your intuition and inner wisdom.

Emotional Mind: When emotions control your thinking and behavior. You might feel overwhelmed or act impulsively.

Reasonable Mind: When logic, facts, and rational thinking guide your decisions. You might focus solely on facts without considering feelings.

Wise Mind: The middle path that integrates both emotional and reasonable mind. It acknowledges emotions while applying reason to guide actions.',
            'order' => 1,
            'duration_minutes' => 10,
            'xp_reward' => 15,
            'is_active' => true,
            'is_premium' => false,
        ]);

        // Exercises for Wise Mind Lesson 1
        Exercise::create([
            'lesson_id' => $wiseMindLesson1->id,
            'title' => 'Identify the State of Mind',
            'description' => 'Read each scenario and identify which state of mind is being described.',
            'type' => Exercise::TYPE_MULTIPLE_CHOICE,
            'content' => 'When Sarah received criticism at work, she immediately felt hurt and sent an angry email to her boss without thinking about the consequences.',
            'options' => ['Emotional Mind', 'Reasonable Mind', 'Wise Mind'],
            'correct_answer' => ['Emotional Mind'],
            'order' => 1,
            'difficulty' => 1,
            'xp_reward' => 5,
            'is_active' => true,
        ]);

        Exercise::create([
            'lesson_id' => $wiseMindLesson1->id,
            'title' => 'Accessing Wise Mind',
            'description' => 'Reflect on a recent decision you made. Which state of mind were you in?',
            'type' => Exercise::TYPE_REFLECTION,
            'content' => 'Think about a decision you made recently. Describe the situation, how you felt, what you thought, and the action you took. Which state of mind were you in? How might the outcome have been different if you were in Wise Mind?',
            'options' => null,
            'correct_answer' => null,
            'order' => 2,
            'difficulty' => 2,
            'xp_reward' => 10,
            'is_active' => true,
        ]);

        // Skill: What Skills
        $whatSkillsSkill = Skill::create([
            'module_id' => $mindfulnessModule->id,
            'name' => 'What Skills',
            'slug' => 'what-skills',
            'description' => 'Observe, describe, and participate in the present moment.',
            'icon' => 'eye',
            'order' => 2,
            'is_active' => true,
            'is_premium' => false,
            'prerequisites' => [$wiseMindSkill->id],
        ]);

        // === DISTRESS TOLERANCE MODULE SKILLS AND LESSONS ===
        
        // Skill: TIPP Skills
        $tippSkill = Skill::create([
            'module_id' => $distressToleranceModule->id,
            'name' => 'TIPP Skills',
            'slug' => 'tipp-skills',
            'description' => 'Temperature, Intense exercise, Paced breathing, and Paired muscle relaxation to reduce distress quickly.',
            'icon' => 'heartbeat',
            'order' => 1,
            'is_active' => true,
            'is_premium' => false,
            'prerequisites' => [],
        ]);

        // Lesson 1: Introduction to TIPP
        $tippLesson1 = Lesson::create([
            'skill_id' => $tippSkill->id,
            'name' => 'Introduction to TIPP Skills',
            'slug' => 'intro-to-tipp',
            'description' => 'Learn how to quickly change your body chemistry to reduce overwhelming emotions.',
            'content' => 'When you\'re in high emotional distress, you need fast-acting skills to bring your emotional arousal down. TIPP skills work by directly changing your body chemistry.

T - Temperature: Cold water or ice on your face triggers the diving reflex, slowing your heart rate.
I - Intense exercise: Vigorous physical activity for at least 20 minutes helps reduce stress hormones.
P - Paced breathing: Slow, deep breathing (4-5 breaths per minute) activates your parasympathetic nervous system.
P - Paired muscle relaxation: Tensing and relaxing muscle groups while breathing out helps release physical tension.',
            'order' => 1,
            'duration_minutes' => 15,
            'xp_reward' => 20,
            'is_active' => true,
            'is_premium' => false,
        ]);

        // Exercises for TIPP Lesson 1
        Exercise::create([
            'lesson_id' => $tippLesson1->id,
            'title' => 'Paced Breathing Practice',
            'description' => 'Practice slow, deep breathing to activate your parasympathetic nervous system.',
            'type' => Exercise::TYPE_PRACTICE,
            'content' => 'Find a comfortable position. Breathe in slowly through your nose for 4 counts. Hold for 2 counts. Breathe out slowly through your mouth for 6 counts. Try to do this for at least 2 minutes.',
            'options' => null,
            'correct_answer' => null,
            'order' => 1,
            'difficulty' => 1,
            'xp_reward' => 5,
            'is_active' => true,
        ]);

        // === EMOTION REGULATION MODULE SKILLS AND LESSONS ===
        
        // Skill: Identifying Emotions
        $identifyingEmotionsSkill = Skill::create([
            'module_id' => $emotionRegulationModule->id,
            'name' => 'Identifying Emotions',
            'slug' => 'identifying-emotions',
            'description' => 'Learning to recognize and name emotions and their functions.',
            'icon' => 'search',
            'order' => 1,
            'is_active' => true,
            'is_premium' => false,
            'prerequisites' => [],
        ]);

        // === INTERPERSONAL EFFECTIVENESS MODULE SKILLS AND LESSONS ===
        
        // Skill: DEAR MAN
        $dearManSkill = Skill::create([
            'module_id' => $interpersonalModule->id,
            'name' => 'DEAR MAN',
            'slug' => 'dear-man',
            'description' => 'A skill for making requests or saying no effectively.',
            'icon' => 'comments',
            'order' => 1,
            'is_active' => true,
            'is_premium' => false,
            'prerequisites' => [],
        ]);

        // Lesson 1: Introduction to DEAR MAN
        $dearManLesson1 = Lesson::create([
            'skill_id' => $dearManSkill->id,
            'name' => 'Introduction to DEAR MAN',
            'slug' => 'intro-to-dear-man',
            'description' => 'Learn the DEAR MAN technique for effective communication.',
            'content' => 'DEAR MAN is an acronym that helps you remember the steps for effectively asking for what you want or saying no to requests.

D - Describe the situation objectively without judgments.
E - Express your feelings and opinions clearly.
A - Assert yourself by asking for what you want or saying no directly.
R - Reinforce the person ahead of time by explaining the positive effects of getting what you want.

M - Mindful of the objective and stay focused.
A - Appear confident with eye contact, a steady voice, and an upright posture.
N - Negotiate if necessary and be willing to give to get.',
            'order' => 1,
            'duration_minutes' => 20,
            'xp_reward' => 25,
            'is_active' => true,
            'is_premium' => false,
        ]);

        // Exercises for DEAR MAN Lesson 1
        Exercise::create([
            'lesson_id' => $dearManLesson1->id,
            'title' => 'DEAR MAN Components',
            'description' => 'Match each component of DEAR MAN with its correct description.',
            'type' => Exercise::TYPE_MATCHING,
            'content' => 'Match each component of DEAR MAN with its correct description.',
            'options' => [
                ['D - Describe', 'Explain the situation without judgments'],
                ['E - Express', 'Share your feelings and opinions'],
                ['A - Assert', 'Ask for what you want directly'],
                ['R - Reinforce', 'Explain the positive effects'],
                ['M - Mindful', 'Stay focused on your objective'],
                ['A - Appear confident', 'Use confident body language'],
                ['N - Negotiate', 'Be willing to give to get']
            ],
            'correct_answer' => [0, 1, 2, 3, 4, 5, 6],
            'order' => 1,
            'difficulty' => 2,
            'xp_reward' => 10,
            'is_active' => true,
        ]);

        Exercise::create([
            'lesson_id' => $dearManLesson1->id,
            'title' => 'Practice DEAR MAN',
            'description' => 'Write a DEAR MAN script for a situation in your life.',
            'type' => Exercise::TYPE_TEXT_INPUT,
            'content' => 'Think of a situation where you need to ask for something or say no to a request. Write a script using the DEAR MAN technique. Include each component: Describe, Express, Assert, Reinforce, stay Mindful, Appear confident, and be willing to Negotiate.',
            'options' => null,
            'correct_answer' => ['script'],
            'order' => 2,
            'difficulty' => 3,
            'xp_reward' => 15,
            'is_active' => true,
        ]);
    }
}
