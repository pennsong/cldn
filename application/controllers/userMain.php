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
		$tmpRes = $this->db->query("SELECT a.id areaId, a.name areaName, b.name bigAreaName FROM area a JOIN bigArea b ON a.bigArea = b.id ORDER BY b.name, a.name");
		$areaArray = $tmpRes->result_array();
		$this->areaArray = $areaArray;
		$areaIdList = array();
		$areaNameList = array();
		foreach ($areaArray as $area)
		{
			array_push($areaIdList, $area['areaId']);
			array_push($areaNameList, $area['areaName']);
		}
		$this->smarty->assign('areaIdList', $areaIdList);
		$this->smarty->assign('areaNameList', $areaNameList);
		//取得mark列表
		$tmpRes = $this->db->query("SELECT * FROM mark ORDER BY sortOrder");
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

	public function changePassword()
	{
		$this->load->library('form_validation');
		$this->smarty->display('userChangePassword.tpl');
	}

	public function updatePassword()
	{
		$this->lang->load('form_validation', 'chinese');
		$this->load->library('form_validation');
		$config = array(
			array(
				'field'=>'oldPassword',
				'label'=>'旧密码',
				'rules'=>'required|callback_checkOldPassword'
			),
			array(
				'field'=>'newPassword',
				'label'=>'新密码',
				'rules'=>'required|alpha_numeric|min_length[6]|max_length[20]'
			),
			array(
				'field'=>'newPasswordConfirm',
				'label'=>'新密码确认',
				'rules'=>'required|matches[newPassword]'
			)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE)
		{
			$this->smarty->display('userChangePassword.tpl');
		}
		else
		{
			//保存新密码
			$tmpRes = $this->db->query("UPDATE user SET password=? WHERE id=?", array(
				$this->input->post('newPassword'),
				$this->session->userdata('userId')
			));
			$this->_return('密码修改成功!', 'ok1', 'area');
		}
	}

	public function checkOldPassword($oldPassword)
	{
		$tmpRes = $this->db->query("SELECT COUNT(*) num FROM USER WHERE id=? AND password=?", array(
			$this->session->userdata('userId'),
			$oldPassword
		));
		if ($tmpRes->first_row()->num > 0)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('checkOldPassword', '%s 错误');
			return FALSE;
		}
	}

	public function index($sortType = 'area', $language = "all")
	{
		$languageSql = '';
		if ($language == "all")
		{
		}
		else if ($language == 'en')
		{
			$languageSql = "AND language = 'en'";
		}
		else if ($language == "ch")
		{
			$languageSql = "AND language = 'ch'";
		}
		//取得课程列表
		if ($sortType == 'area')
		{
			//取得bigArea列表
			$tmpRes = $this->db->query("SELECT * FROM bigArea ORDER BY sortOrder");
			$bigAreaArray = $tmpRes->result_array();
			foreach ($bigAreaArray as &$bigArea)
			{
				$tmpRes = $this->db->query("SELECT * FROM area WHERE bigArea = ? ORDER BY sortOrder", array($bigArea['id']));
				$bigArea['areaArray'] = $tmpRes->result_array();
				foreach ($bigArea['areaArray'] as &$area)
				{
					$area['markArray'] = $this->markArray;
					foreach ($area['markArray'] as &$mark)
					{
						$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ? AND mark = ? ".$languageSql." ORDER BY sortOrder", array(
							$area['id'],
							$mark['id']
						));
						$mark['courseList'] = $tmpRes->result_array();
						$this->_setBought($mark['courseList']);
					}
				}
			}
			$this->smarty->assign('courseAreaSortList', $bigAreaArray);
		}
		else if ($sortType == 'mark')
		{
			//取得mark列表
			$tmpRes = $this->db->query("SELECT * FROM mark ORDER BY sortOrder");
			$markArray = $tmpRes->result_array();
			foreach ($markArray as &$mark)
			{
				$tmpRes = $this->db->query("SELECT * FROM bigArea ORDER BY sortOrder");
				$mark['bigAreaArray'] = $tmpRes->result_array();
				foreach ($mark['bigAreaArray'] as &$bigArea)
				{
					$tmpRes = $this->db->query("SELECT * FROM area WHERE bigArea = ? ORDER BY sortOrder", array($bigArea['id']));
					$bigArea['areaArray'] = $tmpRes->result_array();
					foreach ($bigArea['areaArray'] as &$area)
					{
						$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ? AND mark = ? ".$languageSql." ORDER BY sortOrder", array(
							$area['id'],
							$mark['id']
						));
						$area['courseList'] = $tmpRes->result_array();
						$this->_setBought($area['courseList']);
					}
				}
			}
			$this->smarty->assign('courseMarkSortList', $markArray);
		}
		else
		{
			show_error('无权限做此操作!');
		}
		$this->_getBoughtCourse();
		$this->smarty->assign('sortType', $sortType);
		$this->smarty->display('userMain.tpl');
	}

	public function buyCourse($course)
	{
		//检查是否此课程还在有效期内
		$tmpRes = $this->db->query("SELECT COUNT(*) num FROM userBuyCourse WHERE course = ? AND expiration >= DATE(NOW()) AND user = ?", array(
			$course['id'],
			$this->session->userdata('userId')
		));
		if ($tmpRes->first_row()->num > 0)
		{
			//购买课程还在有效期内
			echo "购买失败!(您已购买过此教程,还未过期,无需购买.)";
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
				$tmpRes = $this->db->query("INSERT INTO `userBuyCourse`(`user`, `course`, `expiration`, `created`) VALUES (?, ?, DATE(NOW( ))+INTERVAL 1 YEAR, NULL)", array(
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
						echo 'ok';
					}
					else
					{
						$this->db->trans_rollback();
						echo "购买失败!(扣除积分失败,请重试)";
					}
				}
				else
				{
					$this->db->trans_rollback();
					echo "购买失败!(添加购买记录失败,请重试)";
				}
			}
			else
			{
				echo "购买失败!(积分不够,请联系客服充值后购买)";
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
		$tmpRes = $this->db->query("SELECT a.updated, a.course, b.id courseId, b.name courseName, b.path, c.id areaId, e.name bigAreaName, c.name areaName, d.id markId, d.name markName, a.expiration FROM userBuyCourse a JOIN course b on a.course = b.id JOIN area c ON b.area = c.id JOIN mark d ON b.mark = d.id JOIN bigArea e ON c.bigArea = e.id WHERE user = ? AND a.expiration >= DATE(NOW()) ORDER BY a.updated DESC", array($this->session->userdata['userId']));
		$courseArray = $tmpRes->result_array();
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
