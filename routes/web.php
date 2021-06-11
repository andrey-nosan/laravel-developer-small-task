<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'message');

Route::resource('/message', 'MessageController')->except(['show']);
Route::get('/message/{message}/send', 'MessageController@send')->name('message.send');
