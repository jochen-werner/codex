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

    'storage_path' => public_path().'/docs',

    /*
    |--------------------------------------------------------------------------
    | Site name
    |--------------------------------------------------------------------------
    | Will be displayed in the header
    */

    'site_name' => 'Codex',

    /*
    |--------------------------------------------------------------------------
    | Default Manual
    |--------------------------------------------------------------------------
    |
    */

    'default_manual' => '',

    /*
    |--------------------------------------------------------------------------
    | Version Ordering
    |--------------------------------------------------------------------------
    |
    | Allows you to define if you wish the versions be ordered by numerics
    | first (2.0, 1.0, master), or alphabetically (master, 2.0, 1.0).
    |
    | Supported: "numeric-first", "alpha-first"
    |
    */

    'version_ordering' => 'alpha-first',

    /*
    |--------------------------------------------------------------------------
    | Google Analytics Tracking Code
    |--------------------------------------------------------------------------
    |
    | If you'd like to track analytical data on your visitors, add your
    | Universal Analytics tracking code below. Otherwise, leave this as
    | null.
    |
    */

    'tracking_code' => getenv('tracking_code'),

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
