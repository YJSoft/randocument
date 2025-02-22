<?php
class randocumentModel extends randocument
{
	function getRandocumentToDocumentSrl($module_srl)
	{
		if(!$module_srl)
		{
			return false;
		}

		$args = new stdClass();
		$args->module_srls = $module_srl;
		$output = executeQuery('randocument.getRandocumentToDocumentSrl', $args);

		return $output;
	}
}