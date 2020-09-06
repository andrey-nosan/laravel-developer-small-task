<?php

use Illuminate\Support\Facades\Route;

Route::get('/message', 'MessageController@index')->name('message.index');
