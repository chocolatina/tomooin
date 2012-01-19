<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

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
	public function twitter()
	{
		
		
		/**
		 * @file
		 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
		 */
		
		/* Load required lib files. */
		session_start();
		require_once(APPPATH.'libraries/loginwithtwitter/twitteroauth/twitteroauth.php');
		require_once(APPPATH.'libraries/loginwithtwitter/config.php');
		
		
		
		
		
		
		
		/* Build TwitterOAuth object with client credentials. */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		 
		/* Get temporary credentials. */
		$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
		
		
		/* Save temporary credentials to session. */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		 
		/* If last connection failed don't display authorization link. */
		switch ($connection->http_code) {
		  case 200:
			/* Build authorize URL and redirect user to Twitter. */
			$url = $connection->getAuthorizeURL($token);
			header('Location: ' . $url); 
			break;
		  default:
			/* Show notification if something went wrong. */
			echo 'Could not connect to Twitter. Refresh the page or try again later.';
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		//require_once('twitteroauth/twitteroauth.php');
		//require_once('config.php');
		
		/* If access tokens are not available redirect to connect page. 
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			header('Location: ./clearsessions.php');
		}*/
		/* Get user access tokens out of the session. 
		$access_token = $_SESSION['access_token'];*/
		
		/* Create a TwitterOauth object with consumer/user tokens. 
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);*/
		
		/* If method is set change API call made. Test is called by default. 
		$content = $connection->get('account/verify_credentials');*/
		
		/* Some example calls */
		//$connection->get('users/show', array('screen_name' => 'abraham'));
		//$connection->post('statuses/update', array('status' => date(DATE_RFC822)));
		//$connection->post('statuses/destroy', array('id' => 5437877770));
		//$connection->post('friendships/create', array('id' => 9436992)));
		//$connection->post('friendships/destroy', array('id' => 9436992)));
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*$params = $_POST["params"];
		$sql = "INSERT INTO table1 (text,status) VALUES (?,?)";
		$this->db->query($sql,array($params["text"],$params["status"]));*/
		
		//var_dump($params);
		//exit;
		/*var_dump($_POST);
		exit;*/
		
		//header("Location: /admin/");
		
		/*$body_id="login_twitter";
		$body_class="login";
		$content_tpl="login/twitter.html";
		
		$this->smarty->assign("body_id",$body_id);
		$this->smarty->assign("body_class",$body_class);
		$this->smarty->assign( 'content_tpl',$content_tpl );
		$this->smarty->view("include/base.html");*/
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */