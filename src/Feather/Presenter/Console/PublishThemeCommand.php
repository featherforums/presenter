<?php namespace Feather\Presenter\Console;

use DirectoryIterator;
use Illuminate\Filesystem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class PublishThemeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'feather:publish:theme';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish a theme\'s assets';

	/**
	 * Illuminate application instance.
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Path to publishing path.
	 * 
	 * @var string
	 */
	protected $publishPath;

	/**
	 * Create a new PublishThemeCommand instance.
	 * 
	 * @param  Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct($app, $publishPath)
	{
		parent::__construct();

		$this->app = $app;
		$this->publishPath = $publishPath;
	}

	/**
	 * Execute the console command.
	 * 
	 * @return void
	 */
	public function fire()
	{
		// If no theme name is provided as an argument then we'll confirm with the user that they
		// really do want to publish all of the themes.
		if ( ! $this->input->getArgument('name'))
		{
			if ($this->confirm('Are you sure you want to publish all of the themes?'))
			{
				$this->publishAll();
			}
		}
		else
		{
			$this->publish($this->input->getArgument('name'));
		}

		$this->line('');
	}

	/**
	 * Publish all themes.
	 * 
	 * @return void
	 */
	public function publishAll()
	{
		$this->line('');

		$published = 0;

		foreach (new DirectoryIterator($this->app['feather']['path.themes']) as $file)
		{
			if ($file->isDot()) continue;

			if ($this->publish($file->getFilename()))
			{
				$published++;
			}
		}

		$this->line('');

		if ($published > 0)
		{
			$this->line('Themes published: '.$published);
		}
		else
		{
			$this->line('There was nothing to publish.');
		}
	}

	/**
	 * Publishes a theme's assets.
	 * 
	 * @param  string  $name
	 * @return void
	 */
	public function publish($name)
	{
		$sourcePath = $this->app['feather']['path.themes'].'/'.$name.'/public';
		$destinationPath = $this->publishPath.'/'.$name;

		// We can only publish a themes assets if it has a public directory. All of the files inside this
		// directory will be moved to within the applications public directory.
		if ($this->app['files']->exists($sourcePath))
		{
			if ($this->app['files']->copyDirectory($sourcePath, $destinationPath))
			{
				$this->info("Theme '{$name}' was published successfully.");

				return true;
			}
			else
			{
				$this->error("Theme '{$name}' failed to publish.");
			}
		}
		else
		{
			$this->comment("Theme '{$name}' has nothing to publish.");
		}

		return false;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::OPTIONAL, 'The name of the theme to publish'),
		);
	}

}