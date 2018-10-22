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

		$oRandocumentModel = getModel('randocument');

		$output = $oRandocumentModel->getRandocumentToDocumentSrl($this->module_info->seleted_module_srl);
		if(!$output->toBool())
		{
			return $output;
		}
		
		if($output->data->document_srl)
		{
			/** @var documentModel $oDocumentModel */
			$oDocumentModel = getModel('document');
			$oDocument = $oDocumentModel->getDocument($output->data->document_srl);
			$link = $oDocument->getPermanentUrl();
			
			$db_info = Context::getDBInfo();
			if($db_info->use_rewrite !== 'Y')
			{
				$link = htmlspecialchars_decode($link);
			}
			
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
