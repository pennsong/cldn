<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/**
 *  FlexPaperConfig Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	FlexPaper
 * @author		penn song
 */
class FP_config
{
	private $config;
	public function __construct()
	{
		$this->config = array(
			'test_pdf2swf'=>1,
			'test_pdf2json'=>"",
			'allowcache'=>1,
			'splitmode'=>1,
			'path.pdf'=>"/pdf/",
			'path.swf'=>"/pdfWorking/",
			'cmd.conversion.singledoc'=>"\"/opt/local/bin/pdf2swf\" \"{path.pdf}{pdffile}\" -o \"{path.swf}{pdffile}.swf\" -f -T 9 -t -s storeallcharacters",
			'cmd.conversion.splitpages'=>"\"/opt/local/bin/pdf2swf\" \"{path.pdf}{pdffile}\" -o \"{path.swf}{pdffile}%.swf\" -f -T 9 -t -s storeallcharacters -s linknameurl",
			'cmd.searching.extracttext'=>"\"/opt/local/bin/swfstrings\" \"{path.swf}{swffile}\"",
			'pdf2swf'=>1,
			'admin.username'=>"admin",
			'admin.password'=>"tcltcl",
			'licensekey'=>"",
			'cmd.conversion.renderpage'=>"",
			'cmd.conversion.rendersplitpage'=>"",
			'cmd.conversion.jsonfile'=>"",
			'renderingorder.primary'=>"",
			'renderingorder.secondary'=>""
		);
	}

	public function getConfig($str)
	{
		return $this->config[$str];
	}

	public function getDocUrl()
	{
		return '';
	}

}

/* end */
