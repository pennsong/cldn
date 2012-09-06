<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class VisitorMain extends CW_Controller
{
	private $areaArray;
	private $markArray;
	public function noLogin_index($sortType = 'area', $language = "all")
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
					}
				}
			}
			$this->smarty->assign('courseMarkSortList', $markArray);
		}
		else
		{
			show_error('无权限做此操作!');
		}
		$this->smarty->assign('sortType', $sortType);
		$this->smarty->display('visitorMain.tpl');
	}

}

/*end*/
