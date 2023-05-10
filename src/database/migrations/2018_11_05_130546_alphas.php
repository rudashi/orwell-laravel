<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Rudashi\Orwell\Database\Seeders\AlphasSeeder;
use Rudashi\Orwell\OrwellServiceProvider;

return new class () extends Migration {
    protected $connection = OrwellServiceProvider::PACKAGE;

    public function up(): void
    {
        if (Schema::hasTable('alphas') === false) {
            Schema::create('alphas', static function (Blueprint $table) {
                $table->string('letter');
                $table->tinyInteger('points');
            });

            (new AlphasSeeder())->run();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('alphas');
    }
};
