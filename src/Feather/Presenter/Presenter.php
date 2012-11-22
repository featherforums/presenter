<?php namespace Feather\Presenter;

class Presenter {

	/**
	 * Illuminate application instance.
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Create a new theme instance.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Prepare the theme by requiring a starter file if it exists.
	 * 
	 * @return void
	 */
	public function prepare($paths)
	{
		// Assign a namespace and some cascading paths so that view files are first searched
		// for within a theme then within the core view directory.
		$hints = array(
			$paths['path.themes'].'/'.ucfirst($this->app['config']['feather::forum.theme']).'/Views',
			$paths['path'].'/Views'
		);

		$this->app['view']->addNamespace('feather', $hints);

		// If the theme has a start file require the file to bootstrap the theme.
		$start = $paths['path.themes'].'/'.ucfirst($this->app['config']['feather::forum.theme']).'/start.php';

		if ($this->app['files']->exists($start))
		{
			require $start;
		}
	}

}