<?php

use Illuminate\Support\Facades\Route;

Route::view('/verify-ris/{token}', 'app');
Route::view('/{any?}', 'app')->where('any', '.*');
