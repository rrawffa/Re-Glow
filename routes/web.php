<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/education', 'App\Http\Controllers\EducationController@index');
Route::get('/education/{id}', 'App\Http\Controllers\EducationController@show');

Route::get('/test-db', function () {
    try {
        $db   = DB::select("SELECT DATABASE() as db");
        $tbl  = DB::select('SHOW TABLES');
        return [
            'database' => $db,
            'tables'   => $tbl,
        ];
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/env-test', function () {
    return env('DB_DATABASE');
});