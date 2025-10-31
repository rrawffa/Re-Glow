<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php
Route::get('/education', function () {
    return view('education.index'); // Memanggil file index.blade.php di folder education
});

Route::get('/education/{id}', function ($id) {
    return view('education.show', ['id' => $id]); // Memanggil show.blade.php
});
