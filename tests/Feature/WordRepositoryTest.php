<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use InvalidArgumentException;
use Rudashi\Orwell\WordRepository;

uses(TestCase::class);

beforeEach(function () {
    $this->repository = resolve(WordRepository::class);
});

it('pass input phrase validation', function (string $phrase) {
    expect($this->repository->prepareInputSearch($phrase))
        ->toBe($phrase);
})->with([
    'kajaK',
    'ka*K',
    'ki?is',
    'łoś?',
    'łoś*',
    '?łoś',
    '*łoś',
]);

it('fails input phrase validation', function (string $phrase) {
    expect(fn () => $this->repository->prepareInputSearch($phrase))
        ->toThrow(
            exception: InvalidArgumentException::class,
            exceptionMessage: 'Bad search parameters.',
        );
})->with([
    '18',
    'kaÜüja8K',
    '!,.łóś',
    '---.',
    'łoś?>',
    'łoś*>',
    '>?łoś',
    '>*łoś',
]);

it('returns words based on letters', function () {
    $data = $this->repository->anagram('kajak', 25);

    expect($data)
        ->toHaveCount(11);
});

it('returns words based on wildcard letters', function () {
    $data = $this->repository->anagram('ż*', -1);

    expect($data)
        ->toHaveCount(4)
        ->sequence(
            fn ($letter) => $letter->word->toBe('aż'),
            fn ($letter) => $letter->word->toBe('iż'),
            fn ($letter) => $letter->word->toBe('oż'),
            fn ($letter) => $letter->word->toBe('że'),
        );
});
