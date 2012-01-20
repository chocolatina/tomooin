<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public $body_class = "admin";
	
	public function index()
	{
		session_start();
		echo ($_SESSION['current_user']['id'].$_SESSION['current_user']['screen_name']."←セッションで渡ってきたid。これでログインしてるかどうか判別する。ログインしてなかったらトップにリダイレクトする処理をする（未作成）");
		echo ("<a href='/logout'>ログアウト</a>");
		
		$this->body_id="admin_index";
		$this->content_tpl="admin/index.html";
		
		//上書き
		$this->body_class = "admin2";
		
	    $this->_render();
	
		
	}
	public function received()
	{
		
		$body_id="admin_received";
		$body_class="admin";
		$content_tpl="admin/received.html";
		
		$this->smarty->assign("body_id",$body_id);
		$this->smarty->assign("body_class",$body_class);
		$this->smarty->assign( 'content_tpl', $content_tpl );
		$this->smarty->view("include/base.html");
	}
	public function write()
	{
		
		$body_id="admin_write";
		$body_class="admin";
		$content_tpl="admin/write.html";
		
		$this->smarty->assign("body_id",$body_id);
		$this->smarty->assign("body_class",$body_class);
		$this->smarty->assign( 'content_tpl', $content_tpl );
		$this->smarty->view("include/base.html");
	}
	public function write_form()
	{
		
		$body_id="admin_write_form";
		$body_class="admin";
		$content_tpl="admin/write/form.html";
		
		$this->smarty->assign("body_id",$body_id);
		$this->smarty->assign("body_class",$body_class);
		$this->smarty->assign( 'content_tpl', $content_tpl );
		$this->smarty->view("include/base.html");
	}
	public function write_create()
	{
		
		$params = $_POST["params"];
		$sql = "INSERT INTO table1 (ip,date,user_id,friend_id,text,relation) VALUES (?,?,?,?,?,?)";
		$this->db->query($sql,array($params["ip"],$params["date"],$params["user_id"],$params["friend_id"],$params["text"],$params["relation"]));
		
		//var_dump($params);
		//exit;
		/*var_dump($_POST);
		exit;*/
		
		header("Location: /admin/wrote");
	}
	 
	public function wrote()
	{
		
		$body_id="admin_wrote";
		$body_class="admin";
		$content_tpl="admin/wrote.html";
		
		$sql = "SELECT * from table1 WHERE user_id = ? ORDER BY date DESC";
		$query = $this->db->query($sql, array(14212710));
		$rows = $query->result();
		$this->smarty->assign("rows",$rows);
		
		$this->smarty->assign("body_id",$body_id);
		$this->smarty->assign("body_class",$body_class);
		$this->smarty->assign( 'content_tpl', $content_tpl );
		$this->smarty->view("include/base.html");
	}
	public function setting()
	{
		
		$body_id="admin_setting";
		$body_class="admin";
		$content_tpl="admin/setting.html";
		
		$this->smarty->assign("body_id",$body_id);
		$this->smarty->assign("body_class",$body_class);
		$this->smarty->assign( 'content_tpl', $content_tpl );
		$this->smarty->view("include/base.html");
	}
	
	private function _render()
	{
		$this->smarty->assign("body_id",$this->body_id);
		$this->smarty->assign("body_class",$this->body_class);
		$this->smarty->assign( 'content_tpl', $this->content_tpl );
		$this->smarty->view("include/base.html");
	
	}
}

/*同じこと何回もやっててバカなのであとで@banyanに聞く*/

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */