<?php

namespace App\Domain\ValueObjects\Content;

class ExerciseContent
{
    private string $content;
    private array $options;
    private ?array $correctAnswer;

    public function __construct(string $content, ?array $options = null, ?array $correctAnswer = null)
    {
        $this->content = $content;
        $this->options = $options ?? [];
        $this->correctAnswer = $correctAnswer;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function getCorrectAnswer(): ?array
    {
        return $this->correctAnswer;
    }

    public function hasOptions(): bool
    {
        return !empty($this->options);
    }

    public function hasCorrectAnswer(): bool
    {
        return !empty($this->correctAnswer);
    }

    public function isValid(): bool
    {
        return !empty($this->content);
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
