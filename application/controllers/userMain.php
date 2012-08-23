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
		$tmpRes = $this->db->query("SELECT * FROM area ORDER BY name");
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
		$tmpRes = $this->db->query("SELECT * FROM mark ORDER BY name");
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

	public function index($sortType = 'area')
	{
		//取得课程列表
		if ($sortType == 'area')
		{
			$courseList = $this->areaArray;
			foreach ($courseList as &$item)
			{
				//取得当前所属板块课程信息
				$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ? ORDER BY name", array($item['id']));
				$item['courseArray'] = $tmpRes->result_array();
				//判断是否在有效购买期内
				$this->_setBought($item['courseArray']);
			}
		}
		else if ($sortType == 'mark')
		{
			$courseList = $this->markArray;
			foreach ($courseList as &$item)
			{
				//取得当前所属标签课程信息
				$tmpRes = $this->db->query("SELECT a.* FROM course a JOIN courseToMark b on a.id = b.course WHERE b.mark = ? ORDER BY a.name", array($item['id']));
				$item['courseArray'] = $tmpRes->result_array();
				//判断是否在有效购买期内
				$this->_setBought($item['courseArray']);
			}
		}
		else
		{
			show_error('无权限做此操作!');
		}
		$this->_getBoughtCourse();
		$this->smarty->assign('sortType', $sortType);
		$this->smarty->assign('courseList', $courseList);
		$this->smarty->display('userMain.tpl');
	}

	public function buyCourse($course, $sortType)
	{
		//检查是否此课程还在有效期内
		$tmpRes = $this->db->query("SELECT COUNT(*) num FROM userBuyCourse WHERE course = ? AND expiration >= DATE(NOW()) AND user = ?", array(
			$course['id'],
			$this->session->userdata('userId')
		));
		if ($tmpRes->first_row()->num > 0)
		{
			//购买课程还在有效期内
			$this->_return("购买失败!(您已购买过此教程,还未过期,无需购买.)", ".error1", $sortType);
		}
		else
		{
			//没有购买此课程还在有效期内, 处理购买
			//取得此课程价格
			$tmpRes = $this->db->query("SELECT cost FROM course WHERE id = ?", array($course));
			$cost = $tmpRes->first_row()->cost;
			//检查用户是否有足够积分
			$tmpRes = $this->db->query("SELECT point FROM user WHERE id = ?", array($this->session->userdata('userId')));
			$point = $tmpRes->first_row()->point;
			if ($point >= $cost)
			{
				$this->db->trans_start();
				//添加购买记录
				$tmpRes = $this->db->query("INSERT INTO `userBuyCourse`(`user`, `course`, `expiration`, `created`) VALUES (?, ?, DATE(NOW( ))+INTERVAL 3 MONTH, NULL)", array(
					$this->session->userdata('userId'),
					$course
				));
				if ($tmpRes)
				{
					//扣除积分
					$tmpRes = $this->db->query("UPDATE `user` SET `point`= ? WHERE id = ?", array(
						($point - $cost),
						$this->session->userdata('userId')
					));
					if ($tmpRes)
					{
						$this->db->trans_commit();
						$this->session->set_userdata('point', ($point - $cost));
						$this->_return("购买成功!", ".ok1", $sortType);
					}
					else
					{
						$this->db->trans_rollback();
						$this->_return("购买失败!(扣除积分失败,请重试)", ".error1", $sortType);
					}
				}
				else
				{
					$this->db->trans_rollback();
					$this->_return("购买失败!(添加购买记录失败,请重试)", ".error1", $sortType);
				}
			}
			else
			{
				$this->_return("购买失败!(积分不够,请联系客服充值后购买)", ".error1", $sortType);
			}
		}
	}

	private function _setBought(&$courseArray)
	{
		foreach ($courseArray as &$course)
		{
			$tmpRes = $this->db->query("SELECT COUNT(*) num FROM userBuyCourse WHERE course = ? AND expiration >= DATE(NOW()) AND user = ?", array(
				$course['id'],
				$this->session->userdata('userId')
			));
			if ($tmpRes->first_row()->num > 0)
			{
				$course['bought'] = 'yes';
			}
			else
			{
				$course['bought'] = 'no';
			}
		}
	}

	private function _getBoughtCourse()
	{
		//取得已购买课程列表
		$tmpRes = $this->db->query("SELECT b.id courseId, b.name courseName, b.path, c.id areaId, c.name areaName, a.expiration FROM userBuyCourse a JOIN course b on a.course = b.id JOIN area c ON b.area = c.id WHERE user = ? AND a.expiration >= DATE(NOW()) ORDER BY c.name", array($this->session->userdata['userId']));
		$courseArray = $tmpRes->result_array();
		foreach ($courseArray as &$course)
		{
			$tmpRes = $this->db->query("SELECT a.mark, b.name markName FROM courseToMark a LEFT JOIN mark b on a.mark = b.id WHERE a.course = ?", $course['courseId']);
			$course['markList'] = $tmpRes->result_array();
		}
		$this->smarty->assign('boughtCourseList', $courseArray);
	}

	private function _return($str, $css, $sortType)
	{
		$this->smarty->assign('msg', '<span class="'.$css.'">'.$str.'</span>');
		$this->index($sortType);
		exit ;
	}

}

/*end*/
