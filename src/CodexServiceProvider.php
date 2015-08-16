<?php
namespace Codex\Codex;

use Caffeinated\Beverage\ServiceProvider;
use Codex\Codex\Filters\FrontMatterFilter;
use Codex\Codex\Filters\ParsedownFilter;
use Codex\Codex\Http\ViewComposers\ProjectMenusComposer;
use Codex\Codex\Providers\RouteServiceProvider;
use Codex\Codex\Traits\CodexProviderTrait;
use Illuminate\Contracts\Foundation\Application;

/**
 * Codex service provider.
 *
 * @package   Codex\Codex
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class CodexServiceProvider extends ServiceProvider
{
    use CodexProviderTrait;

    /**
     * @var string
     */
    protected $dir = __DIR__;

    /**
     * Collection of configuration files.
     *
     * @var array
     */
    protected $configFiles = [ 'codex' ];

    /**
     * Collection of bound instances.
     *
     * @var array
     */
    protected $provides = [ 'codex' ];

    protected $viewDirs = [ 'views' => 'codex' ];

    protected $assetDirs = [ 'views' => 'codex' ];

    public function boot()
    {
        $app = parent::boot();

        $this->loadViewsFrom(realpath(__DIR__ . '/../resources/views'), 'codex');

        $this->publishes([ realpath(__DIR__ . '/../resources/assets') => public_path('vendor/codex') ], 'public');

        $app->make('view')->composer([ 'codex::document' ], ProjectMenusComposer::class);
    }


    /**
     * Register bindings in the container.
     *
     * @return Application
     */
    public function register()
    {
        $app = parent::register();

        $this->app->singleton('codex', Factory::class);

        $this->registerRoute();

        $this->registerFilters();
    }

    /**
     * Register the core filters.
     *
     * @return void
     */
    protected function registerFilters()
    {
        $this->addCodexFilter('front_matter', FrontMatterFilter::class);
        $this->addCodexFilter('parsedown', ParsedownFilter::class);
    }

    /**
     * If enabled, register the provided HTTP routes.
     *
     * @return void
     */
    protected function registerRoute()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
