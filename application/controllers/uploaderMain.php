<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class uploaderMain extends CW_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->_init();
	}

	private function _init()
	{
		//检查用户是否为uploader或admin
		if ($this->session->userdata('type') != 'uploader' && $this->session->userdata('type') != 'admin')
		{
			show_error('无权限做此操作!');
		}
		//取得area列表
		$tmpRes = $this->db->query("SELECT b.id bigAreaId, b.name bigAreaName, a.id areaId, a.name areaName FROM area a JOIN bigArea b on a.bigArea = b.id ORDER BY b.sortOrder, a.sortOrder");
		$areaArray = $tmpRes->result_array();
		$areaIdList = array();
		$areaNameList = array();
		foreach ($areaArray as $area)
		{
			array_push($areaIdList, $area['areaId']);
			array_push($areaNameList, $area['bigAreaName'].'|'.$area['areaName']);
		}
		$this->smarty->assign('areaIdList', $areaIdList);
		$this->smarty->assign('areaNameList', $areaNameList);
		//取得mark列表
		$tmpRes = $this->db->query("SELECT * FROM mark ORDER BY sortOrder");
		$markArray = $tmpRes->result_array();
		$markIdList = array();
		$markNameList = array();
		foreach ($markArray as $mark)
		{
			array_push($markIdList, $mark['id']);
			array_push($markNameList, $mark['name']);
		}
		//初始化语言选项
		$languageValue = array(
			'ch',
			'en'
		);
		$languageName = array(
			'中文',
			'英文'
		);
		$this->smarty->assign('languageValue', $languageValue);
		$this->smarty->assign('languageName', $languageName);
		$this->smarty->assign('markIdList', $markIdList);
		$this->smarty->assign('markNameList', $markNameList);
	}

	private function _getUploadedFile($sortType)
	{
		//取得mark列表
		$tmpRes = $this->db->query("SELECT * FROM mark ORDER BY sortOrder");
		$markArray = $tmpRes->result_array();
		//取得已上传文件列表
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
					$area['markArray'] = $markArray;
					foreach ($area['markArray'] as &$mark)
					{
						if ($this->session->userdata('type') == 'uploader')
						{
							$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ? AND mark = ? AND uploader = ? ORDER BY sortOrder", array(
								$area['id'],
								$mark['id'],
								$this->session->userdata('userId')
							));
							$mark['courseList'] = $tmpRes->result_array();
						}
						else if ($this->session->userdata('type') == 'admin')
						{
							$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ? AND mark = ? ORDER BY sortOrder", array(
								$area['id'],
								$mark['id']
							));
							$mark['courseList'] = $tmpRes->result_array();
						}
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
						if ($this->session->userdata('type') == 'uploader')
						{
							$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ? AND mark = ? AND uploader = ? ORDER BY sortOrder", array(
								$area['id'],
								$mark['id'],
								$this->session->userdata('userId')
							));
							$area['courseList'] = $tmpRes->result_array();
						}
						else if ($this->session->userdata('type') == 'admin')
						{
							$tmpRes = $this->db->query("SELECT * FROM course WHERE area = ? AND mark = ? ORDER BY sortOrder", array(
								$area['id'],
								$mark['id'],
							));
							$area['courseList'] = $tmpRes->result_array();
						}
					}
				}
			}
			$this->smarty->assign('courseMarkSortList', $markArray);
		}
	}

	public function changePassword()
	{
		$this->load->library('form_validation');
		$this->smarty->display('uploaderOrAdminChangePassword.tpl');
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
			$this->smarty->display('uploaderOrAdminChangePassword.tpl');
		}
		else
		{
			//保存新密码
			if ($this->session->userdata('type') == 'uploader')
			{
				$tmpRes = $this->db->query("UPDATE uploader SET password=? WHERE id=?", array(
					$this->input->post('newPassword'),
					$this->session->userdata('userId')
				));
			}
			else if ($this->session->userdata('type') == 'admin')
			{
				$tmpRes = $this->db->query("UPDATE admin SET password=? WHERE id=?", array(
					$this->input->post('newPassword'),
					$this->session->userdata('userId')
				));
			}
			$this->smarty->assign('msg', '<span class="ok1">密码修改成功!</span>');
			$this->index();
		}
	}

	public function checkOldPassword($oldPassword)
	{
		if ($this->session->userdata('type') == 'uploader')
		{
			$tmpTable = 'uploader';
		}
		else if ($this->session->userdata('type') == 'admin')
		{
			$tmpTable = 'admin';
		}
		$tmpRes = $this->db->query("SELECT COUNT(*) num FROM ".$tmpTable." WHERE id=? AND password=?", array(
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

	public function index($sortType = 'area')
	{
		$this->_getUploadedFile($sortType);
		$this->smarty->assign('sortType', $sortType);
		$this->smarty->assign('type', 'create');
		$this->smarty->display('uploaderMain.tpl');
	}

	public function update($course, $sortType)
	{
		$this->_getUploadedFile($sortType);
		//取得course信息
		$tmpRes = $this->db->query("SELECT id course, name, language, area, sortOrder, mark, cost, introduction, list, uploader FROM course WHERE id = ?", array($course));
		$courseInfo = $tmpRes->first_row('array');
		//检查当前用户是否是文件拥有者
		if (!(($courseInfo['uploader'] == $this->session->userdata('userId') && $this->session->userdata('type') == 'uploader') || $this->session->userdata('type') == 'admin'))
		{
			show_error('无权限做此操作!');
		}
		//取得course mark信息
		$_POST = $courseInfo;
		$this->smarty->assign('type', 'update');
		$this->smarty->assign('sortType', $sortType);
		$this->smarty->display('uploaderMain.tpl');
	}

	public function delete($course, $sortType)
	{
		$this->smarty->assign('sortType', $sortType);
		//取得course信息
		$tmpRes = $this->db->query("SELECT uploader FROM course WHERE id = ?", array($course));
		//检查当前用户是否是文件拥有者
		if (!(($tmpRes->first_row()->uploader == $this->session->userdata('userId') && $this->session->userdata('type') == 'uploader') || $this->session->userdata('type') == 'admin'))
		{
			show_error('无权限做此操作!');
		}
		$this->smarty->assign('type', 'create');
		$this->_getUploadedFile($sortType);
		$this->db->trans_start();
		$tmpRes = $this->db->query("DELETE FROM course WHERE id = ?", array($course));
		if ($tmpRes)
		{
			$this->db->trans_commit();
			$this->_getUploadedFile($sortType);
			$this->_return("文件删除成功", "ok1");
		}
		else
		{
			//删除course失败
			$this->db->trans_rollback();
			$this->_return("删除文件失败,请重试!", "error1");
		}
	}

	public function uploadSubmit($sortType)
	{
		$this->smarty->assign('sortType', $sortType);
		$this->_getUploadedFile($sortType);
		$this->smarty->assign('type', $this->input->post('type'));
		$this->lang->load('form_validation', 'chinese');
		//检查数据格式
		if (!($this->_checkDataFormat($result, $this->input->post('type'))))
		{
			$this->_return($result, "error1");
		}
		else
		{
			if ($this->input->post('type') == 'create')
			{
				$errors = array();
				$data = "";
				$success = "false";
				$uploadRoot = "/pdf";
				$file_temp = $_FILES['fileName']['tmp_name'];
				$dateStamp = date("Y_m_d");
				$fine_original_name = $_FILES['fileName']['name'];
				$file_name = $dateStamp."_".$fine_original_name;
				//complete upload
				$filestatus = move_uploaded_file($file_temp, $uploadRoot."/".$file_name);
				if (!$filestatus)
				{
					//上传文件失败
					$this->_return("上传文件失败,请重试!", "error1");
				}
				else
				{
					//上传文件成功继续处理文件相关信息
					$this->db->trans_start();
					$tmpRes = $this->db->query("INSERT INTO `course`(`uploader`, `name` ,`language`, `path`, `area`, `sortOrder`, `mark`, `cost`, `list`, `introduction`, `created`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, null)", array(
						$this->session->userdata('userId'),
						$fine_original_name,
						$this->input->post('language'),
						$file_name,
						$this->input->post('area'),
						$this->input->post('sortOrder'),
						$this->input->post('mark'),
						$this->input->post('cost'),
						$this->input->post('list'),
						$this->input->post('introduction')
					));
					if ($tmpRes)
					{
						$this->db->trans_commit();
						$this->_getUploadedFile($sortType);
						$_POST = NULL;
						$this->_return("文件上传成功!", "ok1");
					}
					else
					{
						//添加文件信息失败
						$this->db->trans_rollback();
						$this->_return("上传文件信息失败,请重试!", "error1");
					}
				}
			}
			else if ($this->input->post('type') == 'update')
			{
				$this->db->trans_start();
				$tmpRes = $this->db->query("UPDATE `course` SET `language` = ?, `area`= ?, `sortOrder` = ?, `mark`= ?, `cost`= ?, `list`= ?, `introduction`= ? WHERE id = ?", array(
					$this->input->post('language'),
					$this->input->post('area'),
					$this->input->post('sortOrder'),
					$this->input->post('mark'),
					$this->input->post('cost'),
					$this->input->post('list'),
					$this->input->post('introduction'),
					$this->input->post('course')
				));
				if ($tmpRes)
				{
					$this->db->trans_commit();
					$this->_getUploadedFile($sortType);
					$this->smarty->assign('type', 'create');
					$_POST = NULL;
					$this->_return("修改文件成功!", "ok1");
				}
				else
				{
					//修改文件信息失败
					$this->db->trans_rollback();
					$this->_return("修改文件信息失败,请重试!", "error1");
				}
			}
		}
	}

	private function _checkDataFormat(&$result, $type)
	{
		$this->load->library('form_validation');
		if ($type == 'create')
		{
			$config = array( array(
					'field'=>'fileName',
					'label'=>'上传文件',
					'rules'=>'callback_validateUploadFile'
				));
		}
		else if ($type == 'update')
		{
			$config = array();
		}
		else
		{
			return FALSE;
		}
		$config = array_merge($config, array(
			array(
				'field'=>'language',
				'label'=>'语言',
				'rules'=>'required'
			),
			array(
				'field'=>'area',
				'label'=>'板块',
				'rules'=>'required'
			),
			array(
				'field'=>'sortOrder',
				'label'=>'排序',
				'rules'=>'required|is_natural'
			),
			array(
				'field'=>'mark',
				'label'=>'标签',
				'rules'=>'required'
			),
			array(
				'field'=>'cost',
				'label'=>'分数',
				'rules'=>'required|is_natural_no_zero'
			),
			array(
				'field'=>'introduction',
				'label'=>'概述',
				'rules'=>'required'
			),
			array(
				'field'=>'list',
				'label'=>'目录',
				'rules'=>'required'
			)
		));
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('*', '<br />');
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

	public function validateUploadFile()
	{
		if ($_FILES['fileName']['name'] == NULL)
		{
			$this->form_validation->set_message('validateUploadFile', '上传文件不能为空');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function _return($str, $css)
	{
		$this->smarty->assign('msg', '<span class="'.$css.'">'.$str.'</span>');
		$this->smarty->display('uploaderMain.tpl');
		exit ;
	}

}

/*end*/
