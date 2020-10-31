<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::post('/v1/admin/register', [AdminController::class, 'register']);
Route::post('/v1/admin/register', 'Api\v1\AdminController@register');

Route::post('/v1/admin/login', 'Api\v1\AdminController@login');

Route::middleware(['auth:api', 'scope:admin_add_admin'])->post('/v1/admin/administrators/add', 'Api\v1\AdminController@add_admin');

Route::middleware(['auth:api', 'scope:admin_view_admins'])->get('/v1/admin/administrators/list', 'Api\v1\AdminController@get_all_admins');

Route::middleware(['auth:api', 'scope:admin_view_admins'])->get('/v1/admin/administrators/get', 'Api\v1\AdminController@get_one_admin');

Route::middleware(['auth:api', 'scope:admin_update_admin'])->post('/v1/admin/administrators/edit', 'Api\v1\AdminController@edit_admin');

Route::middleware(['auth:api', 'scope:admin_add_merchant'])->post('/v1/admin/merchants/add', 'Api\v1\AdminController@add_merchant');

Route::middleware(['auth:api', 'scope:admin_update_merchant'])->post('/v1/admin/merchants/edit', 'Api\v1\AdminController@edit_merchant');

Route::middleware(['auth:api', 'scope:admin_view_merchant'])->get('/v1/admin/merchants/get', 'Api\v1\AdminController@search_one_merchant');

Route::middleware(['auth:api', 'scope:admin_view_redemptions'])->get('/v1/admin/redemptions/get', 'Api\v1\AdminController@get_merchant_redemptions');

Route::middleware(['auth:api'])->get('/v1/admin/dashboard/get', 'Api\v1\AdminController@get_dashboard');


// ----------------------------------------------------------------------------//
Route::post('/v1/customer/register', 'Api\v1\CustomerController@register');
