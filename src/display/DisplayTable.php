<?php
namespace dpadmin\display;

use dpadmin\lib\CyLib;
use think\facade\Request;

class DisplayTable extends Base
{
    public static $handle = null;

    public $columns = [];
    public $searchs = [];
    public $actions = [];
    public $buttons = [];

    public $title = '';

    protected $disableAction = false;
	protected $disableEdit = false;
	protected $disableCreate = false;
	protected $disableDelete = false;

    public $model = '';
    public $model_handle = null;

    public $model_user_query = null;

    public $pagezie = 8;

    public function getPage()
    {
        return Request::get('p', 1);
    }

    public function query($callback)
    {
        $this->model_user_query = $callback;
        return $this;
    }

    public function pagesize($pagesize)
    {
        $this->pagesize = $pagesize;
        return $this;
    }

	public function disableDelete()
	{
		$this->disableDelete = true;
		return $this;
	}

	public function disableEdit()
	{
		$this->disableEdit = true;
		return $this;
	}

	public function disableCreate()
	{
		$this->disableCreate = true;
		return $this;
	}

	public function disableAction()
	{
		$this->disableAction = true;
		return $this;
	}

    /**
     * @param $callback
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function callback($callback)
    {
        call_user_func($callback, $this);

        $this->model_handle = model($this->model)->where([]);

        $searchField = [];
        foreach ($this->searchs as $search) {
            if(isset($search->id) && Request::get($search->id)){
                $this->model_handle->whereLike($search->id, '%'.Request::get($search->id).'%');
            }
        }

        //处理用户自定义的 查询条件
        if($this->model_user_query instanceof \Closure){
            call_user_func($this->model_user_query, $this->model_handle);
        }

        $datas = $this->model_handle->paginate($this->pagezie, false, $searchField);

        $searchHtml = CyLib::view('/display/search', ['instance' => $this]);

        $tableHtml = CyLib::view('/column/tr', [
            'datas' => $datas,
            'instance' => $this,
            'pk' => $this->model_handle->getPk(),
            'model' => $this->model_handle,
        ]);

	    list($parent_path_id, $son_path_id) = $this->getCurrentMenu();

        return CyLib::view('/display/table', [
	        'parent_path_id' => $parent_path_id,
	        'son_path_id' => $son_path_id,
            'instance' => $this,
            'title' => $this->title,
            'table_content' => $tableHtml,
            'search_content' => $searchHtml,
        ]);
    }
}