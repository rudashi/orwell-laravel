<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Services;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class Engine
{
    public const MIN_CHARACTERS = 2;

    private Collection $characters;
    private int $charactersCount;
    private int $wildcardsCount;
    private string $searchValue;
    private string $excludeValue;
    private int $limit = -1;

    public function __construct(string $characters)
    {
        $this->setCharacters(preg_split('//u', mb_strtolower($characters), -1, PREG_SPLIT_NO_EMPTY));
        $this->setWildcardsCount($this->countWildcards());
        $this->setSearchValue($this->characters);
        $this->setExcludeValue($this->characters);
    }

    public static function for(string $characters, int|null $limit = null): Engine
    {
        return (new self($characters))->setLimit($limit ?? 0);
    }

    public function validate(): self
    {
        if ($this->getCharactersCount() < self::MIN_CHARACTERS) {
            throw new InvalidArgumentException('Not enough characters for search.');
        }

        return $this;
    }

    public function setCharacters(array $characters): self
    {
        $this->characters = (new Collection($characters))->mapInto(Alpha::class);
        $this->charactersCount = $this->characters->count();

        return $this;
    }

    public function setWildcardsCount(int $wildcardsCount): self
    {
        $this->wildcardsCount = $wildcardsCount;

        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function setSearchValue(Collection $characters): self
    {
        $this->searchValue = $this->parsePostgresArray($characters, true);

        return $this;
    }

    public function setExcludeValue(Collection $characters): self
    {
        $this->excludeValue = $this->parsePostgresArray($characters);

        return $this;
    }

    public function getCharactersCount(): int
    {
        return $this->charactersCount;
    }

    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function getWildcardsCount(): int
    {
        return $this->wildcardsCount;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getSearchValue(): string
    {
        return $this->searchValue;
    }

    public function getExcludeValue(): string
    {
        return $this->excludeValue;
    }

    private function countWildcards(): int
    {
        return $this->characters->sum(static fn (Alpha $alpha) => $alpha->isWildcard());
    }

    private function parsePostgresArray(Collection $collection, bool $withWildcard = false): string
    {
        $characters = $collection->map(function (Alpha $alpha) {
            return $alpha->isWildcard() ? null : $alpha->getCharacter();
        })->filter();

        if ($withWildcard === true && $this->getWildcardsCount() > 0) {
            $characters = $characters->merge(
                collect(array_merge(['ą', 'ś', 'ę', 'ż', 'ź', 'ć', 'ń', 'ó', 'ł'], range('a', 'z')))
            );
        }
        return '{' . $characters->implode(',') . '}';
    }

}
