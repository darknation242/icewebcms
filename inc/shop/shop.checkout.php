<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => 'Shop', 'link' => '');
// ==================== //

define("CACHE_FILE", FALSE);

// Lets check to see the user is logged in
if($user['id'] <= 0)
{
    redirect('?p=account&sub=login',1);
}

if(!$_POST['action'])
{
	//redirect('?p=shop',1);
}

$package = $DB->selectRow("SELECT * FROM `mw_shop_items` WHERE `id`='".$_POST['id']."'");
$character_list = $Account->getCharacterList($user['id']);

// Include the RA Socket class
include('core/SDL/class.rasocket.php');
$RA = new RA;

function completeOrder()
{
	global $RA, $user, $DB, $WDB, $package, $lang;
	
	if($package['wp_cost'] > $user['web_points'])
	{
		output_message('validation', $lang['not_enough_points']);
		return FALSE;
	}
	
	$command = array();
	if($package['item_number'] != 0) 
	{
		$command[] = "send items ".$_POST['char']." \"".$lang["shop_mail_subject"]."\" \"".
			$lang["shop_mail_message"]."\" ".$package['item_number'].":".$package['quanity'];
	}
	if($package['itemset'] != 0) 
	{
		$qray = $WDB->select("SELECT `entry` FROM `item_template` WHERE itemset='".$package['itemset']."'");
		$items = '';
		foreach($qray as $d)
		{
			$items .= $d['entry'].":1 ";
		}
		$command[] = "send items ".$_POST['char']." \"".$lang["shop_mail_subject"]."\" \"".
				$lang["shop_mail_message"]."\" ".$items;
	}
	if($package['gold'] != 0) 
	{
		$command[] = "send money ".$_POST['char']." \"".$lang["shop_mail_subject"]."\" \"".
			$lang["shop_mail_message"]."\" ".$package['gold'];
	}
	
	$send = $RA->send($command, $GLOBALS['cur_selected_realm']);
	if($send == 1 || $send == 2)
	{
		output_message('error', 'Please contact an administrator as there is an error connecting or authenticating with the server. You will NOT be charged at this time');
		return FALSE;
	}
	else
	{
		$success = 0;
		$total_commands = count($command);
		foreach($send as $report)
		{
			if(strpos($report, $_POST['char']))
			{
				$success++;
			}				
		}
		if($success == $total_commands)
		{
			output_message('success', 'Items Sent Successfully!');
		}
		else
		{
			output_message('validation', 'Some Items NOT sent successfully! Please contact an admin.');
		}
	}
}
?>