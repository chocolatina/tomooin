<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
// Smarty.class.php へのパス
require_once(APPPATH.'libraries/Smarty/Smarty.class.php');
 
class MY_Smarty extends Smarty
{
    private $tpl_path;
 
    function __construct()
    {
        parent::__construct();
 
        $this->tpl_path = dirname($_SERVER['SCRIPT_FILENAME']);
 
        $this->compile_dir = APPPATH . "../templates_c";  // コンパイルファイル置き場。パーミッション777とかにする
        //$this->template_dir = $this->tpl_path;     viewファイル置き場。この例ではフレームワークのルート
		$this->template_dir = APPPATH . "views";    // viewファイル置き場。この例ではフレームワークのルート
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