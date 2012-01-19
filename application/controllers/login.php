<?php
class Login extends CI_Controller {
    public function twitter() {
        $params = array('key'=>'5EgWHBYmShjw6MbkwwgRvg', 'secret'=>'XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI');
        $this->load->library('twitter_oauth', $params);
        //$this->load->helper('url');autoloadしてるので書かなくていい
        //$this->load->library('session');autoloadしてるので書かなくていい
 
        $response = $this->twitter_oauth->get_request_token(site_url("tweet/callback"));
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
        var_dump($response);
    }
}