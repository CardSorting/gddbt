# DBT Content Architecture

This document provides an overview of the architecture and implementation details for the DBT (Dialectical Behavior Therapy) content system in our application.

## Architecture Overview

The DBT content system follows a Domain-Driven Design (DDD) architecture with CQRS (Command Query Responsibility Segregation) patterns:

```
├── Domain Layer
│   ├── Models
│   ├── Repositories (Interfaces)
│   ├── ValueObjects
│   └── Factories
├── Application Layer
│   ├── Commands
│   ├── Queries
│   └── DTOs
└── Infrastructure Layer
    ├── ContentProviders
    ├── Persistence
    │   └── Repositories (Implementations)
    └── Seeders
```

## Key Components

### Domain Layer

- **ValueObjects**: Encapsulate lesson and exercise content (`LessonContent`, `ExerciseContent`)
- **Factories**: Create domain entities with proper value objects (`LessonFactory`, `ExerciseFactory`)

### Application Layer

- **DTOs**: Data Transfer Objects for transferring data between layers (`LessonDTO`, `ExerciseDTO`)
- **Commands**: Encapsulate operations to create lessons and exercises
  - `CreateLessonCommand` & `CreateLessonHandler`
  - `CreateExerciseCommand` & `CreateExerciseHandler`

### Infrastructure Layer

- **ContentProviders**: Supply structured content data
  - `ContentProviderInterface`
  - `StaticContentProvider` (with mindfulness module content)
- **Seeders**: Populate the database with content
  - `BaseSeeder`
  - `LessonSeeder`
  - `ExerciseSeeder`

## Service Registration

The necessary services are registered through service providers:

- `ContentServiceProvider`: Registers content providers
- `DbtCommandHandlerServiceProvider`: Registers commands, handlers, and factories

## Content Structure

The DBT content is organized around a modular structure:

- **Modules**: Major themes (e.g., Mindfulness)
- **Skills**: Specific skill sets within modules (e.g., Wise Mind, What Skills, How Skills)
- **Lessons**: Individual teaching units (e.g., Observing, Describing, Participating)
- **Exercises**: Practice activities for each lesson

## Adding New Content

To add new content:

1. Extend the content in `StaticContentProvider` or create a new provider implementing `ContentProviderInterface`
2. Create appropriate DTO instances for the content
3. Use the command handlers to create the entities

## Seeding Content

To seed the DBT mindfulness content into the database:

```bash
php artisan dbt:seed-mindfulness
```

This command will create all the necessary lessons and exercises defined in the content provider.

## Value Objects

**LessonContent**: Encapsulates rich lesson content with markdown formatting
**ExerciseContent**: Encapsulates exercise content with optional multiple choice options and correct answers

## Extending the System

To extend this system:

1. Create new value objects for different content types
2. Implement new factories if needed
3. Create DTOs for new content types
4. Implement commands and handlers for creating new content
5. Update or create new content providers

## Benefits of This Architecture

- **Separation of Concerns**: Clear boundaries between domain, application, and infrastructure layers
- **Maintainability**: Changes to one layer don't affect others
- **Testability**: Each component can be easily tested in isolation
- **Scalability**: Content providers can be easily replaced or extended
