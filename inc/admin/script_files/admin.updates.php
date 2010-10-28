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
		echo "<center>Updates found! New verision: <font color='green'><b>".$Update->get_next_update()."</b></font></center>";
		echo "<center><br /><u>Update Info:</u><br /></center>";
		echo "<center>". $Update->print_update_info() ."</center>";
		echo "<center><br /><u>Update File list:</u><br /></center>";
		echo "<center>". $Update->print_updated_files_list() ."</center>";
		echo "<br />To find out more about this update, click <a href='http://keyswow.com/forum/'>here</a>. Updates can sometimes take up to 30 seconds depending
					on server load. Also note that these updates are <u>incremental</u> and you should re-check for updates after this update.";
		echo "<form method='POST' action='index.php?p=admin&sub=updates' class='form label-inline'>";
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
		
		// Echo the update list of files
		echo $Update->print_updated_files_list(); 
		ob_flush();

		echo "<br /><b><u>2. Checking for write permissions: </u></b><br />"; 
		ob_flush();
		
		// Check if all files are writable by the server, and list
		// the results from each file
		if($Update->check_if_are_writable() == TRUE) 
		{
			foreach ($Update->writable_files as $file => $value) 
			{
				if($value = 'yes')
				{
					$e_val = "<font color='green'><i>Writable</i></font>";
				}
				else
				{
					$e_val = "<font color='red'><i>Not Writable!</i></font>";
				}
				echo $file." = ".$e_val."<br />"; 
				ob_flush();
			}
			echo "<br /><font color='green'><b>All files are writable!</b></font><br>"; 
			ob_flush();
		} 
		else 
		{
			foreach ($Update->writable_files as $file => $value) 
			{
				if($value = 'yes')
				{
					$e_val = "<font color='green'><i>Writable</i></font>";
				}
				else
				{
					$e_val = "<font color='red'><i>Not Writable!</i></font>";
				}
				echo $file." = ".$e_val."<br />"; 
				ob_flush();
			}
			echo "<font color='red'>Some files are not writable.</font><br />"; 
			ob_flush();
		}

		echo "<br /><b><u>3. Starting to update files... </u></b><br />Updating...<br />"; 
		ob_flush();
		
		// Update the files
		if($Update->update_files() == TRUE) 
		{
			echo "<br /><br /><center><font color='green'><b>All the files where succesfuly updated.</b></font></center><br />";
			echo "<center>Click <a href='index.php?p=admin&sub=updates'>here</a> to return to the update screen, and check for more updates.</center>";
			ob_flush();
		} 
		else 
		{
			echo "<br /><font coloe='red'><b>Some errors ocured while updating the files. Please inform Wilson212 @ http://keyswow.com/forum/ 
			... Along with a picture of your screen </b></font><br />"; 
			ob_flush();
		}
	} 
	else 
	{
		echo "<br>No update neccesary. <br>"; 
		ob_flush();
	}
}
?>