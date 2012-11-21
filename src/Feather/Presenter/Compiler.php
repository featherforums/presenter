<?php namespace Feather\Presenter;

use Illuminate\View\Compilers\BladeCompiler;

class Compiler extends BladeCompiler {

	/**
	 * Compile the given Blade template contents.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function compileString($value)
	{
		$this->compilers = array_merge($this->compilers, array(
			'Assignments',
			'GearEvents',
			'InlineErrors',
			'Errors'
		));

		return parent::compileString($value);
	}

	/**
	 * Compile Knife assignments into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileAssignments($value)
	{
		return preg_replace('/(\s*)@assign\s*\(\$(.*), (.*)\)(\s*)/', '$1<?php $$2 = $3; ?>$4', $value);
	}

	/**
	 * Compile Knife gear events into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileGearEvents($value)
	{
		$pattern = $this->createMatcher('event');

		return preg_replace($pattern, '$1<?php echo Feather\Gear::fire$2; ?>', $value);
	}

	/**
	 * Compile Knife inline errors into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileInlineErrors($value)
	{
		$pattern = $this->createMatcher('error');

		return preg_replace($pattern, '$1<?php echo $errors->has$2 ? view("feather::errors.inline", array("error" => $errors->first$2)) : null; ?>', $value);
	}

	/**
	 * Compile Knife errors into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileErrors($value)
	{
		return str_replace('@errors', '<?php echo $errors->all() ? view("feather::errors.page", array("errors" => $errors->all())) : null; ?>', $value);
	}

}