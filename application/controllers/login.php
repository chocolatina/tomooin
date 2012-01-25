<?php
require_once(APPPATH . "/config/oauth.php");

class Login extends CI_Controller {
    public function twitter() {
	$params = array('key'=>OAUTH_TWITTER_KEY, 'secret'=>OAUTH_TWITTER_SECRET);
		
        $this->load->library('twitter_oauth', $params);
        //$this->load->helper('url');autoload���Ă�̂ŏ����Ȃ��Ă���
        //$this->load->library('session');autoload���Ă�̂ŏ����Ȃ��Ă���
 
        $response = $this->twitter_oauth->get_request_token(site_url("login/callback"));

	//$response = $this->twitter_oauth->get_request_token(site_url("/admin"));�킩��Ȃ�
        $_SESSION['token_secret'] = $response['token_secret'];
        $this->session->set_userdata('token_secret', $response['token_secret']);
        redirect($response['redirect']);
    }
 
    public function callback() {
        $params = array('key'=>OAUTH_TWITTER_KEY, 'secret'=>OAUTH_TWITTER_SECRET);
        $this->load->library('twitter_oauth', $params);
        //$this->load->library('session');
 
        $response = $this->twitter_oauth->get_access_token(false, $this->session->userdata('token_secret'));
        //$this->_store_in_db($response);�uoauth_token�v�Ȃǂ�DB�ɓo�^���Ă����΁A���񂩂�͎����I�Ƀ��O�C�������ĖႤ���Ƃ��ł��܂��B
        //var_dump($response['user_id']);
		//exit;
		
		$query = $this->db->query("SELECT * from admin WHERE user_id = ". $response['user_id']);
		//$rows = $query->result_array();
		
		
		if ($query->num_rows() > 1)//�����ꃌ�R�[�h��2���ȏ゠��Ƃ�
		{
		   echo "�G���[�ł� ���������������ł�";
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
		//���߂Ẵ��O�C���̎��B�܂�Aadmin��table����user_id���Ȃ������Ƃ�
		if( empty($row) ){		
			//�������ʂ�null��������͂��߂ĂȂ̂ŃN���G�C�g���Ă����鏈��������
			$sql = "INSERT INTO admin (provider,screen_name,user_id) VALUES (?,?,?)";
			$ret = $this->db->query($sql,array("twitter",$response["screen_name"],$response["user_id"]));
			
			$query = $this->db->query("SELECT * from admin WHERE user_id = ". $response['user_id']);
			$row = $query->row();
			$_SESSION['current_user']['id'] = $row->id;
			$_SESSION['current_user']['screen_name'] = $row->screen_name;
			//$_SESSION['id'] = $row->id;
			//var_dump($row);
			//exit;
			header("Location: /admin");
		}
		//�O�ɂ����O�C���������Ƃ�����Ƃ�
		else{
			session_start();
			//$res=$rows[0];
			$_SESSION['current_user']['id'] = $row->id;
			$_SESSION['current_user']['screen_name'] = $row->screen_name;
			//$_SESSION['current_user']['profile_image_url'] = $row->profile_image_url;
			//$_SESSION['current_user']['profile_image_url_https'] = $row->profile_image_url_https;
			header("Location: /admin");
		}
    }
}