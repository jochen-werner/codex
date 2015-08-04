<?php
namespace Codex\Codex;

use Caffeinated\Beverage\ServiceProvider;
use Codex\Codex\Filters\FrontMatterFilter;
use Codex\Codex\Filters\ParsedownFilter;
use Codex\Codex\Traits\CodexHookProvider;

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
    use CodexHookProvider;

    /**
     * @var string
     */
    protected $dir = __DIR__;

    /**
     * @var array
     */
    protected $configFiles = ['codex'];

    /**
     * Perform the post-registration booting of services.
     *
     * @return Application
     */
    public function boot()
    {
        parent::boot();
        
        $this->app->make('codex');
    }

    /**
     * Register bindings in the container.
     *
     * @return Application
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('codex', 'Codex\Codex\Factory');

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
        $this->app->register('Codex\Codex\Providers\RouteServiceProvider');
    }
}
