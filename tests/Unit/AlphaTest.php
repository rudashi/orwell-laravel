<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Tests\Unit;

use Rudashi\Orwell\Services\Alpha;

it('create instance of Alpha', function () {
    $data = new Alpha('a', 5);

    expect($data)
        ->getCharacter()->toBe('a')
        ->getPoints()->toBe(5)
        ->isWildcard()->toBeFalse();
});

it('determine that character is wildcard', function (string $character) {
    $data = new Alpha($character);

    expect($data->isWildcard())->toBeTrue();
})->with([
    ['*'],
    ['?'],
]);

it('determine that character is not wildcard', function (string $character) {
    $data = new Alpha($character);

    expect($data->isWildcard())->toBeFalse();
})->with([
    [' '],
    ['a'],
]);
