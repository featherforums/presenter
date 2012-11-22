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
		$app = new Illuminate\Container;
		$app['config'] = new Illuminate\Config\Repository(m::mock('Illuminate\Config\LoaderInterface'), 'production');
		$app['config']->getLoader()->shouldReceive('load')->once()->with('production', 'forum', 'feather')->once()->andReturn(array('theme' => 'foo'));
		$app['files'] = m::mock('Illuminate\Filesystem');
		$app['files']->shouldReceive('exists')->once()->andReturn(false);
		$app['view'] = m::mock('Illuminate\View\ViewManager');
		$app['view']->shouldReceive('addNamespace')->once()->with('feather', array('Themes/Foo/Views', 'Application/Views'));
		$presenter = new Presenter($app);
		$presenter->prepare(array('path.themes' => 'Themes', 'path' => 'Application'));
	}


}