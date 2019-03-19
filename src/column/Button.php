<?php
namespace dpadmin\column;

use dpadmin\lib\CyLib;

class Button extends Base
{
	protected $href = '';

	public function href($href)
	{
		$this->href = $href;
		return $this;
	}

	public function render($data = [], $field = '')
	{
		$tmp = explode('\\', get_class($this));
		$type = array_pop($tmp);

		return CyLib::view('column/'.strtolower($type), [
				'instance' => $this
			]);
	}
}