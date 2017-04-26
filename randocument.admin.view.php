<?php

class randocumentAdminView extends randocument
{
	function init()
	{
		$oModuleModel = getModel('module');
		$module_srl = Context::get('module_srl');

		if(!$module_srl && $this->module_srl)
		{
			$module_srl = $this->module_srl;
			Context::set('module_srl', $module_srl);
		}

		if($module_srl)
		{
			$module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
			if(!$module_info)
			{
				Context::set('module_srl', '');
			}
			else
			{
				$oModuleModel->syncModuleToSite($module_info);
				$module_info->mid_list = explode('|@|', $module_info->mid_list);
				$module_info->notify_list = explode('|@|', $module_info->notify_list);
				$this->module_info = $module_info;
				Context::set('module_info', $module_info);
			}
		}
		if($module_info && $module_info->module != 'randocument')
		{
			return $this->stop('msg_invalid_request');
		}

		$module_config = $this->getConfig();
		$module_category = $oModuleModel->getModuleCategories();

		$order_target = array();
		if(is_array($this->order_target))
		{
			foreach($this->order_target as $key)
			{
				$order_target[$key] = Context::getLang($key);
			}
		}

		$order_target['list_order'] = Context::getLang('regdate');
		$order_target['update_order'] = Context::getLang('last_update');

		Context::set('module_config', $module_config);
		Context::set('module_category', $module_category);
		Context::set('order_target', $order_target);

		$security = new Security();
		$security->encodeHTML('module_info.');
		$security->encodeHTML('module_config.');
		$security->encodeHTML('module_category..');

		$this->setTemplatePath($this->module_path . 'tpl');
		$this->setTemplateFile(strtolower(str_replace('dispRandocumentAdmin', '', $this->act)));
	}

	function dispRandocumentAdminDashboard()
	{
		$args = new stdClass();
		$args->sort_index = 'module_srl';
		$args->list_count = 20;
		$args->page_count = 10;
		$args->page = Context::get('page');
		$args->module_category_srl = Context::get('module_category_srl');

		$search_target = Context::get('search_target');
		$search_keyword = Context::get('search_keyword');

		switch($search_target)
		{
			case 'mid':
				$args->mid = $search_keyword;
				break;
			case 'browser_title':
				$args->browser_title = $search_keyword;
				break;
		}

		$oModuleModel = getModel('module');

		$output = executeQueryArray('randocument.getRandocumentModuleList', $args);
		$oModuleModel->syncModuleToSite($output->data);

		$skin_list = $oModuleModel->getSkins($this->module_path);
		$mskin_list = $oModuleModel->getSkins($this->module_path, 'm.skins');

		$oLayoutModel = getModel('layout');
		$layout_list = $oLayoutModel->getLayoutList();
		$mlayout_list = $oLayoutModel->getLayoutList(0, 'M');

		Context::set('page', $output->page);
		Context::set('total_page', $output->total_page);
		Context::set('total_count', $output->total_count);
		Context::set('skin_list', $skin_list);
		Context::set('mskin_list', $mskin_list);
		Context::set('layout_list', $layout_list);
		Context::set('mlayout_list', $mlayout_list);
		Context::set('randocument_list', $output->data);
		Context::set('page_navigation', $output->page_navigation);

		$oModuleAdminModel = getAdminModel('module');
		$selected_manage_content = $oModuleAdminModel->getSelectedManageHTML($this->xml_info->grant, array('tab1'=>1, 'tab3'=>1));
		Context::set('selected_manage_content', $selected_manage_content);

		$security = new Security();
		$security->encodeHTML('skin_list..title', 'mskin_list..title');
		$security->encodeHTML('layout_list..title', 'layout_list..layout');
		$security->encodeHTML('mlayout_list..title', 'mlayout_list..layout');
	}

	function dispRandocumentAdminInsertMid()
	{
		// get the skins list
		$oModuleModel = getModel('module');
		$skin_list = $oModuleModel->getSkins($this->module_path);
		Context::set('skin_list',$skin_list);

		$mskin_list = $oModuleModel->getSkins($this->module_path, "m.skins");
		Context::set('mskin_list', $mskin_list);

		// get the layouts list
		$oLayoutModel = getModel('layout');
		$layout_list = $oLayoutModel->getLayoutList();
		Context::set('layout_list', $layout_list);

		$mobile_layout_list = $oLayoutModel->getLayoutList(0,"M");
		Context::set('mlayout_list', $mobile_layout_list);

		$security = new Security();
		$security->encodeHTML('skin_list..title','mskin_list..title');
		$security->encodeHTML('layout_list..title','layout_list..layout');
		$security->encodeHTML('mlayout_list..title','mlayout_list..layout');

		$security = new Security();
		$security->encodeHTML('extra_vars..name','list_config..name');
	}

	function dispRandocumentAdminDeleteMid()
	{
		$module_srl = Context::get('module_srl');

		if(!$module_srl)
		{
			return $this->setRedirectUrl(getNotEncodedUrl('', 'module', 'admin', 'act', 'dispRandocumentAdminDashboard'));
		}

		$security = new Security();
		$security->encodeHTML('module_info..module', 'module_info..mid');
	}
}