<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('csv-upload', 'CsvUploadController@getCsvUpload');
Route::post('csv-upload', 'CsvUploadController@postCsvUpload');
Route::get('json-data-upload', 'CsvUploadController@getJsonData');