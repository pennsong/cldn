<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class FPView extends CW_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('fp_config');
		$this->load->helper('fp_common');
	}

	public function noLogin_preview($doc)
	{
		$numPages = getTotalPages($this->fp_config->getConfig('path.pdf').$doc);
		if ($numPages >= 3)
		{
			$numPages = 3;
		}
		$this->smarty->assign('doc', $doc);
		$this->smarty->assign('numPages', $numPages);
		$this->smarty->display('fpView.tpl');
	}

	public function viewAll($doc)
	{
		$numPages = getTotalPages($this->fp_config->getConfig('path.pdf').$doc);
		$this->smarty->assign('doc', $doc);
		$this->smarty->assign('numPages', $numPages);
		$this->smarty->display('fpView.tpl');
	}

	public function noLogin_view($doc, $page)
	{
		$pos = strpos($doc, "/");
		$swfFilePath = $this->fp_config->getConfig('path.swf').$doc.$page.".swf";
		$pdfFilePath = $this->fp_config->getConfig('path.pdf').$doc;
		if (!validPdfParams($pdfFilePath, $doc, $page))
			echo "[Incorrect file specified]";
		else
		{
			$this->load->library('pdf2swf');
			$output = $this->pdf2swf->convert($doc, $page);
			if (rtrim($output) === "[Converted]")
			{
				if ($this->fp_config->getConfig('allowcache'))
				{
					setCacheHeaders();
				}
				if (!$this->fp_config->getConfig('allowcache') || ($this->fp_config->getConfig('allowcache') && endOrRespond()))
				{
					header('Content-type: application/x-shockwave-flash');
					header('Accept-Ranges: bytes');
					header('Content-Length: '.filesize($swfFilePath));
					echo file_get_contents($swfFilePath);
				}
			}
			else
				echo $output;
			//error messages etc
		}
	}

}

/*end*/
