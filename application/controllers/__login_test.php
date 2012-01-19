<?php

class Login extends CI_Controller {
	/*function __construct()
	{
		parent::__construct();
		header('Content-Type: text-html; charset=UTF-8');
		//セッションクラスのロード
		$this->load->library('session');
	}*/
	
	public function index()
	{
var_dump($this->session->userdata);
exit;
		//セッションデータの読み取り
		if(!$this->session->userdata('count'))
		{
			//セッションデータの保存
			$this->session->set_userdata('count',1);
		}
		else
		{
			$count = $this->session->userdata('count');
			$count ++;
			$this->session->set_userdata('count',$count);
		}
		echo 'visit:'.$this->session->userdata('count')."<br />";
		
		echo "<a href='/login'>reload</a><br />";
		echo "<a href='/login/destroy'>clear</a>";
	}
	
	public function destroy()
	{
		$this->session->sess_destroy();
		echo "session cleared!!<br />";
		echo "<a href='/login'>back</a>";
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */