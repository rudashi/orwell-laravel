<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alphas extends Migration
{

    public function up(): void
    {
        Schema::create('alphas', function (Blueprint $table) {
            $table->string('letter');
            $table->tinyInteger('points');
        });

        Artisan::call('db:seed', [
            '--class' => \Rudashi\Orwell\Database\Seeders\AlphasSeeder::class
        ]);

    }

    public function down(): void
    {
        Schema::drop('alphas');
    }

}