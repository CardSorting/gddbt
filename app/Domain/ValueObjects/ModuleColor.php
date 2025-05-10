<?php

namespace App\Domain\ValueObjects;

class ModuleColor
{
    private string $colorCode;

    public function __construct(string $colorCode)
    {
        if (!$this->isValidHexColor($colorCode)) {
            throw new \InvalidArgumentException("Invalid hex color code: {$colorCode}");
        }
        
        $this->colorCode = $colorCode;
    }

    public function getValue(): string
    {
        return $this->colorCode;
    }

    public function getRgb(): array
    {
        $hex = ltrim($this->colorCode, '#');
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }

    public function getContrastColor(): self
    {
        $rgb = $this->getRgb();
        
        // Calculate brightness according to W3C guidelines
        $brightness = (($rgb['r'] * 299) + ($rgb['g'] * 587) + ($rgb['b'] * 114)) / 1000;
        
        // Choose white or black based on background brightness
        return new self($brightness > 128 ? '#000000' : '#FFFFFF');
    }

    public function darken(int $percent = 10): self
    {
        $rgb = $this->getRgb();
        
        // Darken each RGB component
        foreach ($rgb as $key => $value) {
            $rgb[$key] = max(0, $value - round($value * ($percent / 100)));
        }
        
        return new self(sprintf(
            '#%02x%02x%02x',
            $rgb['r'],
            $rgb['g'],
            $rgb['b']
        ));
    }

    public function lighten(int $percent = 10): self
    {
        $rgb = $this->getRgb();
        
        // Lighten each RGB component
        foreach ($rgb as $key => $value) {
            $rgb[$key] = min(255, $value + round((255 - $value) * ($percent / 100)));
        }
        
        return new self(sprintf(
            '#%02x%02x%02x',
            $rgb['r'],
            $rgb['g'],
            $rgb['b']
        ));
    }

    private function isValidHexColor(string $color): bool
    {
        return preg_match('/^#[a-fA-F0-9]{6}$/', $color) === 1;
    }

    public function __toString(): string
    {
        return $this->colorCode;
    }
}
