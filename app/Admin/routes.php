<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('properties', PropertyController::class);
    $router->resource('property-descriptions', PropertyDescriptionController::class);
    $router->resource('property-images', PropertyImageController::class);
    $router->resource('subscriptions', SubscriptionController::class);
    $router->resource('price-controls', PriceControlController::class);
    $router->resource('orders', OrderController::class);

});
