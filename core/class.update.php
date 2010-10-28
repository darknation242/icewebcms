<?php
/*******************************************************************/
/*    Update class for MangosWeb Enhanced. You the user are NOT    */
/*     allowed to copy any of this code, and/or use it for any 	   */
/*         purpose other than to update MangosWeb Enhanced         */
/*******************************************************************/

class Update
{
	var $current_version;
	var $server_version;
	var $update_version;
	var $updated_files_list;
	var $writable_files;
	var $charlen_file;
	var $updates;
	
	function Update()
	{
		global $Core;
		$this->server_address = 'http://127.0.0.1/downloads/updates/';
		$this->current_version = $Core->version;
		$this->handle = FALSE;
	}
	
	// Standard check to see if the server is online function
	function connect()
	{
		$this->handle = @fsockopen('127.0.0.1', 80, $errno, $errstr, 5);
		if($this->handle)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	// This function should always be used FIRST! checks to  see if server is online, 
	// and if there's updates
	function check_for_updates() 
	{
		if($this->connect() == TRUE)
		{
			$this->updates = file_get_contents("". $this->server_address ."mangosweb.txt");
			$ups = explode(",", $this->updates );
			$this->newest = $ups['0'];
			if($this->current_version < $this->newest)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			echo "<center><font color='red'>Cant Connect to update server. Please check <a href='http://code.google.com/p/mwenhanced/'>here</a> 
					for any news pretaining to this error</font>";
			return FALSE;
		}
	}
	
	// If there is updates, then this function returns the next update version number.
	function get_next_update()
	{
		$ups = explode(",", $this->updates );
		
		// Ok, so we need the next update, but first we need to find, where in the array is out current version
		foreach($ups as $key => $value)
		{
			if($value == $this->current_version)
			{
				$tmp_version = $key;
				// Now that we have our postion, we subtract 1, to get the next update version
				$newkey = $tmp_version - 1;
			}
		}
		$this->update_version = $ups[$newkey];
		$this->get_server_variables();
		return $this->update_version;
	}
	
	// This function get the list of update files, and their sizes
	function get_server_variables() 
	{
		$variables_file_address = $this->server_address."update_". (string)$this->update_version ."/update_vars.php";
		$file = file($variables_file_address);
		foreach ($file as $line) 
		{
			if(strstr($line,"[update_version]") !== false)
			{
				$this->server_version = trim(substr($line,strpos($line,"=")+1));
			}
			elseif(strstr($line,"[update_info]") !== false)
			{
				$this->update_info[] = trim(substr($line,strpos($line,"=")+1));
			}
			elseif(strstr($line,"[update_make_dir]") !== false)
			{
				@mkdir(trim(substr($line,strpos($line,"=")+1)), 0700);
			}
			elseif(strstr($line,"[update_file_list]") !== false)
			{
				$this->updated_files_list[] = trim(substr($line,strpos($line,"=")+1));
			}
			elseif(strstr($line,"[charlen_file]") !== false)
			{
				$this->charlen_file[] = trim(substr($line,strpos($line,"=")+1));
			}
		}
	}
	
	// Prints updated file list
	function print_updated_files_list() 
	{
		$filelist = "";
		foreach ($this->updated_files_list as $filename) 
		{
			$filelist .= $filename."<br />";
		}
		return $filelist;
	}
	
	function print_update_info()
		{
		$infolist = "";
		foreach ($this->update_info as $desc) 
		{
			$infolist .= $desc."<br />";
		}
		return $infolist;
	}

	// This function checks to see if a file is writable
	private function is__writable($path) 
	{
		//Make sure to use a "/" after trailing folders
	    if ($path{strlen($path)-1} == '/') // recursively return a temporary file path
		{
	        return is__writable($path.uniqid(mt_rand()).'.tmp');
		}
	    else if (is_dir($path))
		{
	        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
		}
	    // check tmp file for read/write capabilities
	    $rm = file_exists($path);
	    $f = @fopen($path, 'a');
	    if ($f == false)
		{
	        return FALSE;
		}
	    fclose($f);
	    if (!$rm)
		{
	        unlink($path);
		}
	    return TRUE;
	}
	
	// Checks if the files are writable
	function check_if_are_writable() 
	{
		$err = "";
		foreach ($this->updated_files_list as $filename) 
		{
			if($this->is__writable($filename) == TRUE) 
			{
				$this->writable_files[$filename] = "yes";
			} 
			else 
			{
				$this->writable_files[$filename] = "no";
				$err = 1;
			}
		}
		return $err == 1 ? FALSE:TRUE;
	}
	
	// Gets the total character length of all updated files
	function get_total_charlen() 
	{
		$total_len = 0;
		foreach($this->charlen_file as $len) 
		{
			$total_len += $len;
		}
		return $total_len;
	}

	
	// Main update function
	function update_files()
	{
		$err = "";
		if($this->check_if_are_writable() == TRUE) 
		{
			$i=0;
			$len_till_now = 0;
			foreach ($this->updated_files_list as $filename) 
			{
				$updated_file_url = $this->server_address."/update_".$this->update_version."/".str_replace(".php",".upd",$filename);
				$updated_file_contents = file_get_contents($updated_file_url);

				if($updated_file_contents != "") 
				{
					$file = fopen($filename,"w");
					fwrite($file,$updated_file_contents);
					fclose($file);
				}
				$len_till_now += $this->charlen_file[$i];
				$perc = $len_till_now * 100 / $this->get_total_charlen();
				echo $filename." <font color='green'>Updated Successfully!</font><br />";
				$i++;
			}
		} 
		else 
		{
			$err = "Some file or all are not writable.";
			foreach ($this->writable_files as $id => $value) 
			{
				if($value == "no") 
				{
					echo $id." file is not writable!<br>";
				}
			}
			$err .= "No file was updated.<br>";
		}
		return $err == "" ? TRUE : $err;
	}
}
?>