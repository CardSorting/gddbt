# DBT Learning Platform

A gamified learning platform for Dialectical Behavioral Therapy (DBT) skills, built with Laravel and following Domain-Driven Design (DDD), Clean Architecture, and CQRS principles.

## Architecture Overview

This application is built with a strong focus on maintainability, testability, and scalability using modern architectural patterns:

- **Domain-Driven Design (DDD)**: The core business logic is modeled around domain entities and business rules.
- **Clean Architecture**: The application is structured in layers with clear dependencies flowing inward.
- **Command Query Responsibility Segregation (CQRS)**: Commands and queries are separated to optimize for specific use cases.
- **SOLID Principles**: The code follows SOLID principles for maintainability and extensibility.

### Project Structure

```
app/
  ├── Domain/              # Domain layer - business rules and entities
  │   ├── Models/          # Domain entities
  │   ├── Events/          # Domain events
  │   ├── Repositories/    # Repository interfaces
  │   └── ValueObjects/    # Value objects
  │
  ├── Application/         # Application layer - use cases
  │   ├── Commands/        # Command objects and handlers
  │   ├── Queries/         # Query objects and handlers
  │   ├── DTOs/            # Data Transfer Objects
  │   └── Services/        # Application services
  │
  ├── Infrastructure/      # Infrastructure layer - external concerns
  │   ├── Persistence/     # Database implementations
  │   └── Services/        # External service implementations
  │
  └── Http/                # Presentation layer - API and web controllers
      ├── Controllers/     # Request handlers
      └── Resources/       # API resources/transformers
```

## Core Features

### DBT Content Structure

1. **Modules**: The four core DBT modules (Mindfulness, Distress Tolerance, Emotion Regulation, Interpersonal Effectiveness)
2. **Skills**: Specific DBT skills within each module
3. **Lessons**: Educational content for each skill
4. **Exercises**: Interactive activities to practice skills

### Gamification Features

1. **XP Points & Levels**: Users earn XP for completing lessons and exercises
2. **Streaks**: Users maintain daily learning streaks (similar to Duolingo)
3. **Achievements**: Users earn achievements for reaching milestones
4. **Progress Tracking**: Users can track their progress through skills and modules
5. **Daily Goals**: Users can set and track daily goals with DBT skills used, including reflections on gratitude and achievements
6. **Social Features**: Users can follow each other, view progress, streaks, and daily goals of friends

## Tech Stack

- **Backend**: Laravel PHP framework
- **Database**: PostgreSQL
- **Architecture**: DDD, Clean Architecture, CQRS

## Setup and Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and update the database settings
4. Run database migrations: `php artisan migrate`
5. Seed the database: `php artisan db:seed`
6. Start the development server: `php artisan serve`

## API Endpoints

### User Progress
- GET `/api/v1/users/{userId}/progress` - Get user's progress overview
- POST `/api/v1/lessons/complete` - Mark a lesson as completed

### Daily Goals
- GET `/api/v1/users/{userId}/daily-goals` - Get a user's daily goals
- GET `/api/v1/users/{userId}/daily-goals/today` - Get today's goal for a user
- POST `/api/v1/daily-goals` - Create or update a daily goal
- PATCH `/api/v1/daily-goals/{id}/visibility` - Toggle goal visibility

### Social Features
- POST `/api/v1/users/{userId}/follow` - Follow a user
- DELETE `/api/v1/users/{userId}/follow` - Unfollow a user
- GET `/api/v1/users/{userId}/followers` - Get user's followers
- GET `/api/v1/users/{userId}/following` - Get users being followed
- GET `/api/v1/feed` - Get activity feed from followed users
- PATCH `/api/v1/users/privacy` - Update privacy settings

## Database Schema

The database follows the domain model with tables for:
- Users
- Modules
- Skills
- Lessons
- Exercises
- User progress tracking
- Streaks
- Achievements
- Daily goals
- User followers/following relationships

## Testing and Development

The project is set up with seeders for creating test data:
- Core DBT content (modules, skills, lessons, exercises)
- Test users with different progress levels
- Achievements and gamification elements

## CQRS Implementation

The application separates commands (write operations) from queries (read operations):

- **Commands**: Operations that change state (e.g., completing a lesson)
- **Queries**: Operations that read state (e.g., getting user progress)

This pattern allows for optimization of each path separately and provides clearer code organization.
