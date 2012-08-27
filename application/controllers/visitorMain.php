<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class VisitorMain extends CW_Controller
{
	private $areaArray;
	private $markArray;
	public function noLogin_index($sortType = 'area')
	{
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
			$courseAreaSortList = $this->areaArray;
			foreach ($courseAreaSortList as &$item)
			{
				//取得当前所属板块课程信息
				$tmpRes = $this->db->query("SELECT a.id, a.cost, a.introduction, a.list, a.name, a.path, b.name markName FROM course a JOIN mark b ON a.mark = b.id WHERE area = ? ORDER BY b.name, a.name", array($item['areaId']));
				$item['courseArray'] = $tmpRes->result_array();
			}
			$this->smarty->assign('courseAreaSortList', $courseAreaSortList);
		}
		else if ($sortType == 'mark')
		{
			$courseMarkSortList = $this->markArray;
			foreach ($courseMarkSortList as &$item)
			{
				//取得当前所属标签课程信息
				$tmpRes = $this->db->query("SELECT a.*, d.name bigAreaName, b.name areaName FROM course a JOIN area b ON a.area = b.id JOIN mark c ON a.mark = c.id JOIN bigArea d ON b.bigArea = d.id WHERE a.mark = ? ORDER BY d.name, b.name, a.name", array($item['id']));
				$item['courseArray'] = $tmpRes->result_array();
			}
			$this->smarty->assign('courseMarkSortList', $courseMarkSortList);
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
