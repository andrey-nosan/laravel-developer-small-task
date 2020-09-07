<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('message.index');
})->name('home');

Route::get('/message', 'MessageController@index')->name('message.index');
