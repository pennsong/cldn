<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class AddUser extends CW_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->_init();
	}

	private function _init()
	{
		//检查权限
		if ($this->session->userdata('type') != 'admin')
		{
			show_error('无权限做此操作!');
		}
		$this->lang->load('form_validation', 'chinese');
		$this->load->library('grocery_CRUD');
	}

	public function index()
	{
		$this->smarty->assign('title', '用户管理');
		$this->smarty->assign('css_files', array());
		$this->smarty->assign('js_files', array());
		$this->smarty->assign('output', '');
		$this->smarty->display('addUser.tpl');
	}

	public function user()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->columns('username', 'password', 'point');
		$crud->fields('username', 'password', 'point');
		$crud->required_fields('username', 'password', 'point');
		$crud->set_rules('username', '用户名', 'required|alpha_numeric|min_length[6]|max_length[20]');
		$crud->set_rules('password', '密码', 'required|alpha_numeric|min_length[6]|max_length[20]');
		$crud->display_as('username', '用户名')->display_as('password', '密码')->display_as('point', '积分');
		$output = $crud->render();
		foreach ($output as $key=>$value)
		{
			$this->smarty->assign($key, $value);
		}
		$this->smarty->assign('title', '普通用户');
		$this->smarty->display('addUser.tpl');
	}

	public function uploader()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->columns('username', 'password');
		$crud->fields('username', 'password');
		$crud->required_fields('username', 'password');
		$crud->set_rules('username', '用户名', 'required|alpha_numeric|min_length[6]|max_length[20]');
		$crud->set_rules('password', '密码', 'required|alpha_numeric|min_length[6]|max_length[20]');
		$crud->display_as('username', '用户名')->display_as('password', '密码');
		$output = $crud->render();
		foreach ($output as $key=>$value)
		{
			$this->smarty->assign($key, $value);
		}
		$this->smarty->assign('title', '管理员');
		$this->smarty->display('addUser.tpl');
	}

	public function admin()
	{
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->columns('username', 'password');
		$crud->fields('username', 'password');
		$crud->required_fields('username', 'password');
		$crud->set_rules('username', '用户名', 'required|alpha_numeric|min_length[6]|max_length[20]');
		$crud->set_rules('password', '密码', 'required|alpha_numeric|min_length[6]|max_length[20]');
		$crud->display_as('username', '用户名')->display_as('password', '密码');
		$output = $crud->render();
		foreach ($output as $key=>$value)
		{
			$this->smarty->assign($key, $value);
		}
		$this->smarty->assign('title', '超级管理员');
		$this->smarty->display('addUser.tpl');
	}

}

/*end*/