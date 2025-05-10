<?php
/**
 * DBT Learning Platform - Database Viewer
 * 
 * This standalone viewer allows you to browse the database content
 * without needing to install all Laravel dependencies.
 */

// Database connection parameters
$host = 'hopper.proxy.rlwy.net';
$port = '32157';
$dbname = 'railway';
$username = 'postgres';
$password = 'sFcPxPUyffubAUnnufYoqhnrPXtExjvP';

// CSS styles for the viewer
$styles = '
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
    }
    .container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px 30px;
        margin-bottom: 30px;
    }
    h1 {
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }
    h2 {
        color: #3498db;
        margin-top: 30px;
    }
    h3 {
        color: #2c3e50;
        margin-top: 25px;
        border-left: 4px solid #3498db;
        padding-left: 10px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        text-align: left;
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    thead {
        background-color: #3498db;
        color: white;
    }
    th {
        font-weight: 600;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #e3f2fd;
    }
    .module-card {
        background-color: white;
        border-left: 5px solid #3498db;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 15px;
        margin-bottom: 20px;
    }
    .skill-card {
        background-color: white;
        border-left: 5px solid #2ecc71;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 15px;
        margin-bottom: 20px;
        margin-left: 20px;
    }
    .lesson-card {
        background-color: white;
        border-left: 5px solid #f39c12;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 15px;
        margin-bottom: 15px;
        margin-left: 40px;
    }
    .exercise-card {
        background-color: white;
        border-left: 5px solid #e74c3c;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 15px;
        margin-bottom: 15px;
        margin-left: 60px;
    }
    .stats {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .stat-box {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        width: 22%;
    }
    .stat-box h3 {
        margin-top: 0;
        border: none;
        color: #7f8c8d;
    }
    .stat-number {
        font-size: 2.5em;
        font-weight: bold;
        color: #3498db;
        margin: 10px 0;
    }
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }
    .tab button:hover {
        background-color: #ddd;
    }
    .tab button.active {
        background-color: #3498db;
        color: white;
    }
    .tabcontent {
        display: none;
        padding: 20px;
        border: 1px solid #ccc;
        border-top: none;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        background-color: white;
    }
</style>';

try {
    // Connect to PostgreSQL database
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get statistics
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $moduleCount = $pdo->query("SELECT COUNT(*) FROM modules")->fetchColumn();
    $skillCount = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
    $lessonCount = $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();
    $exerciseCount = $pdo->query("SELECT COUNT(*) FROM exercises")->fetchColumn();
    $achievementCount = $pdo->query("SELECT COUNT(*) FROM achievements")->fetchColumn();
    
    // Get modules
    $modules = $pdo->query("SELECT id, name, description, sort_position FROM modules ORDER BY sort_position")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get skills
    $skills = $pdo->query("
        SELECT 
            s.id,
            s.module_id,
            s.name,
            s.description,
            s.sort_position,
            m.name as module_name
        FROM 
            skills s
        JOIN
            modules m ON s.module_id = m.id
        ORDER BY 
            s.module_id, s.sort_position
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get lessons
    $lessons = $pdo->query("
        SELECT 
            l.id,
            l.skill_id,
            l.name,
            l.description,
            l.sort_position,
            s.name as skill_name
        FROM 
            lessons l
        JOIN
            skills s ON l.skill_id = s.id
        ORDER BY 
            l.skill_id, l.sort_position
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get exercises
    $exercises = $pdo->query("
        SELECT 
            e.id,
            e.lesson_id,
            e.name,
            e.description,
            e.exercise_type,
            e.sort_position,
            l.name as lesson_name
        FROM 
            exercises e
        JOIN
            lessons l ON e.lesson_id = l.id
        ORDER BY 
            e.lesson_id, e.sort_position
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get achievements
    $achievements = $pdo->query("
        SELECT 
            id,
            name,
            description,
            icon,
            achievement_type,
            requirement_count,
            xp_reward
        FROM 
            achievements
        ORDER BY 
            id
    ")->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBT Learning Platform - Database Viewer</title>
    <?php echo $styles; ?>
</head>
<body>
    <div class="container">
        <h1>DBT Learning Platform - Database Viewer</h1>
        <p>This viewer provides a simple interface to browse the database content without requiring a full Laravel installation.</p>
        
        <div class="stats">
            <div class="stat-box">
                <h3>Users</h3>
                <div class="stat-number"><?php echo $userCount; ?></div>
            </div>
            <div class="stat-box">
                <h3>Modules</h3>
                <div class="stat-number"><?php echo $moduleCount; ?></div>
            </div>
            <div class="stat-box">
                <h3>Skills</h3>
                <div class="stat-number"><?php echo $skillCount; ?></div>
            </div>
            <div class="stat-box">
                <h3>Lessons</h3>
                <div class="stat-number"><?php echo $lessonCount; ?></div>
            </div>
        </div>
        
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'ContentTab')">Content Structure</button>
            <button class="tablinks" onclick="openTab(event, 'ModulesTab')">Modules</button>
            <button class="tablinks" onclick="openTab(event, 'SkillsTab')">Skills</button>
            <button class="tablinks" onclick="openTab(event, 'LessonsTab')">Lessons</button>
            <button class="tablinks" onclick="openTab(event, 'ExercisesTab')">Exercises</button>
            <button class="tablinks" onclick="openTab(event, 'AchievementsTab')">Achievements</button>
        </div>
        
        <!-- Content Structure Tab -->
        <div id="ContentTab" class="tabcontent" style="display: block;">
            <h2>DBT Learning Content Structure</h2>
            <p>The content is organized hierarchically from modules to exercises.</p>
            
            <?php foreach ($modules as $module): ?>
                <div class="module-card">
                    <h3><?php echo htmlspecialchars($module['name']); ?></h3>
                    <p><?php echo htmlspecialchars($module['description']); ?></p>
                    
                    <?php
                    // Get skills for this module
                    $moduleSkills = array_filter($skills, function($s) use ($module) {
                        return $s['module_id'] == $module['id'];
                    });
                    
                    foreach ($moduleSkills as $skill):
                    ?>
                        <div class="skill-card">
                            <h4><?php echo htmlspecialchars($skill['name']); ?></h4>
                            <p><?php echo htmlspecialchars($skill['description']); ?></p>
                            
                            <?php
                            // Get lessons for this skill
                            $skillLessons = array_filter($lessons, function($l) use ($skill) {
                                return $l['skill_id'] == $skill['id'];
                            });
                            
                            foreach ($skillLessons as $lesson):
                            ?>
                                <div class="lesson-card">
                                    <h5><?php echo htmlspecialchars($lesson['name']); ?></h5>
                                    <p><?php echo htmlspecialchars($lesson['description']); ?></p>
                                    
                                    <?php
                                    // Get exercises for this lesson
                                    $lessonExercises = array_filter($exercises, function($e) use ($lesson) {
                                        return $e['lesson_id'] == $lesson['id'];
                                    });
                                    
                                    foreach ($lessonExercises as $exercise):
                                    ?>
                                        <div class="exercise-card">
                                            <h6><?php echo htmlspecialchars($exercise['name']); ?> (<?php echo htmlspecialchars($exercise['exercise_type']); ?>)</h6>
                                            <p><?php echo htmlspecialchars($exercise['description']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Modules Tab -->
        <div id="ModulesTab" class="tabcontent">
            <h2>DBT Modules</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module): ?>
                    <tr>
                        <td><?php echo $module['id']; ?></td>
                        <td><?php echo htmlspecialchars($module['name']); ?></td>
                        <td><?php echo htmlspecialchars($module['description']); ?></td>
                        <td><?php echo $module['sort_position']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Skills Tab -->
        <div id="SkillsTab" class="tabcontent">
            <h2>DBT Skills</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Module</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($skills as $skill): ?>
                    <tr>
                        <td><?php echo $skill['id']; ?></td>
                        <td><?php echo htmlspecialchars($skill['module_name']); ?></td>
                        <td><?php echo htmlspecialchars($skill['name']); ?></td>
                        <td><?php echo htmlspecialchars($skill['description']); ?></td>
                        <td><?php echo $skill['sort_position']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Lessons Tab -->
        <div id="LessonsTab" class="tabcontent">
            <h2>DBT Lessons</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Skill</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lessons as $lesson): ?>
                    <tr>
                        <td><?php echo $lesson['id']; ?></td>
                        <td><?php echo htmlspecialchars($lesson['skill_name']); ?></td>
                        <td><?php echo htmlspecialchars($lesson['name']); ?></td>
                        <td><?php echo htmlspecialchars($lesson['description']); ?></td>
                        <td><?php echo $lesson['sort_position']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Exercises Tab -->
        <div id="ExercisesTab" class="tabcontent">
            <h2>DBT Exercises</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lesson</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exercises as $exercise): ?>
                    <tr>
                        <td><?php echo $exercise['id']; ?></td>
                        <td><?php echo htmlspecialchars($exercise['lesson_name']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['name']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['exercise_type']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['description']); ?></td>
                        <td><?php echo $exercise['sort_position']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Achievements Tab -->
        <div id="AchievementsTab" class="tabcontent">
            <h2>Achievements</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Requirement</th>
                        <th>XP Reward</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($achievements as $achievement): ?>
                    <tr>
                        <td><?php echo $achievement['id']; ?></td>
                        <td><?php echo htmlspecialchars($achievement['name']); ?></td>
                        <td><?php echo htmlspecialchars($achievement['achievement_type']); ?></td>
                        <td><?php echo htmlspecialchars($achievement['description']); ?></td>
                        <td><?php echo $achievement['requirement_count']; ?></td>
                        <td><?php echo $achievement['xp_reward']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        
        // Hide all tab content
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        
        // Remove "active" class from all tab buttons
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        
        // Show the current tab and add "active" class to the button
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    </script>
</body>
</html>
