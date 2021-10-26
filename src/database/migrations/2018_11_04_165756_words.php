<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Words extends Migration
{

    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->string('word');
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
    }

    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS sort_chars;');

        Schema::drop('words');
    }

}