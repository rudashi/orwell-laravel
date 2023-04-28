<?php

namespace Rudashi\Orwell;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Rudashi\Orwell\Services\Engine;

class WordRepository
{
    public const REGEX = '/^[A-ZĄĆĘŁŃÓŚŹŻ?*]*$/iu';
    private Connection $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db->connection(OrwellServiceProvider::PACKAGE);
    }

    public function anagram(string $letters, int $limit = null): Collection
    {
        $engine = Engine::for($letters, $limit)->validate();

        return $this->db
            ->table('words')
            ->select('word')
            ->selectRaw('(
                SELECT SUM(points)
                FROM ( SELECT regexp_split_to_table(words.word,E\'(?=.)\') the_word ) tab
                LEFT JOIN alphas ON the_word = letter
             ) as points')
            ->where('characters', '<@', $engine->getSearchValue())
            ->whereRaw('char_length(word) <= ?', $engine->getCharactersCount())
            ->whereRaw(
                sql: '(SELECT count(*) FROM ( SELECT unnest(characters) EXCEPT ALL SELECT unnest(? :: CHAR []) ) extra) <= ?',
                bindings: [$engine->getExcludeValue(), $engine->getWildcardsCount()]
            )
            ->orderByRaw('char_length(word) DESC')
            ->orderByDesc('points')
            ->orderBy('word')
            ->limit($engine->getLimit())
            ->get();
    }

    public function prepareInputSearch(string $input): string
    {
        $input = urldecode($input);

        if ($this->validateLetters($input) === false) {
            throw new InvalidArgumentException('Bad search parameters.');
        }

        return $input;
    }

    private function validateLetters(string $letters): bool
    {
        return preg_match(self::REGEX, $letters);
    }
}
