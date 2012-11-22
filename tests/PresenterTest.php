<?php

use Mockery as m;
use Feather\Presenter\Presenter;

class PresenterTest extends PHPUnit_Framework_TestCase {


	public function tearDown()
	{
		m::close();
	}


	public function testCanPreparePresenter()
	{
		$config = new Illuminate\Config\Repository(m::mock('Illuminate\Config\LoaderInterface'), 'production');
		$config->getLoader()->shouldReceive('load')->once()->with('production', 'forum', 'feather')->once()->andReturn(array('theme' => 'foo'));
		$files = m::mock('Illuminate\Filesystem');
		$files->shouldReceive('exists')->once()->andReturn(false);
		$view = m::mock('Illuminate\View\ViewManager');
		$view->shouldReceive('addNamespace')->once()->with('feather', array('Themes/Foo/Views', 'Application/Views'));
		$presenter = new Presenter($config, $files, $view);
		$presenter->prepare(array('path.themes' => 'Themes', 'path' => 'Application'));
	}


}