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
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            xp INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "Users table created!\n";
    
    // Create modules table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS modules (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            sort_position INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "Modules table created!\n";
    
    // Insert some test data
    $pdo->exec("
        INSERT INTO users (name, email, password) VALUES
        ('Test User', 'test@example.com', '".password_hash('password', PASSWORD_DEFAULT)."'),
        ('Admin User', 'admin@example.com', '".password_hash('admin', PASSWORD_DEFAULT)."')
    ");
    
    $pdo->exec("
        INSERT INTO modules (name, description, sort_position) VALUES
        ('Mindfulness', 'Learn to observe and describe your experiences without judgment.', 1),
        ('Distress Tolerance', 'Develop skills to tolerate and survive crisis situations.', 2),
        ('Emotion Regulation', 'Learn to understand and manage intense emotions effectively.', 3),
        ('Interpersonal Effectiveness', 'Develop skills to navigate relationships and social interactions.', 4)
    ");
    
    echo "Test data inserted!\n";
    echo "Database setup complete!\n";
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
