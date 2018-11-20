<?php

Route::get('api/orwell/{letters}', 'Rudashi\Orwell\WordController@allWords')->name('api.orwell.search');
