<?php

namespace App\Infrastructure\ContentProviders;

interface ContentProviderInterface
{
    /**
     * Get modules data
     * 
     * @return array
     */
    public function getModulesData(): array;
    
    /**
     * Get skills data
     * 
     * @return array
     */
    public function getSkillsData(): array;
    
    /**
     * Get lessons data
     * 
     * @return array
     */
    public function getLessonsData(): array;
    
    /**
     * Get exercises data
     * 
     * @return array
     */
    public function getExercisesData(): array;
    
    /**
     * Get content for a specific lesson
     * 
     * @param string $lessonSlug The lesson slug
     * @return string|null The lesson content or null if not found
     */
    public function getLessonContent(string $lessonSlug): ?string;
    
    /**
     * Get content for a specific exercise
     * 
     * @param string $exerciseSlug The exercise slug
     * @return array|null The exercise content data or null if not found
     */
    public function getExerciseContent(string $exerciseSlug): ?array;
}
