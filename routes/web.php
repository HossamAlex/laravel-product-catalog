<?php


use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    return view('frontend');
})->where('any', '.*');

Route::get('/{any}', function () {
    return view('frontend');
})->where('any', '^(?!api|admin|storage).*$');

