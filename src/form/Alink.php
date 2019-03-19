<?php
namespace dpadmin\form;

class Alink extends Base
{
	public $href = '';

	public function href($href)
	{
		$this->href = $href;
		return $this;
	}
}