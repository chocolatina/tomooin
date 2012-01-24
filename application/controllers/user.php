<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	
	public $body_class = "user";
	
	public function index()
	{
		//echo $this->uri->segments[2];
		//exit;
		
		//URI��/user/�ʲ���user_id�ѿ��������
		$this->smarty->assign("user_id",$this->uri->segments[2]);
		
		//$sql = "SELECT * from table1 WHERE user_screen_name = ? ORDER BY date DESC";
		//$query = $this->db->query($sql, array(14212710));
		//$rows = $query->result();
		//$this->smarty->assign("rows",$rows);
		
		$this->body_id="user_index";
		$this->content_tpl="user/index.html";
		$this->_render();
		
	}
	
	private function _render()
	{
		$this->smarty->assign("body_id",$this->body_id);
		$this->smarty->assign("body_class",$this->body_class);
		$this->smarty->assign( 'content_tpl', $this->content_tpl );
		$this->smarty->view("include/base.html");
	
	}
}