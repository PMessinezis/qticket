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


Route::resource('ticket', 'TicketController');
Route::resource('category', 'CategoryController');
Route::resource('resolver', 'ResolverController');
Route::resource('group', 'GroupController');
Route::resource('groupresolver', 'GroupResolverController');
Route::resource('user', 'UserController');
Route::resource('department', 'DepartmentController');
Route::resource('subcategory', 'SubcategoryController');
Route::resource('source', 'SourceController');
Route::resource('type', 'TypeController');
Route::resource('status', 'StatusController');
Route::resource('vendor', 'VendorController');
Route::resource('rootcause', 'RootCauseController');
Route::resource('ticketupdate', 'TicketUpdateController');
Route::resource('attachment', 'AttachmentController');
