<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alphas extends Migration
{

    protected $connection = 'orwell';

    public function up(): void
    {
        Schema::connection('orwell')->create('alphas', static function (Blueprint $table) {
            $table->string('letter');
            $table->tinyInteger('points');
        });

        Artisan::call('db:seed', [
            '--class' => \Rudashi\Orwell\Database\Seeders\AlphasSeeder::class
        ]);

    }

    public function down(): void
    {
        Schema::connection('orwell')->drop('alphas');
    }

}
