<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => $lang['account'], 'link' => '?p=account');
$pathway_info[] = array('title' => $lang['char_rename'], 'link' => '');
// ==================== //

// Load the accounts character list
$character_list = $Account->getCharacterList($user['id']);

// Initiate the page description
$Page_Desc = $lang['char_rename_desc'];
$Page_Desc = str_replace('[COST]', '<font color="blue">'.$Config->get('module_charrename_pts').'</font>', $Page_Desc);

/* 
	Buffer function for the SDL
	we need to do 2 checks before changing the name
	1) If the name already exists
	2) the if the character is online
	If both are false, change the name, subtract the web points :)
*/
function changeName()
{
	global $Config, $DB, $lang, $user;
	include('core/SDL/class.character.php');
	$Character = new Character;
	
	if(empty($_POST['newname']))
	{
		output_message('error', $lang['char_rename_newname']);
		return FALSE;
	}
	
	if($Character->checkNameExists($_POST['newname']) == FALSE)
	{
		if($Character->isOnline($_POST['id']) == FALSE)
		{
			if($Character->setName($_POST['id'], $_POST['newname']) == TRUE)
			{
				$DB->query("UPDATE `mw_account_extend` SET 
					`web_points`=(`web_points` - ".$Config->get('module_charrename_pts')."), 
					`points_spent`=(`points_spent` + ".$Config->get('module_charrename_pts').")  
				   WHERE `account_id` = ".$user['id']." LIMIT 1"
				);
				output_message('success', $lang['char_rename_success'].' Redirecting...<meta http-equiv=refresh content="3;url=?p=account&sub=rename">');
			}
		}
		else
		{
			output_message('validation', $lang['char_is_online']);
		}
	}
	else
	{
		output_message('validation', $lang['char_name_exists']);
	}
}
?>