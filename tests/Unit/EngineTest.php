<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Tests\EngineTest;

use InvalidArgumentException;
use Rudashi\Orwell\Services\Engine;
use Tests\TestCase;

uses(TestCase::class);

it('returns Engine based on word', function () {
    $data = new Engine('kaja*k');

    expect($data)
        ->getCharacters()->toHaveCount(6)
        ->getCharactersCount()->toBe(6)
        ->getWildcardsCount()->toBe(1)
        ->getLimit()->toBe(-1)
        ->getSearchValue()->toBe('{k,a,j,a,k,ą,ś,ę,ż,ź,ć,ń,ó,ł,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z}')
        ->getExcludeValue()->toBe('{k,a,j,a,k}');
});

it('create Engine with custom limit', function () {
    $basic = new Engine('kajak');
    $custom = Engine::for('kajak', 8);

    expect($custom)
        ->getCharacters()->toMatchArray($basic->getCharacters())
        ->getLimit()->toBe(8);
});

it('count Wildcards in word', function (string $word, int $result) {
    $data = new Engine($word);

    expect($data->getWildcardsCount())->toBe($result);
})->with([
    ['kajaK', 0],
    ['ka*K', 1],
    ['ki?*is', 2],
    ['łoś?', 1],
    ['łoś*', 1],
    ['?łoś*', 2],
    ['****', 4],
]);

it('pass minimum characters validation', function (string $word) {
    $data = Engine::for($word);

    expect($data->validate())->toBe($data);
})->with([
    'kajak',
    'kajakówna',
    'ki',
    'łoś',
]);

it('fails minimum characters validation', function ($word) {
    $data = Engine::for($word);

    expect(fn () => $data->validate())
        ->toThrow(
            exception: InvalidArgumentException::class,
            exceptionMessage: 'Not enough characters for search.',
        );
})->with([
    'k',
    '?',
    '',
    '',
    'ł',
]);

it('returns PostgresSQL array search value', function () {
    $data = Engine::for('kaj*ak');

    expect($data->getSearchValue())
        ->toBe('{k,a,j,a,k,ą,ś,ę,ż,ź,ć,ń,ó,ł,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z}');
});

it('returns PostgresSQL array excluded value', function () {
    $data = Engine::for('kaj*ak');

    expect($data->getExcludeValue())
        ->toBe('{k,a,j,a,k}');
});
