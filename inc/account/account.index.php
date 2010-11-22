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
	$regiseter_ip = $user['lastip'];
	$joindate = '?';
}
else
{
	$regiseter_ip = $user['registration_ip'];
	$joindate = $user['joindate'];
}
?>