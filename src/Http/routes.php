<?php

/*
|--------------------------------------------------------------------------
| Codex Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', ['as' => 'codex.index', 'uses' => 'CodexController@index']);
Route::get('{projectSlug}/{ref?}/{document?}', [
    'as' => 'codex.document',
    'uses' => 'CodexController@document'
])
    ->where('projectSlug', '^((?!' . \Codex\Codex\Extensions::getExcludedProjectNames(true) . ').*?)$')
    ->where('document', '(.*)');
