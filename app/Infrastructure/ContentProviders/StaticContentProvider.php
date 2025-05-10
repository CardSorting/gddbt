<?php

namespace App\Infrastructure\ContentProviders;

class StaticContentProvider implements ContentProviderInterface
{
    /**
     * Get modules data.
     * 
     * @return array
     */
    public function getModulesData(): array
    {
        return [
            [
                'name' => 'Mindfulness',
                'slug' => 'mindfulness',
                'description' => 'The foundation of all DBT skills. Mindfulness helps you observe, describe, and participate in the present moment without judgment.',
                'icon' => 'meditation',
                'color_code' => '#4CAF50',
                'order' => 1,
                'is_active' => true,
            ]
        ];
    }

    /**
     * Get skills data.
     * 
     * @return array
     */
    public function getSkillsData(): array
    {
        return [
            [
                'module_slug' => 'mindfulness',
                'name' => 'Wise Mind',
                'slug' => 'wise-mind',
                'description' => 'Balancing emotional mind and reasonable mind to access wisdom and intuition.',
                'icon' => 'balance-scale',
                'order' => 1,
                'is_active' => true,
                'is_premium' => false,
                'prerequisites' => [],
            ]
        ];
    }

    /**
     * Get lessons data.
     * 
     * @return array
     */
    public function getLessonsData(): array
    {
        return [
            [
                'skill_slug' => 'wise-mind',
                'name' => 'Introduction to Wise Mind',
                'slug' => 'intro-to-wise-mind',
                'description' => 'Learn about the three states of mind: emotional mind, reasonable mind, and wise mind.',
                'content' => $this->getLessonContent('intro-to-wise-mind'),
                'order' => 1,
                'duration_minutes' => 15,
                'xp_reward' => 20,
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'skill_slug' => 'wise-mind',
                'name' => 'Practicing Wise Mind',
                'slug' => 'practicing-wise-mind',
                'description' => 'Learn practical techniques to access and stay in Wise Mind during daily situations.',
                'content' => $this->getLessonContent('practicing-wise-mind'),
                'order' => 2,
                'duration_minutes' => 20,
                'xp_reward' => 25,
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'skill_slug' => 'wise-mind',
                'name' => 'The Stone in the Pond Meditation',
                'slug' => 'stone-in-pond-meditation',
                'description' => 'A guided meditation to help you experience the state of Wise Mind through visualization.',
                'content' => $this->getLessonContent('stone-in-pond-meditation'),
                'order' => 3,
                'duration_minutes' => 15,
                'xp_reward' => 20,
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'skill_slug' => 'wise-mind',
                'name' => 'Wise Mind in Relationships',
                'slug' => 'wise-mind-relationships',
                'description' => 'Apply Wise Mind concepts to improve communication and decision-making in relationships.',
                'content' => $this->getLessonContent('wise-mind-relationships'),
                'order' => 4,
                'duration_minutes' => 20,
                'xp_reward' => 25,
                'is_active' => true,
                'is_premium' => false,
            ]
        ];
    }

    /**
     * Get exercises data.
     * 
     * @return array
     */
    public function getExercisesData(): array
    {
        return [
            // Introduction to Wise Mind exercises
            [
                'lesson_slug' => 'intro-to-wise-mind',
                'title' => 'Identify the State of Mind',
                'description' => 'Read each scenario and identify which state of mind is being described.',
                'type' => 'multiple_choice',
                'content' => 'When Sarah received criticism at work, she immediately felt hurt and sent an angry email to her boss without thinking about the consequences.',
                'options' => ['Emotional Mind', 'Reasonable Mind', 'Wise Mind'],
                'correct_answer' => ['Emotional Mind'],
                'order' => 1,
                'difficulty' => 1,
                'xp_reward' => 5,
                'is_active' => true,
            ],
            [
                'lesson_slug' => 'intro-to-wise-mind',
                'title' => 'Reasonable Mind Scenario',
                'description' => 'Identify which state of mind is most prominent in this scenario.',
                'type' => 'multiple_choice',
                'content' => 'John needed to make a decision about changing jobs. He made a detailed spreadsheet comparing salary, benefits, commute time, and growth opportunities, but ignored how he felt about the company culture or whether he would enjoy the work.',
                'options' => ['Emotional Mind', 'Reasonable Mind', 'Wise Mind'],
                'correct_answer' => ['Reasonable Mind'],
                'order' => 2,
                'difficulty' => 1,
                'xp_reward' => 5,
                'is_active' => true,
            ],
            [
                'lesson_slug' => 'intro-to-wise-mind',
                'title' => 'Wise Mind Recognition',
                'description' => 'Identify the state of mind represented in this scenario.',
                'type' => 'multiple_choice',
                'content' => 'Maria was offered a promotion that would require moving to another city. She felt excited about the opportunity but also sad about leaving friends. She took time to recognize these feelings, researched the new location, talked with family, and reflected on her long-term goals before making her decision.',
                'options' => ['Emotional Mind', 'Reasonable Mind', 'Wise Mind'],
                'correct_answer' => ['Wise Mind'],
                'order' => 3,
                'difficulty' => 1,
                'xp_reward' => 5,
                'is_active' => true,
            ],
            
            // Practicing Wise Mind exercises
            [
                'lesson_slug' => 'practicing-wise-mind',
                'title' => 'ACCEPTS Activity Planning',
                'description' => 'Create a personal plan using the ACCEPTS framework.',
                'type' => 'free_response',
                'content' => 'Choose one element from the ACCEPTS framework (Activities, Contributing, Comparisons, Emotions, Pushing Away, Thoughts, or Sensations) and create a specific plan for how you will use this technique in a challenging situation. Be as specific as possible.',
                'options' => null,
                'correct_answer' => null,
                'order' => 1,
                'difficulty' => 2,
                'xp_reward' => 10,
                'is_active' => true,
            ],
            [
                'lesson_slug' => 'practicing-wise-mind',
                'title' => 'Breathing Exercise Reflection',
                'description' => 'Practice the Wise Mind breathing technique and reflect on your experience.',
                'type' => 'free_response',
                'content' => 'Take 3-5 minutes to practice the Wise Mind breathing technique described in the lesson. As you breathe in, silently say "Wise," and as you breathe out, silently say "Mind." Afterward, describe what you noticed during the practice. Did your mind wander? Were you able to return your focus to the breath? How do you feel now compared to before the practice?',
                'options' => null,
                'correct_answer' => null,
                'order' => 2,
                'difficulty' => 2,
                'xp_reward' => 10,
                'is_active' => true,
            ],
            
            // Stone in the Pond Meditation exercises
            [
                'lesson_slug' => 'stone-in-pond-meditation',
                'title' => 'Guided Meditation Practice',
                'description' => 'Complete the Stone in the Pond meditation and reflect on your experience.',
                'type' => 'free_response',
                'content' => 'Find a quiet place and practice the Stone in the Pond meditation as described in the lesson. Afterward, reflect on your experience by answering these questions: What sensations did you notice in your body during the meditation? Were you able to visualize the pond and stone? What was it like to reach the "bottom of the pond"? Did you experience any moments of Wise Mind during the practice?',
                'options' => null,
                'correct_answer' => null,
                'order' => 1,
                'difficulty' => 2,
                'xp_reward' => 15,
                'is_active' => true,
            ],
            [
                'lesson_slug' => 'stone-in-pond-meditation',
                'title' => 'Quick Access to Wise Mind',
                'description' => 'Practice a shortened version of the meditation for daily use.',
                'type' => 'free_response',
                'content' => 'Take 1-2 minutes to practice a shortened version of the Stone in the Pond meditation: Close your eyes, take a deep breath, and quickly visualize dropping a stone into still water and following it to the bottom. Try this three times throughout your day—perhaps morning, midday, and evening. Note any differences in your experience each time and whether this brief practice helped you access Wise Mind.',
                'options' => null,
                'correct_answer' => null,
                'order' => 2,
                'difficulty' => 2,
                'xp_reward' => 10,
                'is_active' => true,
            ],
            
            // Wise Mind in Relationships exercises
            [
                'lesson_slug' => 'wise-mind-relationships',
                'title' => 'DEAR MAN Practice Scenario',
                'description' => 'Apply the DEAR MAN technique to a relationship scenario.',
                'type' => 'free_response',
                'content' => 'Your friend frequently cancels plans at the last minute. Using the DEAR MAN technique from Wise Mind, write out what you might say to address this issue. Include each component: Describe, Express, Assert, Reinforce, Mindful, Appear confident, and Negotiate.',
                'options' => null,
                'correct_answer' => null,
                'order' => 1,
                'difficulty' => 3,
                'xp_reward' => 15,
                'is_active' => true,
            ],
            [
                'lesson_slug' => 'wise-mind-relationships',
                'title' => 'Identify Relationship Mind States',
                'description' => 'Recognize different mind states in relationship situations.',
                'type' => 'multiple_choice',
                'content' => 'Your partner forgets an important anniversary. You think to yourself, "They never remember anything that matters to me. They clearly don\'t care about our relationship at all." You immediately cancel dinner plans and refuse to answer their calls for the rest of the day. Which mind state are you primarily in?',
                'options' => ['Emotional Mind', 'Reasonable Mind', 'Wise Mind'],
                'correct_answer' => ['Emotional Mind'],
                'order' => 2,
                'difficulty' => 2,
                'xp_reward' => 10,
                'is_active' => true,
            ],
            [
                'lesson_slug' => 'wise-mind-relationships',
                'title' => 'Wise Mind Response Planning',
                'description' => 'Practice creating a balanced response to a difficult situation.',
                'type' => 'free_response',
                'content' => 'You\'ve been waiting for an important text from your partner about evening plans, but they haven\'t responded for several hours. You\'re feeling anxious and irritated. Write out: 1) What an Emotional Mind response might look like, 2) What a Reasonable Mind response might look like, and 3) How you could respond from Wise Mind, integrating both your emotional needs and rational understanding.',
                'options' => null,
                'correct_answer' => null,
                'order' => 3,
                'difficulty' => 3,
                'xp_reward' => 15,
                'is_active' => true,
            ]
        ];
    }

