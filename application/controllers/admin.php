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
			$xml_friends_shows_arrays = array();
			foreach ($xml_friends_ids->ids->id as $xml_friends_id){
				//echo $xml_friends_id."<br />\n";
				$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
				$connect->format = "xml";
				$api_url = "https://api.twitter.com/1/users/show.xml";
				$method = "GET";
				$option = array("user_id" => "$xml_friends_id");
				$req = $connect->OAuthRequest($api_url,$method,$option);
				$xml_friends_shows = simplexml_load_string($req);
				//var_dump($xml_friends_shows);
				//$xml_friends_names["$xml_friends_id"]="$xml_friends_shows->screen_name";
				$xml_friends_shows_arrays["$i"]["friend_id"]="$xml_friends_id";
				$xml_friends_shows_arrays["$i"]["screen_name"]="$xml_friends_shows->screen_name";
				$xml_friends_shows_arrays["$i"]["profile_image_url"]="$xml_friends_shows->profile_image_url";
				//var_dump($xml_friends_shows_arrays);
				$i++;
				if($i>5){break;}
				
			};
			$this->smarty->assign("friends_list",$xml_friends_shows_arrays);
			//$this->smarty->assign("friends_id",$xml_friends_ids->ids->id);
			//$this->smarty->assign("friends_name",$xml_friends_names);
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
			if(htmlspecialchars(@$_GET['screen_name'])) {
				//自分のidを取得
				$query = $this->db->query("SELECT * from admin WHERE id = ". $_SESSION['current_user']['id']);
				$row = $query->row();
				//echo $row->id;
				//var_dump($query);
				//exit;
				$this->smarty->assign("user_id",$row->user_id);
				//友人の名前を取得
				$friend_screen_name = $_GET['screen_name'];
				$this->smarty->assign("friend_screen_name",$_GET['screen_name']);

				//友人のプロフィールなどを取得
				$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
				$connect->format = "xml";
				$api_url = "https://api.twitter.com/1/users/lookup.xml";
				$method = "GET";
				$option = array("screen_name" => "paperbooya");
				$req = $connect->OAuthRequest($api_url,$method,$option);

				$xml_users_lookups = simplexml_load_string($req);
				//echo $xml_users_lookups->user->profile_image_url;
				//echo $xml_users_lookups->user->name;
				//echo $xml_users_lookups->user->description;
				//exit;
				//var_dump($xml_users_lookups);


				$this->smarty->assign("friend_id",$xml_users_lookups->user->id);
				$this->smarty->assign("friend_profile_image_url_https",$xml_users_lookups->user->profile_image_url_https);
				$this->smarty->assign("friend_name",$xml_users_lookups->user->name);
				$this->smarty->assign("friend_description",$xml_users_lookups->user->description);
				
				$this->body_id="admin_write_form";
				$this->content_tpl="admin/write/form.html";
				
				$this->_render();
			}
			else{
				header("Location: /admin/write");
			}
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