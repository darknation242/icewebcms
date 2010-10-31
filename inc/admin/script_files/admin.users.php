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
function adminChangePass($id, $newp) 
{
	global $DB;
	$newpd = trim($newp);
    
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
	if(!$banreason) {
		$banreason = "Not Specified";
	}
	$DB->query("INSERT into account_banned (id, bandate, unbandate, bannedby, banreason, active) 
		values (?d, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()-10, ?d, ?d, 1)", $bannid, $user['username'], $banreason);
    $getipadd = $DB->selectCell("SELECT last_ip FROM account WHERE id=?d",$bannid);
    $DB->query("INSERT into ip_banned (ip, bandate, unbandate, bannedby, banreason) values (?d, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()-10, ?d, ?d)",$getipadd, $user['username'], $banreason);
	$DB->query("UPDATE account_extend SET `account_level`=5 WHERE account_id=?",$bannid);
	output_message('notice','Success. Account #'.$bannid.' Successfully banned. Reason: '.$banreason.'.<br />
	Click <a href="index.php?p=admin&sub=users&id='.$bannid.'" />Here</a> to return to users profile');
}


// Show ban form is used to input a Ban reason, before acutally banning
function showBanForm($banid) 
{
	global $DB, $cfg;
	$unme = $DB->selectCell("SELECT username FROM account WHERE id='".$banid."'");
	echo "
	<div class=\"content-head\">
		<div class=\"desc-title\">Ban User Form</div>
		<div class=\"description\">
		<i>Description:</i> Ban users here.
		</div>
	</div>
	<div class=\"content\" align=\"center\">";
	if(isset($_POST['ban_user'])) 
	{
		banUser($_POST['ban_user'],$_POST['ban_reason']);
	}
	echo "
		<form method=\"POST\" action=\"index.php?p=admin&sub=users&id=".$banid."&action=ban\" name=\"adminform\">
		<table border=\"0\" width=\"95%\" style=\"border: 2px solid #808080;\">
			<tr>
				<td colspan=\"3\" class=\"form-head\">Ban Account #".$banid." (".$unme.")</td>
			</tr>
			<tr>
				<td width=\"20%\" align=\"right\" valign=\"middle\" class=\"form-text\">Ban Reason:</td>
				<td width=\"15%\" align=\"left\" valign=\"middle\">
				<input type=\"text\" name=\"ban_reason\" size=\"60\" class=\"inputbox\" /></td>
				<input type=\"hidden\" name=\"ban_user\" value=\"".$banid."\" />
				<td align=\"left\" valign=\"top\" class=\"form-desc\"></td>
			</tr>
			<tr>
				<td colspan=\"3\" align=\"center\" class=\"form-text2\">
					<button name=\"process\" class=\"button\" type=\"submit\"><b>Ban User</b></button>&nbsp;&nbsp;
				</td>
			</tr>
		</table>
		</form>
	</div>
	";
}
?>