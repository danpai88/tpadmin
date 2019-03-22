<?php
namespace dpadmin;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\helper\Time;

class DpadminInit extends Command
{
	protected function configure()
	{
		$this->setName('dpadmin:make')
			->addArgument("d")
			->setDescription("init some dpadmin cli");
	}

	/**
	 * 执行命令
	 * @param Input $input
	 * @param Output $output
	 * @return int|void|null
	 * @throws
	 */
	protected function execute(Input $input, Output $output)
	{
		$this->moveAssetDir();
		$this->initDb();
	}

	protected function initDb()
	{

	}

	protected function moveAssetDir()
	{
		$assetDir = dirname(__DIR__)."/admin-lte";
		shell_exec(sprintf("mv %s %s", $assetDir, app()->getRootPath()."/public/"));
	}
}
