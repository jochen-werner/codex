<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Storage Driver
    |--------------------------------------------------------------------------
    |
    | Codex can support a multitude of different storage methods to retrieve
    | your documentation from. You may specify which one you're using
    | throughout your Codex installation here. By default, Codex is set to
    | use the "flat" driver method.
    |
    | Supported: "flat", "git"
    |
    */

    'driver' => 'flat',

    /*
    |--------------------------------------------------------------------------
    | Storage Path
    |--------------------------------------------------------------------------
    |
    */

    'storage_path' => public_path('docs'),

    /*
    |--------------------------------------------------------------------------
    | Site name
    |--------------------------------------------------------------------------
    | Will be displayed in the header
    */

    'site_name' => 'Codex',

    /*
    |--------------------------------------------------------------------------
    | Default Project
    |--------------------------------------------------------------------------
    |
    */

    'default_project' => '',

    /*
    |--------------------------------------------------------------------------
    | Last Modified Timestamp Format
    |--------------------------------------------------------------------------
    |
    | http://php.net/manual/en/function.date.php#refsect1-function.date-parameters
    |
    */

    'modified_timestamp' => 'l, F d, Y',

    /*
    |--------------------------------------------------------------------------
    | Route Base
    |--------------------------------------------------------------------------
    |
    | You may define a base route for your Codex documentation here. By default
    | it is set to "codex", but you may leave this empty if you wish to use
    | Codex as a stand alone application.
    */

    'route_base' => 'codex'

);
