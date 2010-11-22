<?php
/********************************************************************************************************/
/* 	DO NOT change the copyright in this file. Modification to this copyright will result in a break in  */
/* 	agreement terms. Also, any modification to this Copyright, or version info. will result in loss of  */
/*  all support by KeysWoW																				*/
/********************************************************************************************************/

class Core
{
	var $version = '3.0.0a';
	var $version_date = '2010-10-29, 2:19 pm';
	var $exp_dbversion = '1.0';

	function Core()
	{
		$this->Initialize();
	}
	
	function Initialize()
	{
		global $Config;
		$this->Cache_Refresh_Time = (int)$Config->get('cache_expire_time');
		$this->copyright = 'Powered by MangosWeb Enahnced version ' . $this->version . ' &copy; 2009-2010, <a href="http://keyswow.com">KeysWow Dev Team</a>.
			All Rights Reserved.';

		// Fill in the config with the proper directory info if the directory info is wrong
		define('SITE_DIR', dirname( $_SERVER['PHP_SELF'] ).'/');
		define('SITE_HREF', str_replace('//', '/', SITE_DIR));
		define('SITE_BASE_HREF', 'http://'.$_SERVER["HTTP_HOST"]. SITE_HREF);
		if($Config->get('site_base_href') !== SITE_BASE_HREF)
		{
			$Config->set('site_base_href', SITE_BASE_HREF);
			$Config->set('site_href', SITE_HREF);
			$Config->Save();
		}
		return TRUE;
	}
	
	function setGlobals()
	{
		global $Config;
		
		// Setup the site globals
		$GLOBALS['users_online'] = array();
		$GLOBALS['guests_online'] = 0;
		$GLOBALS['user_cur_lang'] = '';
		$GLOBALS['messages'] = '';		// For server messages
		$GLOBALS['redirect'] = '';		// For the redirect function, uses <meta> tags
		$GLOBALS['cur_selected_realm'] = '';
		
		// === Load the languages and set users language === //
		$languages = explode(",", $Config->get('available_lang'));
		if(isset($_COOKIE['Language'])) 
		{
			$GLOBALS['user_cur_lang'] = (string)$_COOKIE['Language'];
		}
		else
		{
			$GLOBALS['user_cur_lang'] = (string)$Config->get('default_lang');
			setcookie("Language", $GLOBALS['user_cur_lang'], time() + (3600 * 24 * 365));
		}
		
		// === Finds out what realm we are viewing. Sets cookie if need be. === //
		if(isset($_COOKIE['cur_selected_realm'])) 
		{
			$GLOBALS['cur_selected_realm'] = (int)$_COOKIE['cur_selected_realm'];
		}
		else
		{
			$GLOBALS['cur_selected_realm'] = (int)$Config->get('default_realm_id');
			setcookie("cur_selected_realm", (int)$Config->get('default_realm_id'), time() + (3600 * 24 * 365));
		}
	}
	
	function load_permissions()
	{
		$allow_url_fopen = ini_get('allow_url_fopen');
		if(function_exists("fsockopen")) 
		{
			$fsock = 1;
		}
		else
		{
			$fsock = 0;
		}
		$ret = array('allow_url_fopen' => $allow_url_fopen, 'allow_fsockopen' => $fsock);
		return $ret;
	}
	
	
	// === CACHING FUNCTIONS === //

	function isCached($id)
	{
		// Check if the cache file exists. If not, return false
		if(file_exists('core/cache/'.$id.'.cache'))
		{
			// If the cache file is expired
			if($this->getNextUpdate('core/cache/'.$id.'.cache') < 0)
			{
				unlink('core/cache/'.$id.'.cache'); #remove file
				return FALSE;
			}
			// Otherwise return true, the cache file exists
			else
			{
				return TRUE;
			}
		}
		else
		{
			return FALSE;
		}		
	}

	function getCache($id)
	{
		// Check if file exists incase isCache wasnt checked first. Else return false
		if(file_exists('core/cache/'.$id.'.cache'))
		{
			return file_get_contents('core/cache/'.$id.'.cache'); #return contents
		}
		else
		{
			return false;
		}		
	}

	function writeCache($id, $content)
	{
		// Write the cache file
		file_put_contents('core/cache/'.$id.'.cache', $content);
	}
	
	// Clean out all cache files. For individual delete, use deleteCache 
	function clearCache()
	{
		// get a list of all files and directories
		$files = scandir('core/cache/');
		foreach($files as $file)
		{
			// We only want to delete the the cache files, not subfolders
			if(is_file('core/cache/'.$file) && $file != 'index.html')
			{
				unlink('core/cache/'.$file); #Remove file
			}
		}
		return TRUE;
	}
	
	function deleteCache($id)
	{
		unlink('core/cache/'.$id); #Remove file
	}
	
	// Return the next cache update time on a file.
	function getNextUpdate($filename)
	{
		return (fileatime($filename) + $this->Cache_Refresh_Time) - time();
	}
}
?>