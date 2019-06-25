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

Route::feeds();

Route::group(['domain' => 'flutterevents.{env}'], function() {
    Route::get('/', 'FlutterEventController@index');
    Route::get('feed', 'FlutterEventController@jsonFeed');
    Route::get('sitemap.xml', 'FlutterEventController@sitemap');
});

Route::group(['domain' => 'itsallwidgets.{env}'], function() {
    Route::get('flutter-apps', function () {
        return redirect('/', 301);
    });

    Route::get('/', 'FlutterAppController@index');
    Route::get('about', 'HomeController@about');
    Route::get('terms', 'HomeController@terms');
    Route::get('sitemap.xml', 'FlutterAppController@sitemap');

    Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
    Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

    Route::get('login', function () {
        return redirect('/');
    })->name('login');

    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('log_error', 'HomeController@logError');

    // TODO change to RSS feeds
    Route::get('event/feed', 'FlutterEventController@jsonFeed');
    Route::get('event/feed/json', 'FlutterEventController@jsonFeed');

    Route::get('flutter-app/{slug}', 'FlutterAppController@show');
    Route::get('flutter-event-click/{flutter_event}/{click_type}', 'FlutterEventController@trackClicked');

    Route::get('podcast', 'PodcastController@index');
    Route::get('podcast/download/episode-{episode}.{format}', 'PodcastController@download');
    Route::get('podcast/episodes/{episode}/{title?}', 'PodcastController@show');

    Route::get('flutter-events', 'FlutterEventController@index');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('submit', 'FlutterAppController@create');
        Route::post('submit', 'FlutterAppController@store')->middleware('slug');

        Route::get('flutter-app/{flutter_app}/feature', 'FlutterAppController@feature');
        Route::get('flutter-app/{flutter_app}/approve', 'FlutterAppController@approve');
        Route::get('flutter-app/{flutter_app}/reject', 'FlutterAppController@reject');
        Route::get('flutter-app/{flutter_app}/edit', 'FlutterAppController@edit');
        Route::put('flutter-app/{flutter_app}', 'FlutterAppController@update');

        Route::get('podcast/submit', 'PodcastController@create');
        Route::post('podcast', 'PodcastController@store');
        Route::get('podcast/admin/{episode_id}', 'PodcastController@edit');
        Route::put('podcast/admin/{episode_id}', 'PodcastController@update');
        Route::delete('podcast/admin/{episode_id}/delete', 'PodcastController@delete');

        Route::get('flutter-event/submit', 'FlutterEventController@create');
        Route::post('flutter-event', 'FlutterEventController@store')->middleware('slug');
        Route::get('flutter-event/{flutter_event}/approve', 'FlutterEventController@approve');
        Route::get('flutter-event/{flutter_event}/reject', 'FlutterEventController@reject');
        Route::get('flutter-event/{flutter_event}/edit', 'FlutterEventController@edit');
        Route::put('flutter-event/{flutter_event}', 'FlutterEventController@update');
    });

});
