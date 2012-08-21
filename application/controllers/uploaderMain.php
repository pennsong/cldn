<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class uploaderMain extends CW_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->init();
		$this->output->enable_profiler(TRUE);
	}

	public function init()
	{
		//取得area列表
		$tmpRes = $this->db->query("SELECT * FROM area");
		$areaArray = $tmpRes->result_array();
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

	public function getUploadedFile()
	{
		//取得已上传文件列表
		$tmpRes = $this->db->query("SELECT a.id course, a.name courseName, a.area, b.name areaName FROM course a LEFT JOIN area b ON a.area = b.id WHERE a.uploader = ? ORDER BY a.area, courseName", array($this->session->userdata['userId']));
		$fileArray = $tmpRes->result_array();
		foreach ($fileArray as &$file)
		{
			$tmpRes = $this->db->query("SELECT a.mark, b.name markName FROM courseToMark a LEFT JOIN mark b on a.mark = b.id WHERE a.course = ?", $file['course']);
			$file['markList'] = $tmpRes->result_array();
		}
		$this->smarty->assign('uploadedFileList', $fileArray);
	}

	public function index()
	{
		$this->getUploadedFile();
		$this->smarty->assign('type', 'create');
		$this->smarty->display('uploaderMain.tpl');
	}

	public function update($course)
	{
		$this->getUploadedFile();
		//取得course信息
		$tmpRes = $this->db->query("SELECT id course, name, area, cost, introduction, list FROM course WHERE id = ?", array($course));
		$courseInfo = $tmpRes->first_row('array');
		//取得course mark信息
		$tmpRes = $this->db->query("SELECT mark FROM courseToMark WHERE course = ?", array($course));
		$tmpMarkArray = $tmpRes->result_array();
		$mark = array();
		foreach ($tmpMarkArray as $item)
		{
			array_push($mark, $item['mark']);
		}
		$courseInfo['mark'] = $mark;
		$_POST = $courseInfo;
		$this->smarty->assign('type', 'update');
		$this->smarty->display('uploaderMain.tpl');
	}

	public function delete($course)
	{
		$this->smarty->assign('type', 'create');
		$this->getUploadedFile();
		$this->db->trans_start();
		$tmpRes = $this->db->query("DELETE FROM courseToMark WHERE course = ?", array($course));
		if ($tmpRes)
		{
			//删除courseToMark失败, 继续删除course
			$tmpRes = $this->db->query("DELETE FROM course WHERE id = ?", array($course));
			if ($tmpRes)
			{
				$this->db->trans_commit();
				$this->getUploadedFile();
				$this->_return("文件删除成功", "ok1");
			}
			else
			{
				//删除course失败
				$this->db->trans_rollback();
				$this->_return("删除文件失败,请重试!", "error1");
			}
		}
		else
		{
			//删除courseToMark失败
			$this->db->trans_rollback();
			$this->_return("删除文件标签失败,请重试!", "error1");
		}
	}

	public function uploadSubmit()
	{
		$this->getUploadedFile();
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
					$tmpRes = $this->db->query("INSERT INTO `course`(`uploader`, `name`, `path`, `area`, `cost`, `list`, `introduction`, `created`) VALUES (?, ?, ?, ?, ?, ?, ?, null)", array(
						$this->session->userdata('userId'),
						$fine_original_name,
						$file_name,
						$this->input->post('area'),
						$this->input->post('cost'),
						$this->input->post('list'),
						$this->input->post('introduction')
					));
					if ($tmpRes)
					{
						$tmpCourse = $this->db->insert_id();
						//添加文件信息成功,继续添加文件mark信息
						foreach ($this->input->post('mark') as $mark)
						{
							$tmpRes = $this->db->query("INSERT INTO `courseToMark`(`course`, `mark`, `created`) VALUES (?, ?, null)", array(
								$tmpCourse,
								$mark
							));
							if (!$tmpRes)
							{
								$this->db->trans_rollback();
								$this->_return("上传文件标签信息失败,请重试!", "error1");
							}
						}
					}
					else
					{
						//添加文件信息失败
						$this->db->trans_rollback();
						$this->_return("上传文件信息失败,请重试!", "error1");
					}
					$this->db->trans_commit();
					$this->getUploadedFile();
					$_POST = NULL;
					$this->_return("文件上传成功!", "ok1");
				}
			}
			else if ($this->input->post('type') == 'update')
			{
				$this->db->trans_start();
				$tmpRes = $this->db->query("UPDATE `course` SET `area`= ?,`cost`= ?,`list`= ?,`introduction`= ? WHERE id = ?", array(
					$this->input->post('area'),
					$this->input->post('cost'),
					$this->input->post('list'),
					$this->input->post('introduction'),
					$this->input->post('course')
				));
				if ($tmpRes)
				{
					//修改文件信息成功,继续修改mark信息
					//先删除原有mark
					$tmpRes = $this->db->query("DELETE FROM courseToMark WHERE course = ?", array($this->input->post('course')));
					if ($tmpRes)
					{
						//删除原有mark成功,保存新标签
						foreach ($this->input->post('mark') as $mark)
						{
							$tmpRes = $this->db->query("INSERT INTO `courseToMark`(`course`, `mark`, `created`) VALUES (?, ?, null)", array(
								$this->input->post('course'),
								$mark
							));
							if (!$tmpRes)
							{
								$this->db->trans_rollback();
								$this->_return("修改文件标签信息失败,请重试!", "error1");
							}
						}
					}
					else
					{
						//删除原有mark失败
						$this->db->trans_rollback();
						$this->_return("修改文件标签信息失败,请重试!", "error1");
					}
				}
				else
				{
					//修改文件信息失败
					$this->db->trans_rollback();
					$this->_return("修改文件信息失败,请重试!", "error1");
				}
				$this->db->trans_commit();
				$this->getUploadedFile();
				$this->smarty->assign('type', 'create');
				$_POST = NULL;
				$this->_return("修改文件成功!", "ok1");
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
				'field'=>'area',
				'label'=>'板块',
				'rules'=>'required'
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

	public function _return($str, $css)
	{
		$this->smarty->assign('msg', '<span class="'.$css.'">'.$str.'</span>');
		$this->smarty->display('uploaderMain.tpl');
		exit ;
	}

}

/*end*/
