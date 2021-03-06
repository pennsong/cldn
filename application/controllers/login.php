<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Login extends CW_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('cookie');
	}

	public function index()
	{
		$this->session->sess_destroy();
		//set user type
		$this->smarty->assign('typeId', array(
			'1',
			'2',
			'3'
		));
		$this->smarty->assign('typeName', array(
			'普通用户',
			'管理员',
			'超级管理员'
		));
		if ($this->input->cookie('type'))
		{
			$this->smarty->assign('type', $this->input->cookie('type'));
		}
		//取得通知内容
		$noticeTitle = null;
		$tmpRes = $this->db->query("SELECT * FROM notice");
		if ($tmpRes)
		{
			$noticTitle = $tmpRes->first_row()->title;
		}
		$this->smarty->assign('noticeTitle', $noticTitle);
		$this->smarty->display('login.tpl');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url()."index.php/login");
	}

	public function login2($userName = null, $password = null, $type = 1)
	{
		$this->session->sess_destroy();
		$_POST['type'] = $type;
		$_POST['userName'] = $userName;
		$_POST['password'] = $password;
		$this->validateLogin();
	}

	public function validateLogin()
	{
		$var = '';
		if ($this->_authenticate($var))
		{
			//登录成功
			$this->input->set_cookie('type', $this->input->post('type'), 3600 * 24 * 30);
			if ($this->session->userdata('type') == 'user')
			{
				redirect(base_url().'index.php/userMain');
			}
			else if ($this->session->userdata('type') == 'uploader' || $this->session->userdata('type') == 'admin')
			{
				redirect(base_url().'index.php/uploaderMain');
			}
		}
		else
		{
			//登录失败
			$this->smarty->assign('loginErrorInfo', $var);
			$this->index();
		}
	}

	private function _checkDataFormat(&$result)
	{
		$this->load->library('form_validation');
		$config = array(
			array(
				'field'=>'userName',
				'label'=>'用户名',
				'rules'=>'required|alpha_numeric|min_length[6]|max_length[20]'
			),
			array(
				'field'=>'password',
				'label'=>'密码',
				'rules'=>'required|alpha_numeric|min_length[6]|max_length[20]'
			)
		);
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('*', '<br>');
		if ($this->form_validation->run() == FALSE)
		{
			$result = validation_errors();
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function checkUsername1($str)
	{
		$r1 = preg_match("/^[\w\.]{6,15}$/", $str);
		if ($r1 == 0)
		{
			$this->form_validation->set_message('checkUsername1', '%s 只能包含英文字母，数字，下划线和点,长度为6-15.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function checkUsername2($str)
	{
		$docNum = substr_count($str, '.');
		$lineNum = substr_count($str, '_');
		if ($docNum + $lineNum > 1)
		{
			$this->form_validation->set_message('checkUsername2', '%s 只能包含一个下划线或点.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function checkUsername3($str)
	{
		$r1 = preg_match("/^\..*/", $str);
		$r2 = preg_match("/^_.*/", $str);
		$r3 = preg_match("/.*\.$/", $str);
		$r4 = preg_match("/.*_$/", $str);
		if ($r1 || $r2 || $r3 || $r4)
		{
			$this->form_validation->set_message('checkUsername3', '%s 不能以下划线或点开始或结束.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function _authenticate(&$var)
	{
		$this->lang->load('form_validation', 'chinese');
		//check data format
		if (!($this->_checkDataFormat($result)))
		{
			$var = $result;
			return FALSE;
		}
		else if ($this->input->post('type') == 1)
		{
			//删除同一id登录的其他session
			$tmpRes = $this->db->query("DELETE FROM ci_sessions WHERE username=? AND userType='user'", array(strtolower($this->input->post('userName'))));
			if ($tmpRes)
			{
				$tmpRes = $this->db->query('SELECT * FROM user WHERE userName = ? AND expiration >= DATE(NOW())', strtolower($this->input->post('userName')));
				if ($tmpRes)
				{
					if ($tmpRes->num_rows() > 0)
					{
						$tmpArr = $tmpRes->first_row('array');
						if ($tmpArr['password'] == strtolower($this->input->post('password')))
						{
							$this->session->set_userdata('userName', strtolower($this->input->post('userName')));
							$this->session->set_userdata('userId', $tmpArr['id']);
							$this->session->set_userdata('type', 'user');
							$this->session->set_userdata('point', $tmpArr['point']);
							//插入用户名,用户类型,为防止一个id多个地方同时登录做准备
							$tmpRes = $this->db->query("UPDATE ci_sessions SET username=?, userType='user' WHERE session_id=?", array(
								strtolower($this->input->post('userName')),
								$this->session->userdata('session_id')
							));
							if ($tmpRes)
							{
								return TRUE;
							}
							else
							{
								//创建session失败
								$var = "*系统忙碌,请重试";
								return FALSE;
							}
						}
						else
						{
							//密码错误
							$var = "*密码错误，请仔细检查";
							return FALSE;
						}
					}
					else
					{
						//用户名不存在
						$var = "*无此用户,或用户已过期,请重新输入";
						return FALSE;
					}
				}
				else
				{
					//查询失败
					$var = "*系统繁忙，请稍后尝试进入";
					return FALSE;
				}
			}
			else
			{
				//删除同一id登录的session失败
				$var = "*系统忙碌,请重试";
				return FALSE;
			}
		}
		else if ($this->input->post('type') == 2)
		{
			$tmpRes = $this->db->query('SELECT * FROM uploader WHERE userName = ?', strtolower($this->input->post('userName')));
			if ($tmpRes)
			{
				if ($tmpRes->num_rows() > 0)
				{
					$tmpArr = $tmpRes->first_row('array');
					if ($tmpArr['password'] == strtolower($this->input->post('password')))
					{
						$this->session->set_userdata('userName', strtolower($this->input->post('userName')));
						$this->session->set_userdata('userId', $tmpArr['id']);
						$this->session->set_userdata('type', 'uploader');
						return TRUE;
					}
					else
					{
						//密码错误
						$var = "*密码错误，请仔细检查";
						return FALSE;
					}
				}
				else
				{
					//用户名不存在
					$var = "*无此用户,请重新输入";
					return FALSE;
				}
			}
			else
			{
				//查询失败
				$var = "*系统繁忙，请稍后尝试进入";
				return FALSE;
			}
		}
		else if ($this->input->post('type') == 3)
		{
			$tmpRes = $this->db->query('SELECT * FROM admin WHERE userName = ?', strtolower($this->input->post('userName')));
			if ($tmpRes)
			{
				if ($tmpRes->num_rows() > 0)
				{
					$tmpArr = $tmpRes->first_row('array');
					if ($tmpArr['password'] == strtolower($this->input->post('password')))
					{
						$this->session->set_userdata('userName', strtolower($this->input->post('userName')));
						$this->session->set_userdata('userId', $tmpArr['id']);
						$this->session->set_userdata('type', 'admin');
						return TRUE;
					}
					else
					{
						//密码错误
						$var = "*密码错误，请仔细检查";
						return FALSE;
					}
				}
				else
				{
					//用户名不存在
					$var = "*无此用户,请重新输入";
					return FALSE;
				}
			}
			else
			{
				//查询失败
				$var = "*系统繁忙，请稍后尝试进入";
				return FALSE;
			}
		}
		else
		{
			//错误的用户类型
			$var = "*用户类型不合法";
			return FALSE;
		}
	}

	public function help()
	{
		$this->smarty->display('loginHelp.tpl');
	}

	public function detail($course)
	{
		$tmpRes = $this->db->query("SELECT * FROM course WHERE id = ?", array($course));
		if ($tmpRes->num_rows() > 0)
		{
			$this->smarty->assign("course", $tmpRes->first_row('array'));
			$this->smarty->display('detail.tpl');
		}
		else
		{
			show_error('无此课程');
		}
	}

	public function getNotice()
	{
		$tmpRes = $this->db->query("SELECT * FROM notice");
		if ($tmpRes->num_rows() > 0)
		{
			$this->smarty->assign("notice", $tmpRes->first_row('array'));
			$this->smarty->display('notice.tpl');
		}
		else
		{
			show_error('无通知');
		}
	}

	public function getNoticeTitle()
	{
		//取得通知标题
		$noticeTitle = '无通知';
		$tmpRes = $this->db->query("SELECT * FROM notice");
		if ($tmpRes)
		{
			$noticTitle = $tmpRes->first_row()->title;
		}
		echo $noticeTitle;
	}

}

/*end*/
