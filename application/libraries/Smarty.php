<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
// Smarty.class.php �ؤΥѥ�
require_once(APPPATH.'libraries/Smarty/Smarty.class.php');
 
class MY_Smarty extends Smarty
{
    private $tpl_path;
 
    function __construct()
    {
        parent::__construct();
 
        $this->tpl_path = dirname($_SERVER['SCRIPT_FILENAME']);
 
        $this->compile_dir = APPPATH . "../templates_c";  // ����ѥ���ե������֤��졣�ѡ��ߥå����777�Ȥ��ˤ���
        //$this->template_dir = $this->tpl_path;     view�ե������֤��졣������Ǥϥե졼�����Υ롼��
		$this->template_dir = APPPATH . "views";    // view�ե������֤��졣������Ǥϥե졼�����Υ롼��
        $this->left_delimiter = "<% ";
        $this->right_delimiter = " %>";
        $this->force_compile = true;
        //$this->allow_php_tag = TRUE;
 
        log_message('debug', "Smarty Class Initialized");
    }
 
    public function view($template)
    {
        $CI =& get_instance();
        $CI->output->set_output($this->fetch($template));
    }
}