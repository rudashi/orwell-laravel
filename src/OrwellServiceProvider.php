<?php

declare(strict_types=1);

namespace Rudashi\Orwell;

use Illuminate\Support\ServiceProvider;

class OrwellServiceProvider extends ServiceProvider
{
    public const PACKAGE = 'orwell';
    private const MIGRATION = __DIR__ . '/database/migrations';
    private const CONFIG = __DIR__ . '/config/config.php';

    public function boot(): void
    {
        $this->loadMigrationsFrom(self::MIGRATION);

        $this->publish();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG, 'database.connections');
    }

    private function publish(): void
    {
        $this->publishes([
            self::MIGRATION => $this->app->databasePath('migrations'),
        ], self::PACKAGE . '-migrations');

        $this->publishes([
            self::CONFIG => $this->app->configPath(self::PACKAGE . '.php'),
        ], self::PACKAGE . '-config');
    }
}
