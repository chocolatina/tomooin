<?php
class Login extends CI_Controller {
    public function twitter() {
        $params = array('key'=>'5EgWHBYmShjw6MbkwwgRvg', 'secret'=>'XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI');
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
        $params = array('key'=>'5EgWHBYmShjw6MbkwwgRvg', 'secret'=>'XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI');
        $this->load->library('twitter_oauth', $params);
        //$this->load->library('session');
 
        $response = $this->twitter_oauth->get_access_token(false, $this->session->userdata('token_secret'));
        //$this->_store_in_db($response);�uoauth_token�v�Ȃǂ�DB�ɓo�^���Ă����΁A���񂩂�͎����I�Ƀ��O�C�������ĖႤ���Ƃ��ł��܂��B
        //var_dump($response);
		
		//SELECT * FROM "admin" user_id �ƃv���o�C�_
		//$sql = "SELECT * from admin WHERE user_id = '". $response['user_id']."'";
		
		$query = $this->db->query("SELECT * from admin WHERE user_id = ". $response['user_id']);
		$rows = $query->result_array();
		//var_dump($rows);
		//echo ($res["id"]);
		//exit;
		
		//���߂Ẵ��O�C���̎��B�܂�Aadmin��table����user_id���Ȃ������Ƃ�
		if( empty($rows) ){		
			//�������ʂ�null��������͂��߂ĂȂ̂ŃN���G�C�g���Ă����鏈��������
			echo "������";
			exit;
		}
		//�O�ɂ����O�C���������Ƃ�����Ƃ�
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
		
		//sql�Ŏ擾�������ʂ�id�������Z�b�V�����ɓ���Ă�����
		//�������ʂ�null��������͂��߂ĂȂ̂ŃN���G�C�g���Ă�����
		
		//if(){
		
		// �p�����[�^����oauth_verifier���擾
		//$verifier = $_GET['oauth_verifier'];
		
		
		//session_start();
		
		
		// token key��token secret, user_id, screen_name���Z�b�V�����ɕۑ�
		//$_SESSION['oauth_token'] = $response['oauth_token'];
		//$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
		
		
		//$_SESSION['user_id'] = $response['user_id'];//����id�ɂ���
		
		//���[�U�[��create����悤��sql
		
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
11:37 (tarop) callback �� user_id �Ƃ��󂯎������
11:37 (tarop) �����ŃZ�b�V������ user_id �����
11:38 (tarop) ����ŊǗ��y�[�W�Ƀ��_�C���N�g�����炢���Ǝv����
11:38 (tarop) �Ǘ��y�[�W�ł́A�Ⴆ�� user_id ���Z�b�V�����ɓ����Ă����烍�O�C����ԂƂ���
11:38 (tarop) �݂����Ȃ��Ƃɂ��Ă�����
11:38 (tarop) user_id �����ĂȂ��l�̓��O�C�����ĂȂ��̂�
11:38 (tarop) ���O�C���y�[�W�Ƀ��_�C���N�g����
11:39 (tarop) ����Ȋ�������
11:39 (tarop) ���Ȃ݂Ƀ��O�A�E�g����Ƃ��̓Z�b�V������j������
11:39 (tarop) ����ŊT�˂����񂶂�Ȃ����Ǝv��
*/