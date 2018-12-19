<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Gets para a API
//MEALS
Route::get('meals', 'MealsController@index');
Route::get('meals/state/active_terminated', 'MealsController@getMealsActiveAndTerminated');

//USERS
Route::get('users', 'UserController@index');
Route::get('users/blocked', 'UserController@getBlockUsers');
Route::get('users/unblocked', 'UserController@getUnblockUsers');

//INVOICES
Route::get('invoices', 'InvoicesController@index');
Route::get('invoices/pending', 'InvoicesController@getPendingInvoces');
Route::post('invoices/create', 'InvoicesController@store');

//ORDERS
Route::get('orders', 'OrdersController@index');

//ITEMS
Route::get('items', 'ItemsController@index');

//TABLES
Route::get('tables', 'TablesController@index');
//Route::get('passreset', 'PasswordResetController@index');
Route::get('invitems', 'InvoiceItemsController@index');
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'LoginControllerAPI@logout');
    //Rotas para tables
    Route::patch('tables/{id}', 'TablesController@update');
    Route::post('tables/create', 'TablesController@store');
    Route::delete('tables/delete/{table_number}', 'TablesController@delete');

    //Rotas para ItemsResource
    Route::post('items/create', 'ItemsController@store');
    Route::patch('items/{id}', 'ItemsController@update');
    Route::delete('items/delete/{id}', 'ItemsController@delete');
    Route::post('items/upload', 'ImageController@uploadItemPhoto');

    //Rotas para users
    Route::post('users/create', 'UserController@store');
    Route::put('users/{id}', 'UserController@update');
    Route::post('users/upload', 'ImageController@uploadUserPhoto');
    Route::delete('users/delete/{id}', 'UserController@delete');
    Route::patch('user/unblock/{id}', 'UserController@unblockUser');
    Route::patch('user/block/{id}', 'UserController@blockUser');
    //Rotas para cashier

});

Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::middleware('auth:api')->get('users/me', 'UserController@getUser');


Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

Route::put('orders/{id}', 'OrdersController@update');

Route::get('/cook/orders/{responsible_cook_id}', 'OrdersController@cookOrders');
Route::get('/waiter/{responsible_waiter_id}/orders/pendingconfirmed', 'OrdersController@waiterPendingConfirmedOrders');
Route::get('/waiter/{responsible_waiter_id}/orders/prepared', 'OrdersController@waiterPreparedOrders');
Route::get('/freetables', 'TablesController@getFreeTables');

Route::post('meals/create', 'MealsController@store');
Route::put('meals/{id}', 'MealsController@update');
Route::get('waiter/{id}/meals/', 'MealsController@myMeals');

Route::post('/orders/multiple', 'OrdersController@storeMultiple');
Route::delete('orders/delete/{id}', 'OrdersController@delete');

Route::get('orders/meal/{id}', 'OrdersController@ordersByMeal');




Route::get('/invoices/pending', 'InvoicesController@pendingInvoices');
Route::get('/invoices/all', 'InvoicesController@allInvoices');
Route::patch('/invoices/pending/{id}', 'InvoicesController@update');

