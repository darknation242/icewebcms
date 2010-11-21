<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => $lang['account'], 'link' => '');
// ==================== //

define("CACHE_FILE", FALSE);

if($user['id'] <= 0)
{
    redirect('?p=account&sub=login',1);
}

if($Config->get('emulator') == 'arcemu')
{
	$regiseter_ip = '?';
	$joindate = '?';
}
else
{
	$regiseter_ip = $user['registration_ip'];
	$joindate = $user['joindate'];
}

$account_level = $DB->selectCell("SELECT `title` FROM `mw_account_groups` WHERE `account_level`='".$user['account_level']."'");
?>