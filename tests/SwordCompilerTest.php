<?php

use Mockery as m;
use Feather\View\Cutlass;

class CutlassCompilerTest extends PHPUnit_Framework_TestCase {


	public function tearDown()
	{
		m::close();
	}


	public function testAssignmentsAreCompiled()
	{
		$cutlass = new Cutlass($this->getFiles(), __DIR__);

		$this->assertEquals('<?php $foo = \'bar\'; ?>', $cutlass->compileString('@assign($foo, \'bar\')'));
		$this->assertEquals('<?php $foo = $bar; ?>', $cutlass->compileString('@assign($foo, $bar)'));
	}


	public function testGearEventsAreCompiled()
	{
		$cutlass = new Cutlass($this->getFiles(), __DIR__);

		$expected = '<?php echo Feather\Gear::fire(\'foo\'); ?>';

		$this->assertEquals($expected, $cutlass->compileString('@event(\'foo\')'));
	}


	public function testInlineErrorsAreCompiled()
	{
		$cutlass = new Cutlass($this->getFiles(), __DIR__);

		$expected = '<?php echo $errors->has(\'foo\') ? view("feather::errors.inline", array("error" => $errors->first(\'foo\'))) : null; ?>';

		$this->assertEquals($expected, $cutlass->compileString('@error(\'foo\')'));
	}


	public function testErrorsAreCompiled()
	{
		$cutlass = new Cutlass($this->getFiles(), __DIR__);

		$expected = '<?php echo $errors->all() ? view("feather::errors.page", array("errors" => $errors->all())) : null; ?>';

		$this->assertEquals($expected, $cutlass->compileString('@errors'));
	}


	public function getFiles()
	{
		return m::mock('Illuminate\Filesystem');
	}

}