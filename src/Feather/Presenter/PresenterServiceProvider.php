<?php namespace Feather\Presenter;

use Illuminate\View\Environment;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;

class PresenterServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register($app)
	{
		$this->registerCompiler($app);

		$app['feather']['presenter'] = $app->share(function() use ($app)
		{
			return new Presenter($app);
		});
	}

	/**
	 * Register the view compiler.
	 * 
	 * @return void
	 */
	public function registerCompiler($app)
	{
		$app['view']->extend('feather.presenter', function($app)
		{
			// The Compiler used by Feather is an extension to the Blade compiler. Feather
			// has a few special methods that are used throughout views that need to be compiled
			// alongside the default Blade methods.
			$compiler = new Compiler($app['files'], $app['path'].'/storage/views');

			$engine = new CompilerEngine($compiler, $app['files'], $app['config']['view.paths'], '.blade.php');

			return new Environment($engine, $app['events']);
		});

		$app['config']['view.driver'] = 'feather.presenter';
	}

}