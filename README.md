# DBT Learning Gamification Platform

A Laravel-based application for gamifying the learning of Dialectical Behavioral Therapy (DBT) skills. This platform combines educational content with gamification elements to make learning DBT skills engaging and effective.

## Architecture

This application is built following SOLID principles and incorporates several architectural patterns:

### Domain-Driven Design (DDD)

The application is structured around the core domain of DBT learning and gamification:

- **Domain Layer**: Contains all business logic and entities
  - Models: Module, Skill, Lesson, Exercise, User, Progress, Achievement
  - Events: Lesson completion, skill mastery, achievements
  - Value Objects: Pure domain concepts

- **Application Layer**: Orchestrates the execution of domain logic
  - Commands: Actions that change the state (Complete lesson, create daily goal)
  - Queries: Read-only operations (Get progress, leaderboard)
  - Event Handlers: React to domain events

### Clean Architecture

The application follows clean architecture principles to maintain separation of concerns:

- **Domain Core**: Contains business entities and rules
- **Use Cases**: Application-specific business rules
- **Interface Adapters**: Converts data between use cases and external frameworks
- **Frameworks & Drivers**: External frameworks, database, web UI

### Command Query Responsibility Segregation (CQRS)

The application separates operations that read data from operations that update data:

- **Commands**: Handle state changes (CompleteLessonCommand)
- **Queries**: Read-only operations (GetUserProgressQuery)

## Database Structure

The database is designed to support gamification of DBT learning with tables for:

1. **Users**: Store user information and authentication
2. **Modules**: Top-level DBT skills modules (Mindfulness, Distress Tolerance, etc.)
3. **Skills**: Specific skills within modules
4. **Lessons**: Educational content for each skill
5. **Exercises**: Interactive practice for lessons
6. **Achievements**: Gamification rewards
7. **Streaks**: Track daily usage streaks
8. **User Progress**: Track completion of skills and lessons
9. **Daily Goals**: User-set goals for DBT practice
10. **Social Features**: User followers for social gamification

## Setup Instructions

### Prerequisites

- PHP 8.1 or higher
- Composer
- PostgreSQL database

### Installation

1. **Clone the repository**

```bash
git clone <repository-url>
cd dbt-learning-platform
```

2. **Install dependencies**

```bash
composer install
```

3. **Environment setup**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**

The .env file is already configured with the PostgreSQL database connection details:

```
DB_CONNECTION=pgsql
DB_HOST=hopper.proxy.rlwy.net
DB_PORT=32157
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=sFcPxPUyffubAUnnufYoqhnrPXtExjvP
```

5. **Initialize database**

The database tables have already been set up using a custom script. To recreate the tables (this will drop existing tables):

```bash
php setup_db_complete.php
```

6. **Start the application**

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Features

### Learning Content

- Structured learning path through DBT skills
- Multi-media content delivery
- Interactive exercises and practice activities

### Gamification Elements

- XP and leveling system
- Achievements and badges
- Streaks for consistent practice
- Progress tracking

### Social Features

- Follow other users
- View friend progress
- Leaderboards

### Personalization

- Daily goal setting
- Tracking mood and skill use
- Customizable learning path

## Modules

The application covers the four main modules of DBT:

1. **Mindfulness**
   - What Skills (Observe, Describe, Participate)
   - How Skills (Non-judgmentally, One-mindfully, Effectively)

2. **Distress Tolerance**
   - Crisis Survival
   - Reality Acceptance

3. **Emotion Regulation**
   - Understanding Emotions
   - Changing Emotions

4. **Interpersonal Effectiveness**
   - DEAR MAN
   - GIVE
   - FAST

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
