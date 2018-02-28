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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/logout','Auth\LoginController@logout');

Route::get('/', 'HomeController@index')->name('home');
Route::post('/photos/upload', 'ImageController@imageUploadPost')->name('photos.imageUpload');
Route::get('/material/disponibles', 'MaterialController@todo');
Route::get('/prestamo/disponibles', 'PrestamoController@onlyAvaibles');
Route::resource('/materiales', 'MaterialController');
Route::resource('/personas', 'PersonaController');
Route::resource('/prestamo', 'PrestamoController');
Route::post('/excel/upload', 'ExcelUploadController@ExcelUploadPost');

