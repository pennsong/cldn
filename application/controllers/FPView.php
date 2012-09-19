<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class FPView extends CW_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('FP_config');
		$this->load->helper('fp_common');
	}

	private function _getTotalPages($course)
	{
		$tmpRes = $this->db->query("SELECT path FROM course WHERE id=?", array($course));
		if ($tmpRes->num_rows() > 0)
		{
			$doc = $tmpRes->first_row()->path;
			$numPages = getTotalPages($this->fp_config->getConfig('path.pdf').$doc);
			return $numPages;
		}
		else
		{
			show_error('无此课程!');
		}
	}

	public function noLogin_preview($course)
	{
		$this->smarty->assign('course', $course);
		$this->smarty->assign('numPages', $this->_getTotalPages($course));
		$this->smarty->display('fpView.tpl');
	}

	public function viewAll($course)
	{
		$this->smarty->assign('course', $course);
		$this->smarty->assign('numPages', $this->_getTotalPages($course));
		$this->smarty->display('fpView.tpl');
	}

	public function noLogin_checkAccessRight($course)
	{
		if ($this->session->userdata('type') == 'user')
		{
			$tmpRes = $this->db->query("SELECT count(*) num FROM userBuyCourse WHERE course=? AND user=? AND expiration>=DATE(NOW())", array(
				$course,
				$this->session->userdata('userId')
			));
			if ($tmpRes->first_row()->num > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else if ($this->session->userdata('type') == 'uploader' || $this->session->userdata('type') == 'admin')
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function noLogin_view($course, $page)
	{
		//检查阅读权限
		if ($page <= 5 || $this->noLogin_checkAccessRight($course))
		{
			$tmpRes = $this->db->query("SELECT path FROM course WHERE id=?", array($course));
			if ($tmpRes->num_rows() > 0)
			{
				$doc = $tmpRes->first_row()->path;
				$pos = strpos($doc, "/");
				$swfFilePath = $this->fp_config->getConfig('path.swf').$doc.$page.".swf";
				$pdfFilePath = $this->fp_config->getConfig('path.pdf').$doc;
				if (!validPdfParams($pdfFilePath, $doc, $page))
					echo "[Incorrect file specified]";
				else
				{
					$this->load->library('Pdf2swf');
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
			else
			{
				show_error('无此课程!');
			}
		}
		else
		{
			$swfFilePath = $this->fp_config->getConfig('path.swf').'noAccess.pdf'.".swf";
			header('Content-type: application/x-shockwave-flash');
			header('Accept-Ranges: bytes');
			header('Content-Length: '.filesize($swfFilePath));
			echo file_get_contents($swfFilePath);
		}
	}

}

/*end*/
