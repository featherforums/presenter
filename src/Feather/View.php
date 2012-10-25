<?php namespace Feather;

use Feather\Sword;
use Illuminate\Filesystem;
use Illuminate\View\Environment;
use Illuminate\Config\Repository;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\Foundation\Managers\ViewManager;

class View {

	/**
	 * Illuminate application instance.
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $config;

	/**
	 * Filesystem instance.
	 * 
	 * @var Illuminate\Filesystem
	 */
	protected $files;

	/**
	 * Illuminate view manager instance.
	 * 
	 * @var Illuminate\Foundation\Managers\ViewManager
	 */
	protected $view;

	/**
	 * Create a new theme instance.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct(Repository $config, Filesystem $files, ViewManager $view)
	{
		$this->config = $config;
		$this->files = $files;
		$this->view = $view;
	}

	/**
	 * Get the path to the themes view directory.
	 * 
	 * @return string
	 */
	public function getDirectoryPath($path)
	{
		return $path . '/' . ucfirst($this->config->get('feather.forum.theme')) . '/Views';
	}

	/**
	 * Prepare the theme by requiring a starter file if it exists.
	 * 
	 * @return void
	 */
	public function prepare($paths)
	{
		$this->registerSwordCompiler();

		// Assign a namespace and some cascading paths so that view files are first searched
		// for within a theme then within the core view directory.
		$namespace = array($this->getDirectoryPath($paths['path.themes']), $paths['path'] . '/Feather/Views');

		$this->view->addNamespace('feather', $namespace);

		// If the theme has a starter file require the file to bootstrap the theme.
		$starter = $paths['path.themes'] . '/' . ucfirst($this->config->get('feather.forum.theme')) . '/start.php';

		if ($this->files->exists($starter))
		{
			require $starter;
		}
	}

	/**
	 * Register the sword compiler as the default view driver.
	 * 
	 * @return void
	 */
	public function registerSwordCompiler()
	{
		$this->view->extend('sword', function($app)
		{
			// The Compiler engine requires an instance of the CompilerInterface, which in
			// this case will be the Blade compiler, so we'll first create the compiler
			// instance to pass into the engine so it can compile the views properly.
			$compiler = new Sword($app['files'], $app['config']['view.cache']);

			$engine = new CompilerEngine($compiler, $app['files'], $app['config']['view.paths'], '.blade.php');

			return new Environment($engine, $app['events']);
		});

		$this->config->set('view.driver', 'sword');
	}

}