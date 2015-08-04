<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Display name
    |--------------------------------------------------------------------------
    |
    */
    'display_name' => 'Codex',

    /*
    |--------------------------------------------------------------------------
    | Root directory
    |--------------------------------------------------------------------------
    |
    */
    'root_dir' => base_path('resources/docs'),

    /*
    |--------------------------------------------------------------------------
    | Route Base
    |--------------------------------------------------------------------------
    |
    | You may define a base route for your Codex documentation here. By default
    | it is set to "codex", but you may leave this empty if you wish to use
    | Codex as a stand alone application.
    |
    */
    'base_route' => 'codex',

    /*
    |--------------------------------------------------------------------------
    | Default Project
    |--------------------------------------------------------------------------
    |
    */
    'default_project' => 'codex',

    /*
    |--------------------------------------------------------------------------
    | Default Project Attributes
    |--------------------------------------------------------------------------
    |
    | These values will be merged with any frontmatter attributes your
    | documentation pages may have. Feel free to add or remove any
    | attributes as you see fit for your documentation needs.
    |
    */
    'default_document_attributes' => [
        'author' => 'John Doe',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Project Configuration
    |--------------------------------------------------------------------------
    |
    | These are the default settings used to pre-populate all project
    | configuration files.
    |
    */
    'default_project_config' => [
        'default'          => Codex\Codex\Project::SHOW_LAST_VERSION_OTHERWISE_MASTER_BRANCH,
        'custom'           => null,
        'filters'          => ['front_matter', 'parsedown'],
        'filters_settings' => ['front_matter' => [], 'parsedown' => []]
    ]
];
