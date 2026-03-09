<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Services;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class Engine
{
    public const MIN_CHARACTERS = 2;

    /**
     * @var \Illuminate\Support\Collection<int, \Rudashi\Orwell\Services\Alpha>
     */
    private Collection $characters;
    private int $charactersCount;
    private int $wildcardsCount;
    private string $searchValue;
    private string $excludeValue;
    private int $limit;

    public function __construct(string $characters, int $limit = -1)
    {
        $this->characters = (new Collection($this->parseCharacters($characters)))->mapInto(Alpha::class);
        $this->charactersCount = $this->characters->count();
        $this->wildcardsCount = $this->countWildcards();
        $this->searchValue = $this->parsePostgresArrayWithWildcards($this->characters);
        $this->excludeValue = $this->parsePostgresArray($this->characters);
        $this->limit = $limit;
    }

    public static function for(string $characters, int|null $limit = null): self
    {
        return new self($characters, $limit ?? 0);
    }

    public function validate(): self
    {
        if ($this->getCharactersCount() < self::MIN_CHARACTERS) {
            throw new InvalidArgumentException('Not enough characters for search.');
        }

        return $this;
    }

    public function getCharactersCount(): int
    {
        return $this->charactersCount;
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Rudashi\Orwell\Services\Alpha>
     */
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
        return $this->characters->filter(static fn (Alpha $alpha) => $alpha->isWildcard())->count();
    }

    /**
     * @return string[]
     */
    private function parseCharacters(string $characters): array
    {
        return preg_split('//u', mb_strtolower($characters), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param \Illuminate\Support\Collection<int, \Rudashi\Orwell\Services\Alpha> $collection
     */
    private function parsePostgresArray(Collection $collection): string
    {
        $characters = $this->extractNonWildcardCharacters($collection);

        return '{' . $characters->implode(',') . '}';
    }

    /**
     * @param \Illuminate\Support\Collection<int, \Rudashi\Orwell\Services\Alpha> $collection
     */
    private function parsePostgresArrayWithWildcards(Collection $collection): string
    {
        $characters = $this->extractNonWildcardCharacters($collection);

        if ($this->getWildcardsCount() > 0) {
            $characters = $characters->merge(
                collect(array_merge(['ą', 'ś', 'ę', 'ż', 'ź', 'ć', 'ń', 'ó', 'ł'], range('a', 'z')))
            );
        }

        return '{' . $characters->implode(',') . '}';
    }

    /**
     * @param \Illuminate\Support\Collection<int, \Rudashi\Orwell\Services\Alpha> $chars
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function extractNonWildcardCharacters(Collection $chars): Collection
    {
        return $chars->map(static fn (Alpha $alpha) => $alpha->isWildcard() ? null : $alpha->getCharacter())->filter();
    }
}
