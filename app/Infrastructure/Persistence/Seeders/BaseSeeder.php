<?php

namespace App\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\ContentProviders\ContentProviderInterface;
use App\Application\Commands\CommandBus;

abstract class BaseSeeder extends Seeder
{
    protected ContentProviderInterface $contentProvider;
    protected CommandBus $commandBus;

    /**
     * Create a new seeder instance.
     * 
     * @param ContentProviderInterface $contentProvider
     * @param CommandBus $commandBus
     */
    public function __construct(
        ContentProviderInterface $contentProvider,
        CommandBus $commandBus
    ) {
        $this->contentProvider = $contentProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * Transform module slug to module ID.
     * 
     * @param string $moduleSlug
     * @param array $moduleMap
     * @return int|null
     */
    protected function getModuleId(string $moduleSlug, array $moduleMap): ?int
    {
        return $moduleMap[$moduleSlug] ?? null;
    }

    /**
     * Transform skill slug to skill ID.
     * 
     * @param string $skillSlug
     * @param array $skillMap
     * @return int|null
     */
    protected function getSkillId(string $skillSlug, array $skillMap): ?int
    {
        return $skillMap[$skillSlug] ?? null;
    }

    /**
     * Transform lesson slug to lesson ID.
     * 
     * @param string $lessonSlug
     * @param array $lessonMap
     * @return int|null
     */
    protected function getLessonId(string $lessonSlug, array $lessonMap): ?int
    {
        return $lessonMap[$lessonSlug] ?? null;
    }

    /**
     * Create a map of entity slugs to IDs from a collection.
     * 
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @return array
     */
    protected function createSlugToIdMap($collection): array
    {
        $map = [];
        foreach ($collection as $item) {
            $map[$item->slug] = $item->id;
        }
        return $map;
    }
}
