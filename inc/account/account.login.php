<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => $lang['login'], 'link' => '');
// ==================== //

// Tell the cache system not to cache this page
define('CACHE_FILE', FALSE);

if(isset($_POST['action']))
{
	if($_POST['action'] == 'login')
	{
		$login = $_POST['login'];
		$pass = $Account->sha_password($login,$_POST['pass']);
		if($Account->login(array('username' => $login, 'sha_pass_hash' => $pass)))
		{
			redirect($_SERVER['HTTP_REFERER'],1);
		}
	}
	elseif($_POST['action'] == 'logout')
	{
		$Account->logout();
		redirect($_SERVER['HTTP_REFERER'],1);
	}
}
?>
