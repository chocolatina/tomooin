<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public $body_class = "index";

	public function index()
	{
		
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