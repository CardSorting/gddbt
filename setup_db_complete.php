<?php

// Database connection parameters
$host = 'hopper.proxy.rlwy.net';
$port = '32157';
$dbname = 'railway';
$username = 'postgres';
$password = 'sFcPxPUyffubAUnnufYoqhnrPXtExjvP';

try {
    // Connect to PostgreSQL database
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to PostgreSQL successfully!\n";
    
    // Drop existing tables (in reverse order of dependencies)
    $tables = [
        'user_followers', 'daily_goals', 'user_exercise_completions', 
        'user_lesson_completions', 'user_achievements', 'user_progress', 
        'streaks', 'achievements', 'exercises', 'lessons', 'skills', 
        'modules', 'users'
    ];
    
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table CASCADE");
        echo "Dropped table $table if it existed\n";
    }
    
    // Create users table
    $pdo->exec("
        CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            level INTEGER DEFAULT 1,
            xp INTEGER DEFAULT 0,
            private_profile BOOLEAN DEFAULT FALSE,
            share_streaks BOOLEAN DEFAULT TRUE,
            share_progress BOOLEAN DEFAULT TRUE,
            share_daily_goals BOOLEAN DEFAULT TRUE,
            remember_token VARCHAR(100) NULL,
            last_login_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "Users table created!\n";
    
    // Create modules table (DBT Skill Modules)
    $pdo->exec("
        CREATE TABLE modules (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            color VARCHAR(20),
            icon VARCHAR(50),
            sort_position INTEGER DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            is_premium BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "Modules table created!\n";
    
    // Create skills table (Skills within each module)
    $pdo->exec("
        CREATE TABLE skills (
            id SERIAL PRIMARY KEY,
            module_id INTEGER NOT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            icon VARCHAR(50),
            sort_position INTEGER DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            is_premium BOOLEAN DEFAULT FALSE,
            prerequisites JSONB DEFAULT '[]'::jsonb,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (module_id) REFERENCES modules (id) ON DELETE CASCADE
        )
    ");
    
    echo "Skills table created!\n";
    
    // Create lessons table
    $pdo->exec("
        CREATE TABLE lessons (
            id SERIAL PRIMARY KEY,
            skill_id INTEGER NOT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            content TEXT,
            sort_position INTEGER DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            xp_reward INTEGER DEFAULT 10,
            estimated_minutes INTEGER DEFAULT 5,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (skill_id) REFERENCES skills (id) ON DELETE CASCADE
        )
    ");
    
    echo "Lessons table created!\n";
    
    // Create exercises table
    $pdo->exec("
        CREATE TABLE exercises (
            id SERIAL PRIMARY KEY,
            lesson_id INTEGER NOT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            content TEXT,
            exercise_type VARCHAR(50),
            options JSONB DEFAULT '[]'::jsonb,
            correct_answer TEXT,
            sort_position INTEGER DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            xp_reward INTEGER DEFAULT 5,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (lesson_id) REFERENCES lessons (id) ON DELETE CASCADE
        )
    ");
    
    echo "Exercises table created!\n";
    
    // Create achievements table
    $pdo->exec("
        CREATE TABLE achievements (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            icon VARCHAR(50),
            achievement_type VARCHAR(50),
            requirement_count INTEGER DEFAULT 1,
            xp_reward INTEGER DEFAULT 50,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "Achievements table created!\n";
    
    // Create streaks table
    $pdo->exec("
        CREATE TABLE streaks (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            current_streak INTEGER DEFAULT 0,
            longest_streak INTEGER DEFAULT 0,
            last_activity_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        )
    ");
    
    echo "Streaks table created!\n";
    
    // Create user progress table
    $pdo->exec("
        CREATE TABLE user_progress (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            skill_id INTEGER NOT NULL,
            completion_percentage INTEGER DEFAULT 0,
            is_completed BOOLEAN DEFAULT FALSE,
            completed_at TIMESTAMP NULL,
            last_activity_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (skill_id) REFERENCES skills (id) ON DELETE CASCADE,
            UNIQUE(user_id, skill_id)
        )
    ");
    
    echo "User Progress table created!\n";
    
    // Create user achievements table
    $pdo->exec("
        CREATE TABLE user_achievements (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            achievement_id INTEGER NOT NULL,
            earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (achievement_id) REFERENCES achievements (id) ON DELETE CASCADE,
            UNIQUE(user_id, achievement_id)
        )
    ");
    
    echo "User Achievements table created!\n";
    
    // Create user lesson completions table
    $pdo->exec("
        CREATE TABLE user_lesson_completions (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            lesson_id INTEGER NOT NULL,
            completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            xp_earned INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (lesson_id) REFERENCES lessons (id) ON DELETE CASCADE,
            UNIQUE(user_id, lesson_id)
        )
    ");
    
    echo "User Lesson Completions table created!\n";
    
    // Create user exercise completions table
    $pdo->exec("
        CREATE TABLE user_exercise_completions (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            exercise_id INTEGER NOT NULL,
            answer TEXT,
            is_correct BOOLEAN,
            completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            xp_earned INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (exercise_id) REFERENCES exercises (id) ON DELETE CASCADE
        )
    ");
    
    echo "User Exercise Completions table created!\n";
    
    // Create daily goals table
    $pdo->exec("
        CREATE TABLE daily_goals (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            date DATE NOT NULL,
            daily_goal TEXT NOT NULL,
            skills_used JSONB DEFAULT '[]'::jsonb,
            tomorrow_goal TEXT,
            highlight TEXT,
            gratitude TEXT,
            is_public BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
            UNIQUE(user_id, date)
        )
    ");
    
    echo "Daily Goals table created!\n";
    
    // Create user followers table
    $pdo->exec("
        CREATE TABLE user_followers (
            id SERIAL PRIMARY KEY,
            follower_id INTEGER NOT NULL,
            following_id INTEGER NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (follower_id) REFERENCES users (id) ON DELETE CASCADE,
            FOREIGN KEY (following_id) REFERENCES users (id) ON DELETE CASCADE,
            UNIQUE(follower_id, following_id)
        )
    ");
    
    echo "User Followers table created!\n";
    
    echo "All database tables created successfully!\n";
    
    // Seed Users
    $pdo->exec("INSERT INTO users (name, email, password, level, xp) VALUES 
        ('Admin User', 'admin@example.com', '".password_hash('password', PASSWORD_DEFAULT)."', 10, 5000),
        ('Regular User', 'user@example.com', '".password_hash('password', PASSWORD_DEFAULT)."', 3, 750),
        ('New User', 'newuser@example.com', '".password_hash('password', PASSWORD_DEFAULT)."', 1, 50)
    ");
    
    // Seed DBT Modules
    $pdo->exec("INSERT INTO modules (name, slug, description, icon, sort_position) VALUES 
        ('Mindfulness', 'mindfulness', 'Learn to observe and describe your experiences without judgment.', 'peace', 1),
        ('Distress Tolerance', 'distress-tolerance', 'Develop skills to tolerate and survive crisis situations.', 'shield', 2),
        ('Emotion Regulation', 'emotion-regulation', 'Learn to understand and manage intense emotions effectively.', 'heart', 3),
        ('Interpersonal Effectiveness', 'interpersonal-effectiveness', 'Develop skills to navigate relationships and social interactions.', 'people', 4)
    ");
    
    // Seed Skills
    $pdo->exec("INSERT INTO skills (module_id, name, slug, description, sort_position) VALUES 
        (1, 'What Skills', 'what-skills', 'Observe, describe, and participate in the present moment.', 1),
        (1, 'How Skills', 'how-skills', 'Practice non-judgmentally, one-mindfully, and effectively.', 2),
        (2, 'Crisis Survival', 'crisis-survival', 'Skills to help you get through immediate crisis situations.', 1),
        (2, 'Reality Acceptance', 'reality-acceptance', 'Skills to help accept reality as it is in the moment.', 2),
        (3, 'Understanding Emotions', 'understanding-emotions', 'Learn to identify and understand your emotions.', 1),
        (3, 'Changing Emotions', 'changing-emotions', 'Practical skills to change unwanted emotions.', 2),
        (4, 'DEAR MAN', 'dear-man', 'Skills for getting what you want effectively.', 1),
        (4, 'GIVE', 'give', 'Skills for maintaining relationships.', 2)
    ");
    
    // Seed Lessons
    $pdo->exec("INSERT INTO lessons (skill_id, name, slug, description, content, sort_position) VALUES 
        (1, 'Introduction to Observe', 'intro-observe', 'Learn the basics of observation without reaction.', 'Content about observing experiences...', 1),
        (1, 'Introduction to Describe', 'intro-describe', 'Learn how to put words to your experiences.', 'Content about describing experiences...', 2),
        (2, 'Non-judgmental Stance', 'non-judgmental', 'Learn to take a non-judgmental stance toward experiences.', 'Content about non-judgment...', 1),
        (3, 'TIPP Skills', 'tipp-skills', 'Temperature, Intense exercise, Paced breathing, Paired muscle relaxation.', 'Content about TIPP skills...', 1),
        (5, 'Model of Emotions', 'model-of-emotions', 'Understanding how emotions work.', 'Content about emotion model...', 1),
        (7, 'DEAR MAN Overview', 'dear-man-overview', 'Introduction to the DEAR MAN skill.', 'Content about DEAR MAN...', 1)
    ");
    
    // Seed Exercises
    $pdo->exec("INSERT INTO exercises (lesson_id, name, slug, description, content, exercise_type, sort_position) VALUES 
        (1, 'Mindful Breathing', 'mindful-breathing', 'Practice observing your breath for 5 minutes.', 'Instructions for mindful breathing...', 'practice', 1),
        (1, 'Body Scan', 'body-scan', 'Practice observing sensations in your body.', 'Instructions for body scan...', 'practice', 2),
        (2, 'Describing Emotions', 'describing-emotions', 'Practice describing your emotional experiences.', 'Instructions for describing emotions...', 'reflection', 1),
        (3, 'Judgment Identification', 'judgment-identification', 'Identify judgmental thoughts and transform them.', 'Instructions for identifying judgments...', 'reflection', 1),
        (4, 'Paced Breathing', 'paced-breathing', 'Practice slowing down your breath.', 'Instructions for paced breathing...', 'practice', 1),
        (5, 'Emotion Worksheet', 'emotion-worksheet', 'Identify components of an emotional experience.', 'Instructions for emotion worksheet...', 'worksheet', 1)
    ");
    
    // Seed Achievements
    $pdo->exec("INSERT INTO achievements (name, description, icon, achievement_type, requirement_count, xp_reward) VALUES 
        ('Mindfulness Beginner', 'Complete your first mindfulness lesson', 'award', 'module_progress', 1, 50),
        ('Consistent Practice', 'Maintain a 7-day streak', 'fire', 'streak', 7, 100),
        ('Emotion Master', 'Complete all emotion regulation lessons', 'star', 'module_completion', 1, 200),
        ('Social Butterfly', 'Follow 5 other users', 'people', 'social', 5, 50),
        ('Goal Setter', 'Create 10 daily goals', 'check', 'daily_goals', 10, 100)
    ");
    
    // Seed Streaks for users
    $pdo->exec("INSERT INTO streaks (user_id, current_streak, longest_streak, last_activity_date) VALUES 
        (1, 15, 30, CURRENT_DATE),
        (2, 3, 10, CURRENT_DATE),
        (3, 0, 2, CURRENT_DATE - INTERVAL '3 days')
    ");
    
    // Seed Daily Goals
    $pdo->exec("INSERT INTO daily_goals (user_id, date, daily_goal, skills_used, tomorrow_goal, highlight, gratitude) VALUES 
        (1, CURRENT_DATE, 'Practice mindful breathing for 10 minutes', '[1, 2]', 'Work on emotion regulation skills', 'Stayed calm during a difficult meeting', 'Grateful for supportive friends'),
        (1, CURRENT_DATE - INTERVAL '1 day', 'Practice TIPP skills when stressed', '[3, 4]', 'Continue with TIPP skills', 'Successfully used skills during stress', 'Grateful for DBT skills'),
        (2, CURRENT_DATE, 'Practice describing emotions without judgment', '[1, 5]', 'Try body scan exercise', 'Successfully identified emotions', 'Grateful for learning new skills')
    ");
    
    // Seed User Followers
    $pdo->exec("INSERT INTO user_followers (follower_id, following_id) VALUES 
        (2, 1),
        (3, 1),
        (1, 2)
    ");
    
    echo "Database seeded successfully with test data!\n";
    echo "Database setup complete!\n";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
