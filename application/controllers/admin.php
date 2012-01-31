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
			 
			// 現在ログイン中のユーザーのタイムラインを取得
			
			$api_url = "http://api.twitter.com/1/statuses/user_timeline.xml";
			$method = "GET";
			$option = array("screen_name" => $_SESSION['current_user']['screen_name'],"count" => "20");
			$req = $connect->OAuthRequest($api_url,$method,$option);
			//echo $req;
			$xml_statuses_user_timeline = simplexml_load_string($req);//この配列にはタイムライン20件が収納されている
			
			// 最近リプライを飛ばしたユーザーを探してidを取得
			$friends_list = array();
			$i=0;
			$n=0;
			while ($i <= 20) {
				//配列内でリプライidが存在するときだけ
				if(!empty($xml_statuses_user_timeline->status[$i]->in_reply_to_user_id)){
					//なおかつ自分じゃない時だけ配列に入れる
					if($xml_statuses_user_timeline->status[$i]->in_reply_to_screen_name!=$_SESSION['current_user']['screen_name']){
						$friends_list[$n]["friend_user_id"]=$xml_statuses_user_timeline->status[$i]->in_reply_to_user_id;
						$n++;
					}
				}
				else{
					//echo "empty";
				}
				$i++;
			}
			
			//重複を削除
			$tmp1 = array();//検証用配列
			$tmp2 = array();//検証用配列
			foreach($friends_list as $key => $val){
				$tmp1[$key]=$val["friend_user_id"];
				$tmp1=array_unique($tmp1);
			}
			foreach($tmp1 as $key => $val){
				$tmp2[$key]["friend_user_id"]=$val;
			}
			$friends_list = $tmp2;
			
			
			//最近リプライを飛ばした友人のスクリーンネームとプロフィール画像を取得
			//$i=0;
			foreach ($friends_list as $key => $value){
				
				$friend_user_id = $value["friend_user_id"];
				
				$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
				$connect->format = "xml";
				$api_url = "https://api.twitter.com/1/users/show.xml";
				$method = "GET";
				$option = array("user_id" => "$friend_user_id");
				$req = $connect->OAuthRequest($api_url,$method,$option);
				$friend_list_req = simplexml_load_string($req);
				$friends_list[$key]["friend_screen_name"]="$friend_list_req->screen_name";
				$friends_list[$key]["friend_profile_image_url"]="$friend_list_req->profile_image_url";
			};
			
			$this->smarty->assign("friends_list",$friends_list);
			//最近リプライを飛ばした友人の情報取得ここまで
			
			//これは使えないにゃー
			//$this->output->cache(10);
			
			//DBにキャッシュ用にテーブルをひとつ作って、友達の情報を入れて、もし情報が存在したら、次からはそれをとってくるにゃー
			//user_id    | friend_id      | friend_screen_name   | friend_profile_image_url | date
			//------------------------------------------------------------------------------------------
			//chocolatina | 123456        |murasakipinko         | https.....               | 20111111
			//------------------------------------------------------------------------------------------
			//chocolatina | 789012        |banyan                | https.....               | 20111112
			//------------------------------------------------------------------------------------------
			
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
			
			//このユーザーに届いた紹介文で未開封なら、開封済み非公開にする
			$sql = "UPDATE table1 SET status='1' WHERE friend_id = ? AND status = ?";
			$query = $this->db->query($sql, array($_SESSION['current_user']['user_id'],0));
			
			
			//現在のユーザーに届いた紹介文を取ってくる
			$sql = "SELECT * from table1 WHERE friend_id = ? ORDER BY date DESC";
			$query = $this->db->query($sql, array($_SESSION['current_user']['user_id']));
			$rows = $query->result();
			
			
			//$rowsに紹介文の筆者のscreen_nameを足してあげる
			
			$i=0;
			foreach ($rows as $key => $value){
				$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
				$connect->format = "xml";
				$api_url = "https://api.twitter.com/1/users/show.xml";
				$method = "GET";
				$option = array("user_id" => $value->user_id);
				$req = $connect->OAuthRequest($api_url,$method,$option);
				$friend_list_req = simplexml_load_string($req);
				$rows[$i]->user_screen_name=(string)$friend_list_req->screen_name;
				$rows[$i]->user_profile_image_url_https=(string)$friend_list_req->profile_image_url_https;
				$i++;
			};
			
			$this->smarty->assign("rows",$rows);
			
			
			
			
			$this->body_id="admin_received";
			$this->content_tpl="admin/received.html";
			
			$this->_render();
		}
	}

	public function received_publish()
	{
		session_start();

		$sql = "UPDATE table1 SET status='2' WHERE id= ? AND friend_id = ?";
		$this->db->query($sql,array($_GET['id'],$_SESSION['current_user']['user_id']));
		
		header("Location: /admin/received");
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
				$option = array("screen_name" => $_GET['screen_name']);
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
			$query = $this->db->query($sql, array($_SESSION['current_user']['user_id']));
			$rows = $query->result();




			//$rowsにscreen_nameを足してあげる



			$i=0;
			foreach ($rows as $key => $value){
				
				//var_dump($value->friend_id);
				//exit;
				//echo $friends_list["friend_user_id"];
				//$friend_id = $value->friend_id;
				//echo $value->friend_id;
				//exit;
				//echo "<br><br><br>".$i."<br><br><br>friend_user_id is".$friend_user_id."<br><br><br>\n";
				
				$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
				$connect->format = "xml";
				$api_url = "https://api.twitter.com/1/users/show.xml";
				$method = "GET";
				$option = array("user_id" => $value->friend_id);
				$req = $connect->OAuthRequest($api_url,$method,$option);
				$friend_list_req = simplexml_load_string($req);
				//echo $i;
				$rows[$i]->friend_screen_name=(string)$friend_list_req->screen_name;
				//echo (string)$friend_list_req->profile_image_url_https;
				$rows[$i]->friend_profile_image_url_https=(string)$friend_list_req->profile_image_url_https;
				//$rows[$i]["friend_screen_name"]=(string)$friend_list_req->screen_name;
				//echo $friend_list_req->screen_name;
				//exit;
				//$rows[$key]["friend_screen_name"]="$friend_list_req->screen_name";
				//$rows["friend_screen_name"]="hogehoge";
				$i++;
			};
			//var_dump($rows);
			//exit;
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
	public function setting_create()
	{
		session_start();
		
		$params = $_POST["params"];
		//ここは本当はINSERTじゃなくてUPDATEにする。INSERTははじめてpublishされるとき全部NULLで渡す。
		$sql = "INSERT INTO user_setting (tomoo_id,external_url_mixi) VALUES (?,?)";
		$this->db->query($sql,array($_SESSION['current_user']['id'],$params["external_url_mixi"]));
		
		header("Location: /admin/setting");
	}
	
	private function _render()
	{

		//セッションのscreen_nameをもとに、プロフィール画像を取ってくる
		$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
		$connect->format = "xml";
		$api_url = "https://api.twitter.com/1/users/lookup.xml";
		$method = "GET";
		$option = array("screen_name" => $_SESSION['current_user']['screen_name']);
		$req = $connect->OAuthRequest($api_url,$method,$option);
		$xml_users_lookup = simplexml_load_string($req);
		$current_user_profile_image_url_https_normal=$xml_users_lookup->user->profile_image_url_https;

		//users/lookupだとnormalサイズの画像しか返ってこないので無理やりbiggerにする
		$pattern1 = "/_normal/";
		$replace1 = "_bigger";
		$current_user_profile_image_url_https_bigger = preg_replace($pattern1, $replace1, $current_user_profile_image_url_https_normal, 1);

		$this->smarty->assign("profile_image_url",$current_user_profile_image_url_https_bigger);



			
		//サイドバーとadminトップ用。このユーザーに届いた紹介文で未開封のものの数字を取ってくる
		$sql = "SELECT * from table1 WHERE friend_id = ? AND status = ?";
		$query = $this->db->query($sql, array($_SESSION['current_user']['user_id'],0));
		$unread_rows = $query->result();
		//echo count($unread_rows);
		//exit;
		$this->smarty->assign("count_unread_rows",count($unread_rows));
			

		$this->smarty->assign("id",$_SESSION['current_user']['id']);
		$this->smarty->assign("user_id",$_SESSION['current_user']['user_id']);
		$this->smarty->assign("screen_name",$_SESSION['current_user']['screen_name']);
		//$this->smarty->assign("screen_name","12345678901234567890");
		$this->smarty->assign("body_id",$this->body_id);
		$this->smarty->assign("body_class",$this->body_class);
		$this->smarty->assign( 'content_tpl', $this->content_tpl );
		$this->smarty->view("include/base.html");
	
	}
}