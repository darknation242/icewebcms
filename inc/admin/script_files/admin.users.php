<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

//====== Pagination Code ======/
$limit = 50; // Sets how many results shown per page	
if(!isset($_GET['page']) || (!is_numeric($_GET['page'])))
{
    $page = 1;
} 
else 
{
	$page = $_GET['page'];
}
$limitvalue = $page * $limit - ($limit);	// Ex: (2 * 25) - 25 = 25 <- data starts at 25

//===== Filter ==========// 
if($_GET['char'] && preg_match("/[a-z]/", $_GET['char']))
{
	$filter = "WHERE `username` LIKE '" . $_GET['char'] . "%'";
}
elseif($_GET['char'] == 1)
{
	$filter = "WHERE `username` REGEXP '^[^A-Za-z]'";
}
else
{
	$filter = '';
}
	
// Get all users
$getusers = $DB->select("SELECT * FROM account $filter ORDER BY `username` ASC LIMIT $limitvalue, $limit;");
$getcnt = $DB->select("SELECT username FROM account");
$totalrows = count($getcnt);

//===== Start of functions =====/

// Change password admin style :p
// Change Pass. Buffer function for the SDL
function changePass()
{
	global $lang, $Account;
	$newpass = trim($_POST['password']);
	if(strlen($newpass)>3)
	{
		if($Account->setPassword($_GET['id'], $newpass) == TRUE)
		{
			output_message('success','<b>Password set successfully! Please wait while your redirected...</b>
			<meta http-equiv=refresh content="3;url=index.php?p=admin&sub=users&id='.$_GET['id'].'">');
		}
		else
		{
			output_message('error', '<b>Change Password Failed!');
		}
	}
	else
	{
		output_message('error','<b>'.$lang['change_pass_short'].'</b>
		<meta http-equiv=refresh content="3;url=index.php?p=admin&sub=users&id='.$_GET['id'].'">');
	}
}

// Unban user
function unBan($unbanid) 
{
	global $DB;
	
}

// Delete user's account
function deleteUser($did) 
{
}

// Ban user
function banUser($bannid,$banreason) 
{
	global $DB, $user;
	if(!$banreason) 
	{
		$banreason = "Not Specified";
	}
	$DB->query("INSERT INTO `account_banned`(
		`id`, 
		`bandate`, 
		`unbandate`, 
		`bannedby`, 
		`banreason`, 
		`active`) 
	   VALUES(
		'".$bannid."', 
		'". UNIX_TIMESTAMP() ."', 
		'". UNIX_TIMESTAMP()-10 ."',
		'".$user['username']."',
		'".$banreason."',
		1)
	");
    $getipadd = $DB->selectCell("SELECT `last_ip` FROM `account` WHERE id='".$bannid."'");
    $DB->query("INSERT INTO `ip_banned`(
		`ip`, 
		`bandate`, 
		`unbandate`, 
		`bannedby`, 
		`banreason`) 
	   VALUES(
		'".$getipadd."', 
		'". UNIX_TIMESTAMP() ."', 
		'". UNIX_TIMESTAMP()-10 ."',
		'".$user['username']."', 
		'".$banreason."')
	");
	$DB->query("UPDATE account_extend SET `account_level`=5 WHERE account_id='".$bannid."'");
	output_message('success','Success. Account #'.$bannid.' Successfully banned. Reason: '.$banreason.'');
}


// Show ban form is used to input a Ban reason, before acutally banning
function showBanForm($banid) 
{
	global $DB, $cfg;
	$unme = $DB->selectCell("SELECT username FROM account WHERE id='".$banid."'");
	echo "
		<div class=\"content\">	
			<div class=\"content-header\">
				<h4><a href=\"?p=admin\">Main Menu</a> / <a href=\"?p=admin&sub=users\">Manage Users</a> / ".$unme." / Ban</h4>
			</div> <!-- .content-header -->				
			<div class=\"main-content\">
	";
	if(isset($_POST['ban_user'])) 
	{
		banUser($_POST['ban_user'],$_POST['ban_reason']);
	}
	echo "
		<form method=\"POST\" action=\"?p=admin&sub=users&id=".$banid."&action=ban\" name=\"adminform\" class=\"form label-inline\">
			<input type='hidden' name='ban_user'  value='".$banid."' />
			<table>
				<thead>
					<th><center><b>Ban Account #".$banid." (".$unme.")</b></center></th>
				</thead>
			</table>
			<br />
			<div class='field'>
				<label for='Username'>Ban Reason: </label>
				<input id='Username' name='ban_reason' size='20' type='text' class='large' />
			</div>
			
			<div class=\"buttonrow-border\">								
				<center><button><span>Ban User</span></button></center>			
			</div>

		</form>
	</div>
	";
}
?>