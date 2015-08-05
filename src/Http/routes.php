<?php

/*
|--------------------------------------------------------------------------
| Codex Routes
|--------------------------------------------------------------------------
|
*/

Route::get(Config::get('codex.base_route').'/', 'CodexController@index');
Route::get(Config::get('codex.base_route').'/{project}/{version?}/{page?}', 'CodexController@show')
    ->where('page', '(.*)');
