<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('message.index');
})->name('home');

Route::resource('/message', 'MessageController')->except(['show']);
Route::get('/message/{message}/send', 'MessageController@send')->name('message.send');
