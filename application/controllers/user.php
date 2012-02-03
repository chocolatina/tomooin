<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . "/config/oauth.php");

// このページの所有者id取得用　twitteroauthを利用
require_once(APPPATH . "/libraries/twitteroauth/twitteroauth.php");

class User extends CI_Controller {
	
	public $body_class = "user";
	
	public function index()
	{
		//echo $this->uri->segments[2];
		//exit;

		//ログイン中かどうか判定
		session_start();
		if(isset($_SESSION['current_user']['tomoo_id'])){
			$this->smarty->assign("is_loggedin",1);
			$this->smarty->assign("screen_name",$_SESSION['current_user']['screen_name']);
		}
		else{
			$this->smarty->assign("is_loggedin",0);
		}
		
		
		//もし/user/直下だったらトップに戻す
		if(!isset($this->uri->segments[2])){
			header("Location: /");
		}
		
		//URIの/user/以下をuser_screen_name変数に入れる
		//echo $this->uri->segments[2];
		//exit;
		//$this->smarty->assign("user_screen_name",$this->uri->segments[2]);
		$this_user_screen_name=$this->uri->segments[2];

		
		//URIで判別したscreen_nameを元にそのユーザーのidなどを取ってくる
		$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
		$connect->format = "xml";
		$api_url = "https://api.twitter.com/1/users/lookup.xml";
		$method = "GET";
		$option = array("screen_name" => $this_user_screen_name);
		$req = $connect->OAuthRequest($api_url,$method,$option);

		$xml_users_lookup = simplexml_load_string($req);
		$this_user_twitter_user_id=$xml_users_lookup->user->id;

		//アサイン
		$this->smarty->assign("this_user_screen_name",$this_user_screen_name);
		$this->smarty->assign("this_user_twitter_user_id",$this_user_twitter_user_id);
		//var_dump($xml_users_lookup);
		//echo $xml_users_lookup->user->id;
		//exit;

		//ユーザーのidを元に書かれた紹介文を取ってくる
		$sql = "SELECT * from table1 WHERE friend_id = ? ORDER BY date DESC";
		$query = $this->db->query($sql, array($this_user_twitter_user_id));
		$rows = $query->result();
		//$this->smarty->assign("rows",$rows);

		//$rowsにscreen_nameを足してあげる

		$i=0;
		foreach ($rows as $key => $value){
			$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
			$connect->format = "xml";
			$api_url = "https://api.twitter.com/1/users/show.xml";
			$method = "GET";
			$option = array("user_id" => $value->twitter_user_id);
			$req = $connect->OAuthRequest($api_url,$method,$option);
			$user_list_req = simplexml_load_string($req);
			$rows[$i]->user_screen_name=(string)$user_list_req->screen_name;
			$i++;
		};
		$this->smarty->assign("rows",$rows);

//var_dump($rows);
//exit;






		//サイドバー用に、セッションのscreen_nameをもとに、プロフィール画像などを取ってくる
		$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
		$connect->format = "xml";
		$api_url = "https://api.twitter.com/1/users/lookup.xml";
		$method = "GET";
		$option = array("screen_name" => $this_user_screen_name);
		$req = $connect->OAuthRequest($api_url,$method,$option);
		$xml_users_lookup = simplexml_load_string($req);

		$current_user_profile_image_url_https_normal=$xml_users_lookup->user->profile_image_url_https;

		//users/lookupだとnormalサイズの画像しか返ってこないので無理やりbiggerにする
		$pattern1 = "/_normal/";
		$replace1 = "_reasonably_small";
		//$replace1 = "_original";
		$this_user_profile_image_url_https_bigger = preg_replace($pattern1, $replace1, $current_user_profile_image_url_https_normal, 1);

		$this->smarty->assign("this_user_profile_image_url",$this_user_profile_image_url_https_bigger);

		//説明文も取る
		$this->smarty->assign("this_user_description",$xml_users_lookup->user->description);
		//所在地も取る
		$this->smarty->assign("this_user_location",$xml_users_lookup->user->location);
		//フルネームも取る
		$this->smarty->assign("this_user_name",$xml_users_lookup->user->name);
		
		//サイドバー用twitterAPI通信ここまで
		
		
		//サイドバー用この人が紹介文を書いた友達の一覧
		$sql = "SELECT * from table1 WHERE twitter_user_id = ? ORDER BY date DESC";
		$query = $this->db->query($sql, array($this_user_twitter_user_id));
		$wrote_rows = $query->result();
		
		//$wrote_rowsにscreen_nameを足してあげる
		$i=0;
		foreach ($wrote_rows as $key => $value){
			$connect = new TwitterOAuth(OAUTH_TWITTER_KEY, OAUTH_TWITTER_SECRET, OAUTH_TWITTER_ACCESS_TOKEN, OAUTH_TWITTER_ACCESS_TOKEN_SECRET);
			$connect->format = "xml";
			$api_url = "https://api.twitter.com/1/users/show.xml";
			$method = "GET";
			$option = array("user_id" => $value->friend_id);
			$req = $connect->OAuthRequest($api_url,$method,$option);
			$user_list_req = simplexml_load_string($req);
			$wrote_rows[$i]->friend_screen_name=(string)$user_list_req->screen_name;
			$wrote_rows[$i]->friend_profile_image_url=(string)$user_list_req->profile_image_url;
			$i++;
		};
		
		//var_dump($wrote_rows);
		
		$this->smarty->assign("wrote_rows",$wrote_rows);
		
		
		
		
		//サイドバー用この人の使っている外部サービス
		//この人のadminテーブルのidを取得
		$sql = "SELECT * from admin WHERE twitter_user_id = ?";
		$query = $this->db->query($sql, array($this_user_twitter_user_id));
		$user_rows = $query->result();
		//これに入ってるidをもとに外部サービスの情報をとってくる
		//var_dump($user_rows);

		
		$this->body_id="user_index";
		$this->content_tpl="user/index.html";
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