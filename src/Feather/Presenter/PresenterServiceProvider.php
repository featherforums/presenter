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
		$app['feather']['presenter'] = $app->share(function() use ($app)
		{
			return new Presenter($app);
		});
		
		$this->registerCompiler($app);

		$this->registerCommands($app);
	}

	/**
	 * Register the view compiler.
	 * 
	 * @return void
	 */
	public function registerCompiler($app)
	{
		$app['view']->addExtension('blade.php', 'feather.presenter', function() use ($app)
		{
			// The Compiler used by Feather is an extension to the Blade compiler. Feather
			// has a few special methods that are used throughout views that need to be compiled
			// alongside the default Blade methods.
			$compiler = new Compiler($app['files'], $app['path'].'/storage/views');
			
			return new CompilerEngine($compiler, $app['files']);
		});
	}

	/**
	 * Register the console commands.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function registerCommands($app)
	{
		$app['command.feather.publish.theme'] = $app->share(function($app)
		{
			return new Console\PublishThemeCommand($app, $app['path.base'].'/public/feather/themes');
		});

		$app['events']->listen('artisan.start', function($artisan)
		{
			$artisan->resolve('command.feather.publish.theme');
		});
	}

}