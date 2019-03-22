<?php
namespace dpadmin\lib;

use think\facade\View;

class CyLib
{
	/**
	 * 模板渲染
	 * @param $tpl
	 * @param $param
	 * @return mixed
	 */
	public static function view($tpl, $param = [])
	{
		$viewPath = dirname(__DIR__)."/view/";
		return View::config(['view_path' => $viewPath])->fetch($tpl, $param);
	}
}