<?php

namespace App\Domain\ValueObjects\Content;

class LessonContent
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getValue(): string
    {
        return $this->content;
    }

    public function isValid(): bool
    {
        // Basic validation - could be extended with more specific rules
        return !empty($this->content);
    }

    public function wordCount(): int
    {
        return str_word_count($this->content);
    }

    public function estimatedReadingTimeMinutes(): int
    {
        // Average reading speed: 200 words per minute
        return (int) ceil($this->wordCount() / 200);
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
