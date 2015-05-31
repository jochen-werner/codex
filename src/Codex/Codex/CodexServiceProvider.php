<?php
namespace Codex\Codex;

use App;
use Illuminate\Support\ServiceProvider;

class CodexServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__.'/../../config/codex.php' => config_path('codex.php')
		]);
	}

	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/../../config/codex.php', 'codex');

		$this->registerServices();

		$this->bindInterfaces();
	}

	/**
	 * Register services.
	 *
	 * @return void
	 */
	protected function registerServices()
	{
		$this->app->register('Codex\Codex\Providers\RouteServiceProvider');
	}

	/**
	 * Bind interfaces to their respective repositories.
	 *
	 * @return void
	 */
	protected function bindInterfaces()
	{
		$this->app->bind(
			'Codex\Codex\Repositories\Interfaces\CodexRepositoryInterface',
			'Codex\Codex\Repositories\Flat\CodexRepository'
		);
	}
}
