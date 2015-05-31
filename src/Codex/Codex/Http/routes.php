<?php

/*
|--------------------------------------------------------------------------
| Codex Routes
|--------------------------------------------------------------------------
|
*/

Route::get(Config::get('codex.route_base').'/', 'CodexController@index');
Route::get(Config::get('codex.route_base').'/{manual}/{version?}/{page?}', 'CodexController@show')
	->where('page', '(.*)');
