<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$body_id="index";
		$body_class="index";
		$content_tpl="index.html";
		
		$this->smarty->assign("body_id",$body_id);
		$this->smarty->assign("body_class",$body_class);
		$this->smarty->assign( 'content_tpl',$content_tpl );
		$this->smarty->view("include/base.html");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */