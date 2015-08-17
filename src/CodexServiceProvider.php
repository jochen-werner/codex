<?php
/**
* Part of the Caffeinated PHP packages.
*
* MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Codex;

use Caffeinated\Beverage\ServiceProvider;
use Codex\Codex\Filters\FrontMatterFilter;
use Codex\Codex\Filters\ParsedownFilter;
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

    /**
     * @var array
     */
    protected $viewDirs = [ 'views' => 'codex' ];

    /**
     * @var array
     */
    protected $assetDirs = [ 'assets' => 'codex' ];

    /**
     * @var array
     */
    protected $providers = [
        \Caffeinated\Beverage\BeverageServiceProvider::class,
        Providers\ConsoleServiceProvider::class,
        Providers\RouteServiceProvider::class
    ];

    /**
     * @var array
     */
    protected $singletons = [
        Contracts\Factory::class => Factory::class,
        Contracts\Menus\MenuFactory::class => Menus\MenuFactory::class
    ];

    /**
     * @var array
     */
    protected $aliases = [
        Contracts\Factory::class => 'codex',
        Contracts\Menus\MenuFactory::class => 'codex.menus'
    ];

    /**
     * Register bindings in the container.
     *
     * @return Application
     */
    public function register()
    {
        $app = parent::register();
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
}
