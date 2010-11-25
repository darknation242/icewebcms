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
	// If posted action was login
	if($_POST['action'] == 'login')
	{
		$login = $_POST['login'];
		$pass = $Account->sha_password($login, $_POST['pass']);
		
		// If account login was successful
		if($Account->login(array('username' => $login, 'sha_pass_hash' => $pass)))
		{
			// Check to see if we are using the phpbb3 registration module
			if($Config->get('module_phpbb3') == 1)
			{
				include('core/lib/class.phpbb.php');
				$phpbb = new phpbb($Config->get('module_phpbb3_path'), 'php');
				$phpbb_vars = array(
					"username" => $_POST['login'], 
					"password" => $_POST['pass'], 
				);
				
				// First we try to log in
				$phpbb_result = $phpbb->user_login($phpbb_vars);
				
				// If login is falied, then we try to create the account in the forums DB
				if($phpbb_result != 'SUCCESS')
				{
					// First check to see if there wasnt just an error in the login phase
					// If the user doesnt exist in the DB, then create the account
					if($phpbb->get_user_id_from_name($login) == FALSE)
					{
						$EMAIL = $DB->selectCell("SELECT `email` FROM `account` WHERE `username` LIKE ".$_POST['login']." LIMIT 1");
						$phpbb_vars = array(
							"username" => $_POST['login'], 
							"user_password" => $_POST['pass'], 
							"user_email" => $EMAIL, 
							"group_id" => "2"
						);
						$phpbb->user_add($phpbb_vars);
					}
				}
			}
			
			// Once finished, redirect to the page we came from
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
