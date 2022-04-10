<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Words extends Migration
{

    protected $connection = 'orwell';

    public function up(): void
    {
        Schema::connection('orwell')->create('words', static function (Blueprint $table) {
            $table->string('word');
        });

        DB::connection('orwell')->unprepared('ALTER TABLE words ADD COLUMN characters CHAR [];');

        DB::connection('orwell')->unprepared('
            CREATE OR REPLACE FUNCTION sort_chars(text) RETURNS text AS
                $func$
            SELECT array_to_string(ARRAY(SELECT unnest(string_to_array($1 COLLATE "C", NULL)) c ORDER BY c), \'\')
                $func$  LANGUAGE sql IMMUTABLE;
        ');

        DB::connection('orwell')->unprepared('CREATE INDEX ix_word_chars ON words USING GIN (characters);');
        DB::connection('orwell')->unprepared('CREATE INDEX ix_word_length ON words (char_length(word));');
    }

    public function down(): void
    {
        DB::connection('orwell')->unprepared('DROP FUNCTION IF EXISTS sort_chars;');

        Schema::connection('orwell')->drop('words');
    }

}
