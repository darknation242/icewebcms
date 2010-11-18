<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

include('core/class.update.php');
$Update = new Update;

// Here we check for updates, and get the list of files for that update.
function checkUpdates() 
{
	global $Update, $Core;
	if($Update->check_for_updates() == TRUE)
	{
		echo "<center><b>Updates found! New verision: <font color='green'>".$Update->get_next_update()."</b></font></center>";
		echo "<br /><u><b>Update Info:</b></u><br />";
		echo $Update->print_update_info()."<br />";
		echo "<br /><u><b>Update File list:</b></u><br />";
		echo $Update->print_updated_files_list();
		echo "	<br />
				<br />
				To find out more about this update, click <a href='http://keyswow.com/forum/'>here</a>. Updates can sometimes take up to 30 seconds depending
				on server load. Also note that these updates are <u>incremental</u> and you should re-check for updates after this update.";
		echo "<form method='POST' action='?p=admin&sub=updates' class='form label-inline'>";
		echo "<input type='hidden' name='action' value='update'>";
		echo "<br /><br />
				<div class='buttonrow-border'>								
					<center><button><span>Update MangosWeb</span></button></center>			
				</div>
			";
	}
	else
	{
		echo "<center>There are no new updates. Your version <font color='green'>". $Core->version ."</font> is up to date.</center>";
	}
}

// Runs the Update class, and updates the CMS
function runUpdate()
{
	global $Update;
	if($Update->check_for_updates() == TRUE) 
	{
		$Update->get_next_update();
		echo "<br /><b><u>1. Building file list: </u></b><br />"; 
		ob_flush();
		flush();
		
		// Echo the update list of files
		echo $Update->print_updated_files_list(); 
		ob_flush();
		flush();

		echo "<br /><b><u>2. Checking for write permissions: </u></b><br />"; 
		ob_flush();
		flush();
		
		// Check if all files are writable by the server, and list
		// the results from each file
		if($Update->check_if_are_writable() == TRUE) 
		{	
			echo "<font color='green'><b>All files are writable!</b></font><br>"; 
			ob_flush();
			flush();
		} 
		else 
		{
			echo "<font color='red'>Some files are not writable! Listing un-writable files...</font><br />";
			foreach ($Update->writable_files as $file => $value) 
			{
				if($value == 'no')
				{
					$e_val = "<font color='red'><i>Not Writable!</i></font>";
				}
				echo $file." = ".$e_val."<br />"; 
				ob_flush();
				flush();
			} 
			die();
		}

		echo "<br /><b><u>3. Starting to update files... </u></b><br />Updating...<br />"; 
		ob_flush();
		flush();
		
		// Update the files
		if($Update->update_files() == TRUE) 
		{
			echo "<br /><br /><center><font color='green'><b>All the files where succesfuly updated.</b></font></center><br />";
			echo "<form method='POST' action='?p=admin&sub=updates' class='form label-inline'>";
			echo "
				<div class='buttonrow-border'>								
					<center><button><span>Return</span></button></center>			
				</div>
			";
			ob_flush();
			flush();
		} 
		else 
		{
			echo "<br /><font coloe='red'><b>Some errors ocured while updating the files. Please inform Wilson212 @ http://keyswow.com/forum/ 
			... Along with a picture of your screen </b></font><br />"; 
			ob_flush();
			flush();
		}
	} 
	else 
	{
		echo "<br>No update neccesary. <br>"; 
		ob_flush();
		flush();
	}
}
?>