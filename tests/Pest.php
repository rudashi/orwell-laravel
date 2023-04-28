<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Tests;

use Rudashi\Orwell\WordRepository;

function repository(): WordRepository
{
    return app(WordRepository::class);
}
