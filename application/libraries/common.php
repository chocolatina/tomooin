<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// Smarty.class.php へのパス
require_once(APPPATH.'libraries/Smarty/Smarty.class.php');

 
class Common
{

	function sanit($data)
	{
		$data = htmlspecialchars($data);
		return $data;
	}
}