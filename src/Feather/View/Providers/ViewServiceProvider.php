<?php namespace Feather\View\Providers;

use Feather\View\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function register($app)
	{
		$app['feather']['view'] = $app->share(function() use ($app)
		{
			return new View($app['config'], $app['files'], $app['view']);
		});
	}

}