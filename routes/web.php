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

//Route::get('oauth', 'Auth\OAuthController@redirectToProvider');
//Route::get('oauth/callback', 'Auth\OAuthController@handleProviderCallback');

Route::feeds();

Route::group(['domain' => '127.0.0.1'], function() {
    Route::get('profiles', 'FlutterProController@index');
    Route::get('profiles/sitemap.xml', 'FlutterProController@sitemap');
    Route::get('profiles/{handle}/json', 'FlutterProController@json')->middleware('cors');
    Route::get('profiles/{handle}', 'FlutterProController@show');
    Route::get('search_pro', 'FlutterProController@search');
    Route::get('flutterx', 'FlutterArtifactController@index');
    Route::get('search', 'FlutterArtifactController@search');
    Route::get('flutterx/sitemap.xml', 'FlutterArtifactController@sitemap');
    Route::get('flutterx/{flutter_artifact}/hide', 'FlutterArtifactController@hide');
    Route::get('flutterx/{flutter_artifact}', 'FlutterArtifactController@show');
    Route::get('flutter-events/feed', 'FlutterEventController@jsonFeed');
    Route::get('flutter-events/sitemap.xml', 'FlutterEventController@sitemap');
    Route::get('flutter-events/{flutter_event}', 'FlutterEventController@show');
    Route::get('flutter-streams', 'FlutterStreamController@index');
    Route::get('flutter-streams/feed', 'FlutterStreamController@jsonFeed');
    Route::get('{flutter_stream}/hide', 'FlutterStreamController@hideChannel');
    Route::get('{flutter_stream}/show', 'FlutterStreamController@showChannel');
    Route::get('{flutter_stream}/lang', 'FlutterStreamController@englishChannel');
    Route::get('search_streams', 'FlutterStreamController@search');
    //Route::get('{flutter_stream}', 'FlutterStreamController@show');
});

Route::group(['domain' => 'www.flutterpro.dev'], function() {
    Route::get('/', function() {
        return redirect('https://flutterpro.dev');
    });
});

Route::group(['domain' => 'www.flutterx.com'], function() {
    Route::get('/', function() {
        return redirect('https://flutterx.com');
    });
});

Route::group(['domain' => 'www.flutterevents.com'], function() {
    Route::get('/', function() {
        return redirect('https://itsallwidgets.com');
    });
});

Route::group(['domain' => 'www.flutterstreams.com'], function() {
    Route::get('/', function() {
        return redirect('https://flutterstreams.com');
    });
});

Route::group(['domain' => '{subdomain}.flutterpro.{tld}'], function() {
    Route::get('/', 'FlutterProController@index');
    Route::get('feed', 'FlutterProController@jsonFeed');
    Route::get('search_pro', 'FlutterProController@search');
    Route::get('{handle}/json', 'FlutterProController@json')->middleware('cors');
    Route::get('sitemap.xml', 'FlutterProController@sitemap');
    Route::get('{handle}', 'FlutterProController@show');
});

/*
Route::group(['domain' => '{subdomain}.flutterevents.{tld}'], function() {
    Route::get('/', 'FlutterEventController@index');
    Route::get('feed', 'FlutterEventController@jsonFeed');
    Route::get('flutter-groups', 'FlutterEventController@groups');
    Route::get('sitemap.xml', 'FlutterEventController@sitemap');
    Route::get('{flutter_event}/hide', 'FlutterEventController@hide');
    Route::get('{flutter_event}', 'FlutterEventController@show');
});
*/

Route::group(['domain' => '{subdomain}.flutterstreams.{tld}'], function() {
    Route::get('/', 'FlutterStreamController@index');
    Route::get('feed', 'FlutterStreamController@jsonFeed');
    Route::get('search_streams', 'FlutterStreamController@search');
    //Route::get('sitemap.xml', 'FlutterStreamController@sitemap');
    Route::get('{flutter_stream}/hide', 'FlutterStreamController@hideChannel');
    Route::get('{flutter_stream}/show', 'FlutterStreamController@showChannel');
    Route::get('{flutter_stream}/lang', 'FlutterStreamController@englishChannel');
    //Route::get('{flutter_stream}', 'FlutterStreamController@show');
});

Route::group(['domain' => '{subdomain}.flutterx.{tld}'], function() {
    Route::get('/', 'FlutterArtifactController@index');
    Route::get('feed', 'FlutterArtifactController@jsonFeed');
    Route::get('search', 'FlutterArtifactController@search');
    Route::get('update', 'FlutterArtifactController@update');
    Route::get('sitemap.xml', 'FlutterArtifactController@sitemap');
    Route::get('{flutter_artifact}', 'FlutterArtifactController@show');
});

Route::group(['domain' => '{subdomain}.geu.{tld}'], function() {
    Route::get('/', 'FlutterArtifactController@index');
    Route::get('search', 'FlutterArtifactController@search');
    Route::get('update', 'FlutterArtifactController@update');
    Route::get('sitemap.xml', 'FlutterArtifactController@sitemap');
    Route::get('{flutter_artifact}/hideme', 'FlutterArtifactController@hide');
    Route::get('{flutter_artifact}', 'FlutterArtifactController@show');
});

Route::get('flutter-apps', function () {
    return redirect('/', 301);
});

Route::get('slides', function () {
    return redirect('https://docs.google.com/presentation/d/1u82DeBozavR31n3xjmwmwl2CT8YsG7u8ltQv1ltxw34/edit', 301);
});

Route::get('30days', function () {
    if (auth()->check()) {
        return redirect('/submit/30days');
    } else {
        return redirect('/auth/google?intended_url=submit/30days');
    }
});

Route::get('/', 'FlutterAppController@index');
Route::get('feed', 'FlutterAppController@jsonFeed');
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

Route::get('flutter-event-click/{flutter_event}/{click_type}', 'FlutterEventController@trackClicked');

Route::get('podcast', 'PodcastController@index');
Route::get('podcast/download/episode-{episode}.{format}', 'PodcastController@download');
Route::get('podcast/episodes/{episode}/{title?}', 'PodcastController@show');

Route::get('flutter-events', 'FlutterEventController@index');

Route::group(['middleware' => ['auth']], function () {
    Route::get('profile', 'FlutterProController@show');
    Route::put('profile', 'FlutterProController@update');
    Route::get('profile/edit', 'FlutterProController@edit');

    Route::get('cards', 'FlutterProController@cards');
    Route::get('badge', 'FlutterAppController@badge');
    Route::get('submit/{campaign?}', 'FlutterAppController@create');
    Route::post('submit', 'FlutterAppController@store')->middleware('slug');

    Route::get('flutter-app/{flutter_app}/hide', 'FlutterAppController@hide');
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

Route::get('flutter-app/{slug}', function ($slug) {
    return redirect($slug);
});

Route::get('{slug}', 'FlutterAppController@show');
