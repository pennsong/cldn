<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class userMain extends CW_Controller
{
	private $areaArray;
	private $markArray;
	public function __construct()
	{
		parent::__construct();
		$this->_init();
	}

	private function _init()
	{
		//检查用户是否为user
		if ($this->session->userdata('type') != 'user')
		{
			show_error('无权限做此操作!');
		}
		//取得area列表
		$tmpRes = $this->db->query("SELECT * FROM area");
		$areaArray = $tmpRes->result_array();
		$this->areaArray = $areaArray;
		$areaIdList = array();
		$areaNameList = array();
		foreach ($areaArray as $area)
		{
			array_push($areaIdList, $area['id']);
			array_push($areaNameList, $area['name']);
		}
		$this->smarty->assign('areaIdList', $areaIdList);
		$this->smarty->assign('areaNameList', $areaNameList);
		//取得mark列表
		$tmpRes = $this->db->query("SELECT * FROM mark");
		$markArray = $tmpRes->result_array();
		$this->markArray = $markArray;
		$markIdList = array();
		$markNameList = array();
		foreach ($markArray as $mark)
		{
			array_push($markIdList, $mark['id']);
			array_push($markNameList, $mark['name']);
		}
		$this->smarty->assign('markIdList', $markIdList);
		$this->smarty->assign('markNameList', $markNameList);
	}

	public function index($sortType = 'mark')
	{
		//取得课程列表
		if ($sortType == 'area')
		{
			$courseList = $this->areaArray;
			foreach ($courseList as &$item)
			{
				//取得当前所属板块课程信息
				$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ?", array($item['id']));
				$item['courseArray'] = $tmpRes->result_array();
			}
		}
		else if ($sortType == 'mark')
		{
			$courseList = $this->markArray;
			foreach ($courseList as &$item)
			{
				//取得当前所属标签课程信息
				$tmpRes = $this->db->query("SELECT a.* FROM course a JOIN courseToMark b on a.id = b.course WHERE b.mark = ?", array($item['id']));
				$item['courseArray'] = $tmpRes->result_array();
			}
			print_r($courseList);
			exit;
		}
		else
		{
			show_error('无权限做此操作!');
		}
		$this->_getBoughtCourse();
		$this->smarty->display('userMain.tpl');
	}

	private function _getBoughtCourse()
	{
		//取得已购买课程列表
		$tmpRes = $this->db->query("SELECT b.id courseId, b.name courseName, c.id areaId, c.name areaName, a.expiration FROM userBuyCourse a JOIN course b on a.course = b.id JOIN area c ON b.area = c.id WHERE user = ?", array($this->session->userdata['userId']));
		$courseArray = $tmpRes->result_array();
		foreach ($courseArray as &$course)
		{
			$tmpRes = $this->db->query("SELECT a.mark, b.name markName FROM courseToMark a LEFT JOIN mark b on a.mark = b.id WHERE a.course = ?", $course['courseId']);
			$course['markList'] = $tmpRes->result_array();
		}
		$this->smarty->assign('boughtCourseList', $courseArray);
	}

}

/*end*/
