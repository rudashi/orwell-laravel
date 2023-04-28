<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Rudashi\Orwell\OrwellServiceProvider;

class AlphasSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection(OrwellServiceProvider::PACKAGE)->table('alphas')->insert([
            [
                'letter' => 'a',
                'points' => 1,
            ],
            [
                'letter' => 'e',
                'points' => 1,
            ],
            [
                'letter' => 'i',
                'points' => 1,
            ],
            [
                'letter' => 'n',
                'points' => 1,
            ],
            [
                'letter' => 'o',
                'points' => 1,
            ],
            [
                'letter' => 'r',
                'points' => 1,
            ],
            [
                'letter' => 's',
                'points' => 1,
            ],
            [
                'letter' => 'w',
                'points' => 1,
            ],
            [
                'letter' => 'z',
                'points' => 1,
            ],
            [
                'letter' => 'c',
                'points' => 2,
            ],
            [
                'letter' => 'd',
                'points' => 2,
            ],
            [
                'letter' => 'k',
                'points' => 2,
            ],
            [
                'letter' => 'l',
                'points' => 2,
            ],
            [
                'letter' => 'm',
                'points' => 2,
            ],
            [
                'letter' => 'p',
                'points' => 2,
            ],
            [
                'letter' => 't',
                'points' => 2,
            ],
            [
                'letter' => 'y',
                'points' => 2,
            ],
            [
                'letter' => 'b',
                'points' => 3,
            ],
            [
                'letter' => 'g',
                'points' => 3,
            ],
            [
                'letter' => 'h',
                'points' => 3,
            ],
            [
                'letter' => 'j',
                'points' => 3,
            ],
            [
                'letter' => 'ł',
                'points' => 3,
            ],
            [
                'letter' => 'u',
                'points' => 3,
            ],
            [
                'letter' => 'ą',
                'points' => 5,
            ],
            [
                'letter' => 'ć',
                'points' => 5,
            ],
            [
                'letter' => 'ę',
                'points' => 5,
            ],
            [
                'letter' => 'f',
                'points' => 5,
            ],
            [
                'letter' => 'ń',
                'points' => 5,
            ],
            [
                'letter' => 'ó',
                'points' => 5,
            ],
            [
                'letter' => 'ś',
                'points' => 5,
            ],
            [
                'letter' => 'ź',
                'points' => 5,
            ],
            [
                'letter' => 'ż',
                'points' => 5,
            ],
            [
                'letter' => '*',
                'points' => 0,
            ],
            [
                'letter' => '?',
                'points' => 0,
            ],
        ]);
    }
}
