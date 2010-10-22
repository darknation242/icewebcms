<?php
/********************************************************************************************************/
/* 	DONOT touch anything in this file. Modification to this file will result in a break in agreement	*/
/*		terms. Also, any modification to this file will result in loss of all support by KeysWoW		*/
/********************************************************************************************************/

class Core
{
	var $version = '3.0.0b';
	var $version_date = '2010-10-11, 1:19 pm';
	var $exp_dbversion = '1.0';

	function Core()
	{
		$this->Initialize();
	}
	
	function Initialize()
	{
		$this->copyright = 'Powered by MangosWeb Enahnced version ' . $this->version . ' &copy; 2009-2010, <a href="http://keyswow.com">KeysWow Dev Team</a>.
								All Rights Reserved.';
		return true;
	}
	
	function load_permissions()
	{
		$allow_url_fopen = ini_get('allow_url_fopen');
		if(function_exists("fsockopen")) {
			$fsock = 1;
		}
		else
		{
			$fsock = 0;
		}
		$ret = array('allow_url_fopen' => $allow_url_fopen, 'allow_fsockopen' => $fsockopen);
		return $ret;
	}
}
?>