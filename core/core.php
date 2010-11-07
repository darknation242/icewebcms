<?php
/********************************************************************************************************/
/* 	DO NOT change the copyright in this file. Modification to this copyright will result in a break in  */
/* 	agreement terms. Also, any modification to this Copyright, or version info. will result in loss of  */
/*  all support by KeysWoW																				*/
/********************************************************************************************************/

class Core
{
	var $version = '3.0.0';
	var $version_date = '2010-10-29, 2:19 pm';
	var $exp_dbversion = '1.0';

	function Core()
	{
		$this->Initialize();
	}
	
	function Initialize()
	{
		global $cfg;
		$this->Cache_Refresh_Time = (int)$cfg->get('cache_expire_time');
		$this->copyright = 'Powered by MangosWeb Enahnced version ' . $this->version . ' &copy; 2009-2010, <a href="http://keyswow.com">KeysWow Dev Team</a>.
			All Rights Reserved.';
		return TRUE;
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