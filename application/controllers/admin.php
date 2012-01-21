<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . "/config/oauth.php");

// friends用　twitteroauthを利用
require_once(APPPATH . "/libraries/twitteroauth/twitteroauth.php");

class Admin extends CI_Controller {
	
	public $body_class = "admin";
	
	public function index()
	{
		session_start();
		if(empty($_SESSION['current_user']['id'])){
			header("Location: /login/twitter");
		}
		else{
			//echo ($_SESSION['current_user']['id'].$_SESSION['current_user']['screen_name']."←セッションで渡ってきたid。これでログインしてるかどうか判別する。ログインしてなかったらトップにリダイレクトする処理をする（未作成）");
			//echo ("<a href='/logout'>ログアウト</a>");
			
			$this->body_id="admin_index";
			$this->content_tpl="admin/index.html";
			
			//上書きしたいとき
			//$this->body_class = "admin2";

			//friends一覧取得ここから

			// OAuthオブジェクトの生成
			$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
			$connect->format = "xml";
			 
			// フォローしてるユーザーのID一覧を取得

			/*$api_url = "https://api.twitter.com/1/users/show.xml";
			$method = "GET";
			$option = array("user_id" => "440891995");
			$req = $connect->OAuthRequest($api_url,$method,$option);

			$xml_friends_id = simplexml_load_string($req);
			echo $req;

			exit;*/

			$api_url = "http://api.twitter.com/1/friends/ids.xml";
			$method = "GET";
			$option = array("screen_name" => $_SESSION['current_user']['screen_name']);
			$req = $connect->OAuthRequest($api_url,$method,$option);

			$xml_friends_ids = simplexml_load_string($req);

			$i=0;
			foreach ($xml_friends_ids->ids->id as $xml_friends_id){
				//echo $xml_friends_id."<br />\n";
				$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
				$connect->format = "xml";
				$api_url = "https://api.twitter.com/1/users/show.xml";
				$method = "GET";
				$option = array("user_id" => "$xml_friends_id");
				$req = $connect->OAuthRequest($api_url,$method,$option);

				$xml_friends_names = simplexml_load_string($req);
				 
				echo $xml_friends_names->screen_name."<br />\n";
				$this->smarty->assign("friends_screen_name",$xml_friends_names->screen_name);

				$i++;
				if($i>4){break;}
				
			};

			$this->smarty->assign("friends_id",$xml_friends_ids->ids->id);
			//friends一覧取得ここまで

			$this->_render();
		}
	}
	public function received()
	{
		session_start();
		if(empty($_SESSION['current_user']['id'])){
			header("Location: /login/twitter");
		}
		else{
			
			$this->body_id="admin_received";
			$this->content_tpl="admin/received.html";
			
			$this->_render();
		}
	}
	public function write()
	{
		session_start();
		if(empty($_SESSION['current_user']['id'])){
			header("Location: /login/twitter");
		}
		else{
			
			$this->body_id="admin_write";
			$this->content_tpl="admin/write.html";
			
			$this->_render();
		}
	}
	public function write_form()
	{
		session_start();
		if(empty($_SESSION['current_user']['id'])){
			header("Location: /login/twitter");
		}
		else{
			
			$this->body_id="admin_write_form";
			$this->content_tpl="admin/write/form.html";
			
			$this->_render();
		}
	}
	public function write_create()
	{
		
		$params = $_POST["params"];
		$sql = "INSERT INTO table1 (ip,date,user_id,friend_id,text,relation) VALUES (?,?,?,?,?,?)";
		$this->db->query($sql,array($params["ip"],$params["date"],$params["user_id"],$params["friend_id"],$params["text"],$params["relation"]));
		
		header("Location: /admin/wrote");
	}
	 
	public function wrote()
	{
		session_start();
		if(empty($_SESSION['current_user']['id'])){
			header("Location: /login/twitter");
		}
		else{
			
			$this->body_id="admin_wrote";
			$this->content_tpl="admin/wrote.html";
		
			$sql = "SELECT * from table1 WHERE user_id = ? ORDER BY date DESC";
			$query = $this->db->query($sql, array(14212710));
			$rows = $query->result();
			$this->smarty->assign("rows",$rows);
			
			$this->_render();
		}
	}
	public function setting()
	{
		session_start();
		if(empty($_SESSION['current_user']['id'])){
			header("Location: /login/twitter");
		}
		else{
			
			$this->body_id="admin_setting";
			$this->content_tpl="admin/setting.html";
			
			$this->_render();
		}
	}
	
	private function _render()
	{
		$this->smarty->assign("id",$_SESSION['current_user']['id']);
		$this->smarty->assign("screen_name",$_SESSION['current_user']['screen_name']);
		$this->smarty->assign("body_id",$this->body_id);
		$this->smarty->assign("body_class",$this->body_class);
		$this->smarty->assign( 'content_tpl', $this->content_tpl );
		$this->smarty->view("include/base.html");
	
	}
}