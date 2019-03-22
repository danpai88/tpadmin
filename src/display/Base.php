<?php
namespace dpadmin\display;

use think\facade\Request;

class Base
{
    public static $handle = null;
    public $menus = [];

    /**
     * @param $model
     * @throws
     * @return $this
     */
    public static function model($model)
    {
        if(is_null(static::$handle)){
            static::$handle = new static();
        }

        static::$handle->model = $model;

        return static::$handle;
    }

	/**
	 * 设置列表页面的标题
	 * @param string $title
	 * @return $this
	 */
    public function title($title = '')
    {
        $this->title = $title;
        return $this;
    }

	/**
	 * 获取所以菜单
	 * @return array|string|\think\Collection
	 * @throws
	 */
	public function getMenus()
	{
		$this->menus = model('CyMenus')->where('pid', 0)->order('order asc')->select();
		foreach ($this->menus as $key => $menu) {
			$this->menus[$key]['items'] = model('CyMenus')->where('pid', $menu['id'])->order('order asc')->select();
		}
		return $this->menus;
	}

	/**
	 * 获取当前父子按钮ID
	 * @throws
	 */
	public function getCurrentMenu()
	{
		$menus = $this->getMenus();
		$parent_path_id = 0;
		$son_path_id = 0;

		$current_url = strtolower(Request::controller().'/index');

		foreach ($menus as $s_menus) {
			foreach ($s_menus['items'] as $s_menu) {
				$s_menu['url'] = strtolower($s_menu['url']);

				if(stristr($s_menu['url'], strtolower(Request::controller()).'/')){
					$parent_path_id = $s_menus['id'];
					if($s_menu['url'] == $current_url){
						$son_path_id = $s_menu['id'];
						break;
					}
				}
			}
		}
		return [$parent_path_id, $son_path_id];
	}

	public function __get($name)
	{
		return $this->$name;
	}
}