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
    'root_dir' => env('CODEX_ROOT_DIR', base_path('resources/docs')),

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
    'base_route' => env('CODEX_BASE_ROUTE', 'codex'),

    /*
    |--------------------------------------------------------------------------
    | Default Project
    |--------------------------------------------------------------------------
    |
    */
    'default_project' => env('CODEX_DEFAULT_PROJECT', 'codex'),

    /*
    |--------------------------------------------------------------------------
    | Project menus view composer
    |--------------------------------------------------------------------------
    |
    */
    'projects_menus_view_composer' => 'Codex\\Codex\\Http\\ViewComposers\\ProjectMenusComposer',

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
        'title' => 'Documentation',
        'subtitle' => '',
        'layout' => 'codex::layouts/default',
        'view' => 'codex::document'
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
