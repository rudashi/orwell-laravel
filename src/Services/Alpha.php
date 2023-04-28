<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Services;

class Alpha
{
    public function __construct(
        protected string $character,
        protected int $points = 0
    ) {
    }

    public function getCharacter(): string
    {
        return $this->character;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function isWildcard(): bool
    {
        return in_array($this->getCharacter(), ['?', '*'], true);
    }
}
