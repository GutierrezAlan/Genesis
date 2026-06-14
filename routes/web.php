<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// Route::get('/', function () {
//     return view('hola');
// });
Route::get('/{any}', function () {
    // Si tu index.html está en la raíz de public/
    return file_get_contents(public_path('index.html'));

    // Si tu index.html está dentro de public/frontend/
    // return file_get_contents(public_path('frontend/index.html'));
})->where('any', '.*');