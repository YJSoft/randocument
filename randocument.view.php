<?php
class randocumentView extends randocument
{
	function init()
	{
		$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		if(!is_dir($template_path)||!$this->module_info->skin)
		{
			$this->module_info->skin = 'default';
			$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		}
		$this->setTemplatePath($template_path);
	}

	function dispRandocumentRand()
	{
		if(!$this->module_info->seleted_module_srl)
		{
			return new Object(-1, '설정을 불러 올 수 없습니다.');
		}

		$args = new stdClass();
		$args->module_srls = $this->module_info->seleted_module_srl;
		$args->sort_index = 'rand()';
		$output = executeQuery('randocument.getRandocumentToDocumentSrl', $args);
		if(!$output->toBool())
		{
			return $output;
		}

		if($output->data->document_srl)
		{
			$oDocument = getModel('document')->getDocument($output->data->document_srl);
			$link = $oDocument->getPermanentUrl();
			Context::set('getlink', $link);
		}
		else
		{
			Context::set('getlink', null);
		}
		if($this->module_info->test_mode === 'yes')
		{
			Context::set('document', $oDocument);
		}
		else
		{
			Context::set('document', null);
		}

		$this->setTemplateFile('rand');
	}
}
