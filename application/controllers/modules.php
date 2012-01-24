<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends CI_Controller {
	
	public $body_class = "modules";
	
	public function index()
	{	
		$this->body_id="modules_index";
		$this->content_tpl="modules/index.html";

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