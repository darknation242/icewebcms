<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => $lang['account_inf0'], 'link' => '');
// ==================== //

define("CACHE_FILE", FALSE);

if($user['id']<=0)
{
    redirect('?p=account&sub=login',1);
}
?>