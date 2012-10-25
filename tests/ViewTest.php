<?php

use Mockery as m;
use Feather\View;

class ViewTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testDirectoryPathIsReturned()
	{
		$files = m::mock('Illuminate\Filesystem');
		$view = m::mock('Illuminate\Foundation\Managers\ViewManager');
		$config = m::mock('Illuminate\Config\Repository');

		$config->shouldReceive('get')->once()->andReturn('foo');

		$view = new View($config, $files, $view);

		$this->assertEquals(__DIR__ . '/Foo/Views', $view->getDirectoryPath(__DIR__));
	}


	public function testCanPrepareViews()
	{
		$files = m::mock('Illuminate\Filesystem');
		$view = m::mock('Illuminate\Foundation\Managers\ViewManager');
		$env = m::mock('Illuminate\View\Environment');
		$config = new Illuminate\Config\Repository(m::mock('Illuminate\Config\LoaderInterface'), 'production');

		$view->shouldReceive('extend')->once()->andReturn($env);
		$view->shouldReceive('addNamespace')->once()->andReturn(true);

		$files->shouldReceive('exists')->once()->andReturn(false);

		$config->getLoader()->shouldReceive('load')->once()->with('production', 'feather', null)->andReturn(array('feather.forum.theme' => 'foo'));
		$config->getLoader()->shouldReceive('load')->once()->with('production', 'view', null)->andReturn(array('view.driver' => 'foo'));

		$view = new View($config, $files, $view);

		$view->prepare(array('path' => __DIR__, 'path.themes' => __DIR__));

		$this->assertEquals('sword', $config->get('view.driver'));
	}

}