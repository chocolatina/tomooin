<?php
class Logout extends CI_Controller {
    public function index() {
        
		session_start();
		session_destroy();
		
		header("Location: /home");
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