<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MTN ADMINISTRATOR WEB ROUTES START HERE
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/admin/', function () {
    return view('mtnadministrator/login');
});

Route::get('/admin/login', function () {
    return view('mtnadministrator/login');
});



/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
|
*/
Route::get('/admin/dashboard', function () {
    return view('mtnadministrator/dashboard');
});

/*
|--------------------------------------------------------------------------
| MERCHANTS
|--------------------------------------------------------------------------
|
*/
Route::get('/admin/merchants/add', function () {
    return view('mtnadministrator/merchants/add');
});

Route::get('/admin/merchants/search', function () {
    return view('mtnadministrator/merchants/search');
});

Route::get('/admin/merchants/edit/{id}', function ($id) {
    return view('mtnadministrator/merchants/edit', ['merchant_id' => $id]);
});


/*
|--------------------------------------------------------------------------
| ADMINISTRATORS
|--------------------------------------------------------------------------
|
*/
Route::get('/admin/administrators/add', function () {
    return view('mtnadministrator/administrators/add');
});

Route::get('/admin/administrators/list', function () {
    return view('mtnadministrator/administrators/list');
});

Route::get('/admin/administrators/edit/{id}', function ($id) {
    return view('mtnadministrator/administrators/edit', ['administrator_id' => $id]);
});

/*
|--------------------------------------------------------------------------
| CLAIMS
|--------------------------------------------------------------------------
|
*/
Route::get('/admin/claims/list', function () {
    return view('mtnadministrator/administrators/add');
});

/*
|--------------------------------------------------------------------------
| SECURITY - CHANGE PASSWORD
|--------------------------------------------------------------------------
|
*/
Route::get('/admin/password/change', function () {
    return view('mtnadministrator/security/change_password');
});



/*
|--------------------------------------------------------------------------
| MERCHANT WEB ROUTES START HERE
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
