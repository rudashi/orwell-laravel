<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Tests\AlphaTest;

use Rudashi\Orwell\Services\Alpha;
use Tests\TestCase;

uses(TestCase::class);

it('create instance of Alpha', function () {
    $data = new Alpha('a', 5);

    expect($data)
        ->getCharacter()->toBe('a')
        ->getPoints()->toBe(5)
        ->isWildcard()->toBeFalse();
});

it('determine if character is wildcard', function (string $character, bool $expectation) {
    $data = new Alpha($character);

    expect($data->isWildcard())->toBe($expectation);
})->with([
    ['*', true],
    [' ', false],
    ['?', true],
    ['a', false],
]);