    /**
     * Get content for a specific lesson.
     * 
     * @param string $lessonSlug The lesson slug
     * @return string|null The lesson content or null if not found
     */
    public function getLessonContent(string $lessonSlug): ?string
    {
        $content = [
            'intro-to-wise-mind' => "# Introduction to Wise Mind

In Dialectical Behavior Therapy (DBT), we recognize three primary states of mind:

## Emotional Mind
When you're in Emotional Mind, your thinking and behavior are controlled primarily by your current emotional state. Your feelings drive your decisions, often leading to impulsive actions that might feel good in the moment but may not serve you well in the long run.

**Signs you're in Emotional Mind:**
- Making decisions based primarily on how you feel
- Acting impulsively without considering consequences
- Strong physical reactions like rapid heartbeat or flushing
- Black and white thinking
- Difficulty concentrating or thinking clearly

## Reasonable Mind
When you're in Reasonable Mind, you approach situations intellectually. You focus on facts, logic, and rational analysis. While this can be helpful for solving problems or planning, it doesn't acknowledge the importance of emotions in our lives.

**Signs you're in Reasonable Mind:**
- Focusing solely on facts and ignoring feelings
- Approaching situations with cool logic
- Planning and analyzing methodically
- Dismissing emotional reactions as irrelevant
- Making decisions based only on rational analysis

## Wise Mind
Wise Mind is the integration of Emotional Mind and Reasonable Mind. It's the middle path that acknowledges both your emotions and rational thoughts. When you're in Wise Mind, you can access your intuition—your inner knowing that incorporates both feeling and thinking.

**Signs you're in Wise Mind:**
- Feeling centered and calm even during challenges
- Considering both emotions and logic when making decisions
- Trusting your intuition or 'gut feeling'
- Finding balance between opposing viewpoints or needs
- Responding to situations rather than reacting

Learning to access Wise Mind is a core skill in mindfulness practice and forms the foundation for all other DBT skills. Through practice, you can learn to recognize which state of mind you're in and intentionally shift toward Wise Mind when needed.",

            'practicing-wise-mind' => "# Practicing Wise Mind

Accessing Wise Mind requires practice and patience. Here are several techniques you can use in your daily life to help find that balanced state between emotion and reason.

## Wise Mind ACCEPTS

This acronym helps you remember ways to access Wise Mind:

### Activities
Engage in activities that connect your mind and body, such as:
- Walking mindfully in nature
- Gentle yoga or stretching
- Creating art or music
- Cooking a meal with full attention

### Contributing
Shift focus away from yourself by helping others:
- Volunteer for a cause you care about
- Offer assistance to a friend or colleague
- Perform small acts of kindness

### Comparisons
Compare your current situation to times of greater difficulty:
- Reflect on how you've overcome challenges before
- Consider how others might handle similar situations
- Remember that all emotions and states are temporary

### Emotions
Acknowledge emotions that are different from your current state:
- If you're angry, try to find moments of gratitude
- If you're anxious, recall times of calm confidence
- Practice accepting all emotions without judgment

### Pushing Away
Temporarily set aside overwhelming thoughts:
- Visualize placing troubling thoughts in a container to deal with later
- Create mental distance from problems when you need a break
- Use phrases like 'Not now' for thoughts that aren't helpful

### Thoughts
Replace destructive thoughts with wise ones:
- Challenge absolute thinking (always/never)
- Question assumptions with 'Is that really true?'
- Reframe negative thoughts in balanced ways

### Sensations
Use physical sensations to anchor yourself:
- Hold something cold or warm
- Focus on your breathing
- Engage your senses (what can you see, hear, touch, smell, taste?)

## Breathing Techniques

One of the simplest ways to access Wise Mind is through mindful breathing:

1. Find a comfortable position
2. Breathe naturally, focusing on the sensation of air moving in and out
3. When you notice your mind wandering, gently return focus to your breath
4. As you breathe in, silently say 'Wise'
5. As you breathe out, silently say 'Mind'
6. Continue for 3-5 minutes

With regular practice, you'll find it easier to access Wise Mind in challenging situations when you need it most.",

            'stone-in-pond-meditation' => "# The Stone in the Pond Meditation

This guided meditation is designed to help you experience Wise Mind through visualization. It's particularly helpful for those who connect well with imagery.

## Preparation

Find a quiet place where you won't be disturbed for about 15 minutes. Sit in a comfortable position with your back straight but not rigid. You may close your eyes or keep them slightly open with a soft gaze.

## Guided Meditation Script

1. **Begin with mindful breathing**
   Take a few moments to settle into your body. Breathe naturally, noticing the sensation of your breath moving in and out. There's no need to change your breathing—simply observe it.

2. **Become aware of your body**
   Notice the points where your body contacts the surface beneath you. Feel the weight of your body being supported. Scan from the top of your head down to your toes, noticing any sensations without trying to change them.

3. **Visualize a calm pond**
   Imagine you're sitting beside a perfectly still pond. The water is clear and reflects everything around it like a mirror. The air is pleasant—not too hot or cold. You feel safe and peaceful in this place.

4. **The surface of the pond represents your mind**
   This still, reflective surface represents your mind when it's calm and balanced. Notice how clearly it reflects reality when it's not disturbed by strong winds (emotions) or frozen over (rigid thinking).

5. **Holding the stone**
   Imagine you're holding a small, smooth stone in your hand. This stone represents your attention. Feel its weight and texture as you hold it.

6. **Dropping the stone into the pond**
   Gently drop the stone into the center of the pond. Watch it break the surface and sink downward. Notice the ripples expanding outward from where the stone entered the water.

7. **Following the stone**
   As the stone sinks deeper, follow it down with your awareness. The surface disturbances fade as you go deeper into the water. The deeper you go, the more still and clear it becomes.

8. **Reaching the bottom of the pond**
   The stone comes to rest at the bottom of the pond. Here, beneath the surface movements, there is a profound stillness and clarity. This deep place represents your Wise Mind—the place where emotional mind and reasonable mind integrate into a balanced wisdom.

9. **Resting in Wise Mind**
   Spend some time resting your awareness at this deep, still place. Notice the quality of your experience here—the sense of knowing that comes from a place beyond just thinking or just feeling.

10. **Returning to the surface**
    When you're ready, gradually bring your awareness back up to the surface of the pond, which is now calm again. Notice how you can maintain a connection to that deep, wise place even as you return to everyday awareness.

11. **Coming back to the room**
    Slowly become aware of your surroundings again. Feel the points where your body contacts the surface beneath you. Take a deeper breath and, when you're ready, open your eyes if they were closed.

## Practice Notes

This meditation becomes more powerful with regular practice. You may find that, over time, you can quickly access this sense of Wise Mind by briefly visualizing dropping a stone into water, even in the midst of challenging situations.

Remember that accessing Wise Mind is not about eliminating emotions or forcing yourself to be rational. It's about finding the integration point where both emotion and reason inform your experience and choices.",

            'wise-mind-relationships' => "# Wise Mind in Relationships

Relationships often trigger our strongest emotions and most rigid thinking patterns. Learning to apply Wise Mind in your interactions with others can transform conflicts into opportunities for growth and deepen your connections.

## Recognizing Mind States in Relationships

### Emotional Mind in Relationships

When operating from Emotional Mind in relationships, you might:
- React immediately to perceived slights
- Make accusations based on feelings rather than facts
- Use absolutes like 'you always' or 'you never'
- Let your emotions drive communication, leading to outbursts or withdrawal
- Make impulsive relationship decisions when upset

### Reasonable Mind in Relationships

When operating from Reasonable Mind in relationships, you might:
- Focus on being 'right' rather than understanding
- Dismiss others' emotional reactions as 'irrational'
- Approach conflicts like problems to be solved rather than experiences to be shared
- Create rigid rules or expectations
- Analyze situations without acknowledging their emotional impact

### Wise Mind in Relationships

When operating from Wise Mind in relationships, you might:
- Listen to understand rather than to respond
- Validate emotions (both yours and others') while also considering facts
- Express needs clearly while remaining open to compromise
- Recognize that most relationship issues are complex with no perfect solution
- Make decisions that honor both emotional well-being and practical realities

## Practicing DEAR MAN from Wise Mind

DEAR MAN is a DBT skill for interpersonal effectiveness that works best when approached from Wise Mind:

### Describe
From Wise Mind: Stick to observable facts without interpretation or judgment
- Instead of: 'You're inconsiderate'
- Try: 'You were 45 minutes late to our dinner plans last night'

### Express
From Wise Mind: Share your feelings and thoughts about the situation clearly without blaming
- Instead of: 'You made me feel worthless'
- Try: 'When plans change without notice, I feel unimportant'

### Assert
From Wise Mind: State what you need specifically while acknowledging the other person's perspective
- Instead of: 'You need to text me constantly'
- Try: 'I'd like a text if you'll be more than 20 minutes late, while respecting that you can't always check your phone'

### Reinforce
From Wise Mind: Explain the positive effects of getting what you've asked for, highlighting benefits for both parties
- Instead of: 'If you cared about me, you'd do this'
- Try: 'If we can communicate about timing, we'll both feel more relaxed and enjoy our time together more'

### Mindful
From Wise Mind: Stay focused on your objective, noticing but not acting on urges to attack or give in
- Notice when you're slipping into Emotional Mind (becoming defensive) or Reasonable Mind (lecturing)
- Return to your breath briefly if needed to recenter

### Appear confident
From Wise Mind: Maintain balanced confidence that respects both yourself and the other person
- Instead of aggressive dominance or fearful submission
- Aim for calm, centered assurance about the validity of your needs

### Negotiate
From Wise Mind: Remain open to alternative solutions while maintaining boundaries around core needs
- Look for the 'both/and' rather than 'either/or'
- Remember that most relationship solutions involve give and take

## Wise Mind Practices for Difficult Relationship Moments

1. **Pause before responding**
   Take a breath, or even ask for a moment, before responding to charged situations.

2. **Check in with your body**
   Notice physical sensations that might signal you've moved away from Wise Mind.

3. **Use validation**
   Validate both your own and the other person's perspective and feelings.

4. **Look for truth on both sides**
   Ask yourself: 'What might be true about their perspective? What might be true about mine?'

5. **Consider long-term relationship goals**
   Ask: 'What response would serve our relationship in the long run?'

Remember that accessing Wise Mind in relationships is an ongoing practice. Each interaction is an opportunity to strengthen this skill, and moments of falling back into Emotional or Reasonable Mind are not failures—they're simply part of the learning process."
        ];

        return $content[$lessonSlug] ?? null;
    }

    /**
     * Get content for a specific exercise.
     * 
     * @param string $exerciseSlug The exercise slug
     * @return array|null The exercise content data or null if not found
     */
    public function getExerciseContent(string $exerciseSlug): ?array
    {
        return null;
    }
}
