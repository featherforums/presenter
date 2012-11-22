<?php namespace Feather\Presenter;

use Illuminate\Filesystem;
use Illuminate\View\ViewManager;
use Illuminate\Config\Repository;

class Presenter {

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
	 * @var Illuminate\View\ViewManager
	 */
	protected $view;

	/**
	 * Create a new theme instance.
	 * 
	 * @param  Illuminate\Config\Repository  $config
	 * @param  Illuminate\Filesystem  $files
	 * @param  Illuminate\View\ViewManager  $view
	 * @return void
	 */
	public function __construct(Repository $config, Filesystem $files, ViewManager $view)
	{
		$this->config = $config;
		$this->files = $files;
		$this->view = $view;
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
			$paths['path.themes'].'/'.ucfirst($this->config['feather::forum.theme']).'/Views',
			$paths['path'].'/Views'
		);

		$this->view->addNamespace('feather', $hints);

		// If the theme has a start file require the file to bootstrap the theme.
		$start = $paths['path.themes'].'/'.ucfirst($this->config['feather::forum.theme']).'/start.php';

		if ($this->files->exists($start))
		{
			require $start;
		}
	}

}