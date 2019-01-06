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


//ITEMS
Route::get('items', 'ItemsController@index');
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'LoginControllerAPI@logout');
    Route::get('invitems', 'InvoiceItemsController@index');


    //Statistics US39
    Route::middleware('isManager')->get('cook/orders/average/day', 'OrdersController@averageByCook');
    Route::middleware('isManager')->get('waiter/meals/average/day', 'OrdersController@statisticsMeals');
    Route::middleware('isManager')->get('waiter/orders/average/day', 'OrdersController@averageByWaiter');


    //Rotas para tables
    Route::middleware('isManager')->get('tables', 'TablesController@index');
    Route::middleware('isWaiter')->get('/freetables', 'TablesController@getFreeTables');
    Route::middleware('isManager')->patch('tables/{id}', 'TablesController@update');
    Route::middleware('isManager')->post('tables/create', 'TablesController@store');
    Route::middleware('isManager')->delete('tables/delete/{table_number}', 'TablesController@delete');

    //Rotas para Items
    Route::middleware('isManager')->post('items/create', 'ItemsController@store');
    Route::middleware('isManager')->patch('items/{id}', 'ItemsController@update');
    Route::middleware('isManager')->delete('items/delete/{id}', 'ItemsController@delete');
    Route::middleware('isManager')->post('items/upload', 'ImageController@uploadItemPhoto');

    //Rotas para users
    Route::middleware('isManager')->get('users', 'UserController@index');
    Route::middleware('isManager')->get('users/blocked', 'UserController@getBlockUsers');
    Route::middleware('isManager')->get('users/unblocked', 'UserController@getUnblockUsers');
    Route::middleware('isManager')->post('users/create', 'UserController@store');
    Route::put('users/{id}', 'UserController@update');
    Route::post('users/upload', 'ImageController@uploadUserPhoto');
    Route::patch('user/{id}/edit/password', 'UserController@editPassword');
    Route::middleware('isManager')->delete('users/delete/{id}', 'UserController@delete');
    Route::middleware('isManager')->patch('user/unblock/{id}', 'UserController@unblockUser');
    Route::middleware('isManager')->patch('user/block/{id}', 'UserController@blockUser');

    //Rotas para orders
    Route::middleware('isCook')->get('orders', 'OrdersController@index');
    Route::middleware('isCook')->get('cook/orders/pending', 'OrdersController@noCookOrders');
    Route::middleware('isCook')->get('cook/orders/{responsible_cook_id}', 'OrdersController@cookOrders');
    Route::middleware('isWaiterOrCook')->get('orders/meal/{id}', 'OrdersController@ordersByMeal');
    Route::middleware('isWaiterOrCook')->put('orders/{id}', 'OrdersController@update');
    Route::patch('order/change/state/confirmed/{id}', 'OrdersController@changeStateAfter5Sec');
    Route::middleware('isWaiterOrCook')->post('/orders/multiple', 'OrdersController@storeMultiple');
    Route::middleware('isWaiterOrCook')->delete('orders/delete/{id}', 'OrdersController@delete');

    Route::middleware('isWaiter')->get('/waiter/{responsible_waiter_id}/orders/pendingconfirmed', 'OrdersController@waiterPendingConfirmedOrders');
    Route::middleware('isWaiter')->get('/waiter/{responsible_waiter_id}/orders/prepared', 'OrdersController@waiterPreparedOrders');

    Route::middleware('isWaiter')->get('meals', 'MealsController@index');
    Route::middleware('isManager')->get('meals/state/active_terminated', 'MealsController@getMealsActiveAndTerminated');
    Route::middleware('isManager')->get('meals/state/{state}', 'MealsController@getMealsAByState');
    Route::middleware('isManager')->get('meals/filter/date/{date}', 'MealsController@getMealsAByDate');
    Route::middleware('isWaiter')->get('waiter/{id}/meals/', 'MealsController@myMeals');
    Route::middleware('isWaiter')->post('meals/create', 'MealsController@store');
    Route::middleware('isWaiter')->put('meals/{id}', 'MealsController@update');
    Route::middleware('isWaiterOrManager')->patch('/meal/{id}/change/state/terminated', 'MealsController@changeStateToNotPaid');


    Route::get('invoices/pending', 'InvoicesController@pendingInvoices');
    Route::get('invoices/all', 'InvoicesController@allInvoices');
    Route::get('invoices', 'InvoicesController@index');
    Route::get('invoices/pending', 'InvoicesController@getPendingInvoces');
    Route::get('invoice/filter/date/{date}', 'InvoicesController@getInvoicesAByDate');
    Route::get('invoices/state/{state}', 'InvoicesController@getInvoicesByState');
    Route::post('invoices/create', 'InvoicesController@store');
    Route::patch('invoice/{id}/meal/{meal_id}/change/state/pending', 'InvoicesController@changeStateToNotPaid');
    Route::patch('invoices/pending/{id}', 'InvoicesController@update');
});

Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::middleware('auth:api')->get('users/me', 'UserController@getUser');
Route::get('users/{email}', 'UserController@getUserByEmail');


Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});



