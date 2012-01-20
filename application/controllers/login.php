<?php
class Login extends CI_Controller {
    public function twitter() {
        $params = array('key'=>'5EgWHBYmShjw6MbkwwgRvg', 'secret'=>'XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI');
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
        $params = array('key'=>'5EgWHBYmShjw6MbkwwgRvg', 'secret'=>'XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI');
        $this->load->library('twitter_oauth', $params);
        //$this->load->library('session');
 
        $response = $this->twitter_oauth->get_access_token(false, $this->session->userdata('token_secret'));
        //$this->_store_in_db($response);「oauth_token」などをDBに登録しておけば、次回からは自動的にログインをして貰うこともできます。
        //var_dump($response);
		
		//SELECT * FROM "admin" user_id とプロバイダ
		//$sql = "SELECT * from admin WHERE user_id = '". $response['user_id']."'";
		
		$query = $this->db->query("SELECT * from admin WHERE user_id = ". $response['user_id']);
		$rows = $query->result_array();
		//var_dump($rows);
		//echo ($res["id"]);
		//exit;
		
		//初めてのログインの時。つまり、adminのtable内にuser_idがなかったとき
		if( empty($rows) ){		
			//もし結果がnullだったらはじめてなのでクリエイトしてあげる処理を書く
			echo "あああ";
			exit;
		}
		//前にもログインしたことがあるとき
		else{
			session_start();
			$res=$rows[0];
			$_SESSION['id'] = $res["id"];
			header("Location: /admin");
		}
		
		/*$query = $this->db->query("SELECT * from admin WHERE user_id = ". $response['user_id']);
		//var_dump($query);
		$data['query'] = $query->result_array();
		var_dump($data['query'][0]);
		exit;*/

		/*$result = $this->db->query($sql);
		
		//var_dump($result);
		echo $result[num_rows];
		exit;*/
		
		//sqlで取得した結果のidだけをセッションに入れてあげる
		//もし結果がnullだったらはじめてなのでクリエイトしてあげる
		
		//if(){
		
		// パラメータからoauth_verifierを取得
		//$verifier = $_GET['oauth_verifier'];
		
		
		//session_start();
		
		
		// token keyとtoken secret, user_id, screen_nameをセッションに保存
		//$_SESSION['oauth_token'] = $response['oauth_token'];
		//$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
		
		
		//$_SESSION['user_id'] = $response['user_id'];//これidにする
		
		//ユーザーをcreateするようなsql
		
		//
		//}
		//$_SESSION['screen_name'] = $response['screen_name'];
		
		/*echo $_SESSION['oauth_token']."<br>";
		echo $_SESSION['oauth_token_secret']."<br>";
		echo $_SESSION['user_id']."<br>";
		echo $_SESSION['screen_name'];*/
		
		
    }
}


/*
11:37 (tarop) callback で user_id とか受け取ったら
11:37 (tarop) そこでセッションに user_id 入れて
11:38 (tarop) それで管理ページにリダイレクトしたらいいと思うよ
11:38 (tarop) 管理ページでは、例えば user_id がセッションに入っていたらログイン状態とする
11:38 (tarop) みたいなことにしておけば
11:38 (tarop) user_id 持ってない人はログインしてないので
11:38 (tarop) ログインページにリダイレクトする
11:39 (tarop) そんな感じかな
11:39 (tarop) ちなみにログアウトするときはセッションを破棄する
11:39 (tarop) これで概ねいいんじゃないかと思う
*/