<?php
class Login extends CI_Controller {
    public function twitter() {
        $params = array('key'=>'5EgWHBYmShjw6MbkwwgRvg', 'secret'=>'XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI');
        $this->load->library('twitter_oauth', $params);
        //$this->load->helper('url');autoload���Ă�̂ŏ����Ȃ��Ă���
        //$this->load->library('session');autoload���Ă�̂ŏ����Ȃ��Ă���
 
        $response = $this->twitter_oauth->get_request_token(site_url("tweet/callback"));
	//$response = $this->twitter_oauth->get_request_token(site_url("/admin"));�킩��Ȃ�
        $_SESSION['token_secret'] = $response['token_secret'];
        $this->session->set_userdata('token_secret', $response['token_secret']);
        redirect($response['redirect']);
    }
 
    public function callback() {
        $params = array('key'=>'5EgWHBYmShjw6MbkwwgRvg', 'secret'=>'XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI');
        $this->load->library('twitter_oauth', $params);
        //$this->load->library('session');
 
        $response = $this->twitter_oauth->get_access_token(false, $this->session->userdata('token_secret'));
        //$this->_store_in_db($response);�uoauth_token�v�Ȃǂ�DB�ɓo�^���Ă����΁A���񂩂�͎����I�Ƀ��O�C�������ĖႤ���Ƃ��ł��܂��B
        var_dump($response);
    }
}