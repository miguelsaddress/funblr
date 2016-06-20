<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', 'FeedController@index')->name('feed')->middleware('auth');
Route::group(['prefix' => 'feed/count', 'middleware' => 'cors'], function() {

    Route::get('views', 'FeedController@viewCount')->name('feed.count.views');
    Route::get('posts', 'FeedController@postCount')->name('feed.count.posts');
});

Route::group(['prefix' => 'export', 'middleware' => 'auth'], function () {

    Route::get('excel', 'ExportController@downloadAsExcel')->name('export.excel');
    Route::get('csv', 'ExportController@downloadAsCsv')->name('export.csv');
    Route::get('zip', 'ExportController@downloadAsZip')->name('export.zip');
});

Route::group(['prefix' => 'api/v1' , 'middleware' => 'cors'], function() {
    Route::get('posts', 'PostsController@index')->name('posts');
    Route::get('post/{post}', 'PostsController@show');
    Route::post('post', 'PostsController@store')->name('post.store');
    
    Route::group(['prefix' => 'feed/count'], function() {
        Route::get('views', '\Funblr\Http\Controllers\FeedController@viewCount')->name('feed.count.views');
        Route::get('posts', '\Funblr\Http\Controllers\FeedController@postCount')->name('feed.count.posts');
    });

    Route::get('export/bulk', '\Funblr\Http\Controllers\ExportController@bulkAsUrl')->name('export.bulk');
});