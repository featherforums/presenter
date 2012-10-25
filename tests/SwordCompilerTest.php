<?php

use Mockery as m;
use Feather\Sword;

class SwordCompilerTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testAssignmentsAreCompiled()
	{
		$knife = new Sword($this->getFiles(), __DIR__);

		$this->assertEquals('<?php $foo = \'bar\'; ?>', $knife->compileString('@assign($foo, \'bar\')'));
		$this->assertEquals('<?php $foo = $bar; ?>', $knife->compileString('@assign($foo, $bar)'));
	}


	public function testGearEventsAreCompiled()
	{
		$knife = new Sword($this->getFiles(), __DIR__);

		$expected = '<?php echo Feather\Gear::fire(\'foo\'); ?>';

		$this->assertEquals($expected, $knife->compileString('@event(\'foo\')'));
	}


	public function testInlineErrorsAreCompiled()
	{
		$knife = new Sword($this->getFiles(), __DIR__);

		$expected = '<?php echo $errors->has(\'foo\') ? view("feather::errors.inline", array("error" => $errors->first(\'foo\'))) : null; ?>';

		$this->assertEquals($expected, $knife->compileString('@error(\'foo\')'));
	}


	public function testErrorsAreCompiled()
	{
		$knife = new Sword($this->getFiles(), __DIR__);

		$expected = '<?php echo $errors->all() ? view("feather::errors.page", array("errors" => $errors->all())) : null; ?>';

		$this->assertEquals($expected, $knife->compileString('@errors'));
	}


	public function getFiles()
	{
		return m::mock('Illuminate\Filesystem');
	}

}