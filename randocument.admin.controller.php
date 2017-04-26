<?php

class randocumentAdminController extends randocument
{
	function procRandocumentAdminInsertMid()
	{
		$oModuleModel = getModel('module');
		$oModuleController = getController('module');

		$args = Context::getRequestVars();
		$args->module = 'randocument';
		if(is_array($args->mid_list))
		{
			$args->mid_list = implode('|@|', $args->mid_list);
		}
		if(is_array($args->notify_list))
		{
			$args->notify_list = implode('|@|', $args->notify_list);
		}
		if($args->module_srl)
		{
			$module_info = $oModuleModel->getModuleInfoByModuleSrl($args->module_srl);
			if ($module_info->module_srl != $args->module_srl)
			{
				unset($args->module_srl);
			}
		}
		if($args->module_srl)
		{
			$output = $oModuleController->updateModule($args);
			$msg_code = 'success_updated';
		}
		else
		{
			$output = $oModuleController->insertModule($args);
			$msg_code = 'success_registed';
		}

		if(!$output->toBool())
		{
			return $output;
		}

		$this->setMessage($msg_code);

		// 반환 URL로 돌려보낸다.
		if (Context::get('success_return_url'))
		{
			$this->setRedirectUrl(Context::get('success_return_url'));
		}
		else
		{
			$this->setRedirectUrl(getNotEncodedUrl('', 'module', 'admin', 'act', 'dispRandocumentAdminInsertMid', 'module_srl', $args->module_srl));
		}
	}

	function procRandocumentAdminDeleteMid()
	{
		$module_srl = Context::get('module_srl');

		$oModuleController = getController('module');
		$output = $oModuleController->deleteModule($module_srl);
		if(!$output->toBool())
		{
			return $output;
		}

		$this->setMessage('success_deleted');

		// 반환 URL로 돌려보낸다.
		if (Context::get('success_return_url'))
		{
			$this->setRedirectUrl(Context::get('success_return_url'));
		}
		else
		{
			$this->setRedirectUrl(getNotEncodedUrl('', 'module', 'admin', 'act', 'dispRandocumentAdminDeleteMid'));
		}
	}
}