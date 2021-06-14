<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'TicketController@index')->name('home');
Route::group(['middleware' => ['web'], 'prefix' => 'ticket'], function()
{
    Route::post('/', 'TicketController@store')->name('store_ticket');
    Route::get('/{id}', 'TicketController@show');
    
});

