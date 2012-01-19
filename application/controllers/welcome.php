<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	 
	 
	
	public function input()
	{
		$this->smarty->view("welcome/input.html");
	}
		
	public function create()
	{
		
		$params = $_POST["params"];
		$sql = "INSERT INTO table1 (text,status) VALUES (?,?)";
		$this->db->query($sql,array($params["text"],$params["status"]));
		
		//var_dump($params);
		//exit;
		/*var_dump($_POST);
		exit;*/
		
		header("Location: /welcome/");
	}
	 
	 
	 
	public function index()
	{
		/*
		//特定の条件だけ絞り込みたいとき。
		$sql = "SELECT * FROM table1 WHERE id = ? AND status = ?"; 
		$query = $this->db->query($sql, array(1,2));
		*/
		
		/*
		$query = $this->db->get('table1'5);//$query = $this->db->query('SELECT * from table1');　と一緒
		*/
		
		/*
		$query = $this->db->get('table1',3,5);//$query = $this->db->query('SELECT * from table1');　と一緒で　件数指定
		*/
		
		/*
		$this->db->select_max('id'); //最大のものをしぼりこむ
		$query = $this->db->get('table1');　 //だす
		*/
		
		
		$query = $this->db->query('SELECT * from table1');
		
		$rows = $query->result();
		$this->smarty->assign("rows",$rows);
		
		
		
		/*
		//だんぷする
		//var_dump($query->result());
		//exit;
		*/
		
		//$this->load->view('welcome_message');
		
		$hoge="aaaa";
		$this->smarty->assign("hoge",$hoge);
		$this->smarty->view("welcome/index.html");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */