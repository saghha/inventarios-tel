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
Route::get('/search', 'HomeController@searchMethod');
Route::get('/', 'HomeController@index')->name('home');
Route::post('/photos/upload', 'ImageController@imageUploadPost')->name('photos.imageUpload');
Route::get('/material/disponibles', 'MaterialController@todo');
Route::get('/prestados/{id}', 'MaterialController@getMatPrestadosxAlumno');
Route::get('/prestamo/devueltos', 'PrestamoController@onlyClosed');
Route::get('/persona/count', 'PersonaController@countPerson');
Route:: get('/persona/all', 'PersonaController@selectPersona');
Route::get('/material/prestados', 'MaterialController@getMaterialesPrestados');
Route::get('user/name', 'DashboardController@get_username');
Route::get('/material/mostrequired', 'MaterialController@mostRequired');
Route::get('material/countprestados', 'MaterialController@countMaterialesPrestados');
Route::get('/prestamo/disponibles', 'PrestamoController@onlyAvaibles');
Route::resource('/materiales', 'MaterialController');
Route::resource('/personas', 'PersonaController');
Route::resource('/prestamo', 'PrestamoController');
Route::post('/excel/upload', 'ExcelUploadController@ExcelUploadPost');

