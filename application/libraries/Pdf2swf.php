<?php
/**
 * █▒▓▒░ The FlexPaper Project
 *
 * Copyright (c) 2009 - 2011 Devaldi Ltd
 *
 * GNU GENERAL PUBLIC LICENSE Version 3 (GPL).
 *
 * The GPL requires that you not remove the FlexPaper copyright notices
 * from the user interface.
 *
 * Commercial licenses are available. The commercial player version
 * does not require any FlexPaper notices or texts and also provides
 * some additional features.
 * When purchasing a commercial license, its terms substitute this license.
 * Please see http://flexpaper.devaldi.com/ for further details.
 *
 */
class Pdf2swf
{
	private $configManager = null;
	private $CI;
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->library('fp_config');
		$this->CI->load->helper('fp_common');
	}

	/**
	 * Destructor
	 */
	function __destruct()
	{
		//echo "pdf2swf destructed\n";
	}

	/**
	 * Method:convert
	 */
	public function convert($doc, $page)
	{
		$output = array();
		$pdfFilePath = $this->CI->fp_config->getConfig('path.pdf').$doc;
		$swfFilePath = $this->CI->fp_config->getConfig('path.swf').$doc.$page.".swf";
		if (strlen($page) > 0)
			$command = $this->CI->fp_config->getConfig('cmd.conversion.splitpages');
		else
			$command = $this->CI->fp_config->getConfig('cmd.conversion.singledoc');
		$command = str_replace("{path.pdf}", $this->CI->fp_config->getConfig('path.pdf'), $command);
		$command = str_replace("{path.swf}", $this->CI->fp_config->getConfig('path.swf'), $command);
		$command = str_replace("{pdffile}", $doc, $command);
		try
		{
			if (!$this->isNotConverted($pdfFilePath, $swfFilePath))
			{
				array_push($output, utf8_encode("[Converted]"));
				return arrayToString($output);
			}
		}
		catch (Exception $ex)
		{
			array_push($output, "Error,".utf8_encode($ex->getMessage()));
			return arrayToString($output);
		}
		$return_var = 0;
		if (strlen($page) > 0)
		{
			$pagecmd = str_replace("%", $page, $command);
			$pagecmd = $pagecmd." -p ".$page;
			exec($pagecmd, $output, $return_var);
			exec(getForkCommandStart().$command.getForkCommandEnd());
		}
		else
			exec($command, $output, $return_var);
		if ($return_var == 0 || strstr(strtolower($return_var), "notice"))
		{
			$s = "[Converted]";
		}
		else
		{
			$s = "Error converting document, make sure the conversion tool is installed and that correct user permissions are applied to the SWF Path directory".$this->CI->fp_config->getDocUrl();
		}
		return $s;
	}

	/**
	 * Method:isNotConverted
	 */
	public function isNotConverted($pdfFilePath, $swfFilePath)
	{
		if (!file_exists($pdfFilePath))
		{
			throw new Exception("Document does not exist");
		}
		if ($swfFilePath == null)
		{
			throw new Exception("Document output file name not set");
		}
		else
		{
			if (!file_exists($swfFilePath))
			{
				return true;
			}
			else
			{
				if (filemtime($pdfFilePath) > filemtime($swfFilePath))
					return true;
			}
		}
		return false;
	}

}
?>
