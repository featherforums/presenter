<?php

use Mockery as m;
use Feather\View\View;

class ViewTest extends PHPUnit_Framework_TestCase {


	public function tearDown()
	{
		m::close();
	}


	public function testDirectoryPathIsReturned()
	{
		$files = m::mock('Illuminate\Filesystem');
		$view = m::mock('Illuminate\Foundation\Managers\ViewManager');
		$config = new Illuminate\Config\Repository(m::mock('Illuminate\Config\LoaderInterface'), 'production');
		$config->getLoader()->shouldReceive('load')->once()->with('production', 'forum', 'feather')->andReturn(array('theme' => 'foo'));
		$view = new View($config, $files, $view);
		$this->assertEquals(__DIR__.'/Foo/Views', $view->getDirectoryPath(__DIR__));
	}


	public function testCanRegisterCompiler()
	{
		$files = m::mock('Illuminate\Filesystem');
		$view = m::mock('Illuminate\Foundation\Managers\ViewManager');
		$env = m::mock('Illuminate\View\Environment');
		$config = new Illuminate\Config\Repository(m::mock('Illuminate\Config\LoaderInterface'), 'production');
		$config->getLoader()->shouldReceive('load')->once()->with('production', 'view', null)->andReturn(array('driver' => 'foo'));
		$this->assertEquals('foo', $config->get('view.driver'));
		$view->shouldReceive('extend')->once();
		$view = new View($config, $files, $view);
		$view->registerCompiler(array('path' => __DIR__, 'path.themes' => __DIR__));
		$this->assertEquals('feather::view.compiler', $config->get('view.driver'));
	}

}