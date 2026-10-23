<?php

/*
|--------------------------------------------------------------------------
| Export Routes
|--------------------------------------------------------------------------
|
| The new app copies this app's data through these routes. They only answer
| on the legacy host, and every request must be signed (see VerifyIawExport).
|
*/

Route::get('manifest', 'ExportController@manifest');
Route::get('tables/{table}/ids', 'ExportController@ids')->where('table', '[a-z_]+');
Route::get('tables/{table}', 'ExportController@rows')->where('table', '[a-z_]+');
Route::get('ours/{table}', 'ExportController@ours')->where('table', '[a-z_]+');
Route::get('media', 'ExportController@media');
Route::get('mp3/{file}', 'ExportController@mp3')->where('file', '[^/]+');
Route::post('maintenance', 'ExportController@maintenance');
