<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Rudashi\Orwell\OrwellServiceProvider;

return new class () extends Migration {
    protected $connection = OrwellServiceProvider::PACKAGE;

    public function up(): void
    {
        if (Schema::hasTable('words') === false) {
            DB::transaction(static function () {
                Schema::create('words', static function (Blueprint $table) {
                    $table->string('word');
                    $table->integer('points')->nullable();
                });

                DB::unprepared('ALTER TABLE words ADD COLUMN characters CHAR [];');
                DB::unprepared('
              CREATE OR REPLACE FUNCTION sort_chars(text) RETURNS text AS
                    $func$
                SELECT array_to_string(ARRAY(SELECT unnest(string_to_array($1 COLLATE "C", NULL)) c ORDER BY c), \'\')
                    $func$  LANGUAGE sql IMMUTABLE;
            ');
                DB::unprepared('CREATE INDEX ix_word_chars ON words USING GIN (characters);');
                DB::unprepared('CREATE INDEX ix_word_length ON words (char_length(word));');
            });
        }
    }

    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS sort_chars;');

        Schema::dropIfExists('words');
    }
};
