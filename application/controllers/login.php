<?php
require_once(APPPATH . "/config/oauth.php");

class Login extends CI_Controller {
    public function twitter() {
	$params = array('key'=>OAUTH_TWITTER_KEY, 'secret'=>OAUTH_TWITTER_SECRET);
		
        $this->load->library('twitter_oauth', $params);
        //$this->load->helper('url');autoloadしてるので書かなくていい
        //$this->load->library('session');autoloadしてるので書かなくていい
 
        $response = $this->twitter_oauth->get_request_token(site_url("login/callback"));

	//$response = $this->twitter_oauth->get_request_token(site_url("/admin"));わからない
        $_SESSION['token_secret'] = $response['token_secret'];
        $this->session->set_userdata('token_secret', $response['token_secret']);
        redirect($response['redirect']);
    }
 
    public function callback() {
        $params = array('key'=>OAUTH_TWITTER_KEY, 'secret'=>OAUTH_TWITTER_SECRET);
        $this->load->library('twitter_oauth', $params);
        //$this->load->library('session');
 
        $response = $this->twitter_oauth->get_access_token(false, $this->session->userdata('token_secret'));
        //$this->_store_in_db($response);「oauth_token」などをDBに登録しておけば、次回からは自動的にログインをして貰うこともできます。
        //var_dump($response['user_id']);
	//var_dump($response);
	//exit;
		
		$query = $this->db->query("SELECT * from admin WHERE twitter_user_id = ". $response['user_id']);
		//$rows = $query->result_array();
		
		
		if ($query->num_rows() > 1)//万が一レコードが2件以上あるとき
		{
		   echo "エラーです 件数がおかしいです";
		   exit;
		   //$row = $query->row();
		}
		else{
			$row = $query->row();
			//var_dump($row);
			//exit;
		}
		
		
		//var_dump($rows);
		//exit;
		//初めてのログインの時。つまり、adminのtable内にtwitter_user_idがなかったとき
		if( empty($row) ){		
			//もし結果がnullだったらはじめてなのでクリエイトしてあげる処理を書く
			$sql = "INSERT INTO admin (provider,screen_name,twitter_user_id) VALUES (?,?,?)";
			$ret = $this->db->query($sql,array("twitter",$response["screen_name"],$response["user_id"]));
			
			$query = $this->db->query("SELECT * from admin WHERE twitter_user_id = ". $response['user_id']);
			$row = $query->row();
			//var_dump($row);
			//exit;
			$_SESSION['current_user']['tomoo_id'] = $row->tomoo_id;
			$_SESSION['current_user']['twitter_user_id'] = $row->twitter_user_id;
			$_SESSION['current_user']['screen_name'] = $row->screen_name;
			//$_SESSION['tomoo_id'] = $row->tomoo_id;
			//var_dump($row);
			//exit;
			
			//初めてのログインなので、settingページ用にuser_settingテーブルに一行作ってあげる
			$sql = "INSERT INTO user_setting (
					tomoo_id,
					external_url_facebook,
					external_url_mixi,
					external_url_tumblr,
					external_url_hatenab,
					external_url_hatenad,
					external_url_the,
					external_url_instagram,
					external_url_foursquare,
					external_url_ameblo,
					external_url_jugem,
					external_url_flickr,
					external_url_youtube,
					external_url_nicovideo)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$this->db->query($sql,array(
			$_SESSION['current_user']['tomoo_id'],
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				'',
				''
			));
			
			
			
			header("Location: /admin");
		}
		//前にもログインしたことがあるとき
		else{

			session_start();
			//$res=$rows[0];
			$_SESSION['current_user']['tomoo_id'] = $row->tomoo_id;
			$_SESSION['current_user']['twitter_user_id'] = $row->twitter_user_id;
			$_SESSION['current_user']['screen_name'] = $row->screen_name;
			header("Location: /admin");
		}
    }
}