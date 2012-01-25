<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public $body_class = "index";

	public function index()
	{
		//ログイン中かどうか判定
		session_start();
		if(isset($_SESSION['current_user']['id'])){
			$this->smarty->assign("is_loggedin",1);
			$this->smarty->assign("screen_name",$_SESSION['current_user']['screen_name']);
		}
		else{
			$this->smarty->assign("is_loggedin",0);
		}
		$this->body_id="index";
		$this->content_tpl="index.html";
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */