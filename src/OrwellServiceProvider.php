<?php

namespace Rudashi\Orwell;

use Illuminate\Support\ServiceProvider;

class OrwellServiceProvider extends ServiceProvider
{

    public function boot() : void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadRoutesFrom(__DIR__.'/api.php');
    }

    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'database.connections');
    }

}