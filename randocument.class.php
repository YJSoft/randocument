<?php

class randocument extends ModuleObject
{
	public function getConfig()
	{
		$config = getModel('module')->getModuleConfig('randocument');
		if (!is_object($config))
		{
			$config = new stdClass();
		}
		return $config;
	}

	function moduleInstall()
	{

	}

	function checkUpdate()
	{

	}

	function moduleUpdate()
	{

	}
	function recompileCache()
	{

	}


}