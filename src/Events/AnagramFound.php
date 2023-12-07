<?php

declare(strict_types=1);

namespace Rudashi\Orwell\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AnagramFound
{
    /**
     * @var array<array-key, null|string>
     */
    public array $ips = [];

    public function __construct(
        public string $letters,
        public Collection $anagrams,
        Request $request = null
    )
    {
        if ($request) {
            $this->ips = $request->getClientIps() ?? ['xxx.xxx.xxx.xxx'];
        }
    }
}
