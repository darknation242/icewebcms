<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => $lang['account_manage'], 'link' => '');
// ==================== //

// Tell the cache system not to cache this page
define('CACHE_FILE', FALSE);

// check if the user is logged in. if not, redirect
if($user['id'] <= 0)
{
    redirect('?p=account&sub=login',1);
}

// First we need to load the users profile
$profile = $Account->getProfile($user['id']);
$profile['signature'] = str_replace('<br />','',$profile['signature']);

// Load secret questions as $secret_1
$secret_q = $Account->getSecretQuestions();

// ==== Functions ==== //

// Change Email, Buffer function for the SDL
function changeemail()
{
	global $lang, $user, $Account;
	$newemail = trim($_POST['new_email']);
	
	// First we check if the email is valid
	if($Account->isValidEmail($newemail))
	{	
		//Next we see if the email is used already
		if($Account->isAvailableEmail($newemail))
		{
			// Now we set the email by using the SDL
			if($Account->setEmail($user['id'], $newemail) == TRUE)
			{
				output_message('success','<b>'.$lang['change_email_success'].'</b><meta http-equiv=refresh content="3;url=?p=account&sub=manage">');
			}
		}
		else
		{
			output_message('validation','<b>'.$lang['register_email_used'].'</b><meta http-equiv=refresh content="3;url=?p=account&sub=manage">');
		}
	}
	else
	{
		output_message('validation','<b>'.$lang['invalid_email'].'</b><meta http-equiv=refresh content="3;url=?p=account&sub=manage">');
	}
}

// Change Pass. Buffer function for the SDL
function changepass()
{
	global $lang, $user, $Account;
	$newpass = trim($_POST['new_pass']);
	if(strlen($newpass)>3)
	{
		if($Account->setPassword($user['id'], $newpass) == TRUE)
		{
			output_message('success','<b>'.$lang['change_pass_success'].'</b><meta http-equiv=refresh content="3;url=?p=account&sub=manage">');
		}
		else
		{
			output_message('error', '<b>Change Password Failed! Please contact an Administrator');
		}
	}
	else
	{
		output_message('error','<b>'.$lang['change_pass_short'].'</b><meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
	}
}

// Upload Avatar
function uploadAvatar()
{
	global $user, $cfg, $DB;
	if(is_uploaded_file($_FILES['avatar']['tmp_name']))
	{
		if($_FILES['avatar']['size'] <= (int)$cfg->get('max_avatar_file_size'))
		{
			$ext = strtolower(substr(strrchr($_FILES['avatar']['name'],'.'), 1));
			if(in_array($ext,array('gif','jpg','png')))
			{
				if(@move_uploaded_file($_FILES['avatar']['tmp_name'], 'images/avatars/'.$user['id'].'.'.$ext))
				{
					list($width, $height, ,) = getimagesize('images/avatars/'.$user['id'].'.'.$ext);
					$max_avatar_size = explode('x',(string)$cfg->get('max_avatar_size'));
					if($width <= $max_avatar_size[0] || $height <= $max_avatar_size[1])
					{
						$DB->query("UPDATE mw_account_extend SET avatar='".$user['id'].'.'.$ext."' WHERE account_id='".$user['id']."' LIMIT 1");
					}
					else
					{
						output_message('warning', 'Avatar dimmensions are too big!');
						@unlink('images/avatars/'.$user['id'].'.'.$ext);
					}
				}
			}
			else
			{
				output_message('warning', 'Not a valid image type!');
			}
		}
		else
		{
			output_message('warning', 'Avatar size is too large!');
		}
	}
}

// Delete avatar, Buffer function for the SDL
function deleteAvatar()
{
	global $user, $Account;
	$go = $Account->deleteAvatar($user['id'], $_POST['avatarfile']);
	if($go == TRUE)
	{
		output_message('success', 'Avatar Deleted!<meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
	}
	else
	{
		output_message('error', 'Unable to delete avatar. Please contact an admin.<meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
	}
}

// Change secret questions, Buffer function for the SDL
function changeSQ()
{
	global $user, $lang, $DB, $Account;
	$change = $Account->setSecretQuestions($user['id'], $_POST['secretq1'], $_POST['secreta1'], $_POST['secretq2'], $_POST['secreta2']);
	if($change == 1)
	{
		output_message('success','<b>'.$lang['changed_secretq'].'</b><meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
	}
	elseif($change == 2)
	{
		output_message('error','<b>'.$lang['secretq_error_same'].'</b><meta http-equiv=refresh content="3;url=?p=account&sub=manage">');
	}
	elseif($change == 3)
	{
		output_message('error','<b>'.$lang['secretq_error_short'].'</b><meta http-equiv=refresh content="3;url=?p=account&sub=manage">');
	}
	else
	{
		output_message('error','<b>'.$lang['secretq_error_symbols'].'</b><meta http-equiv=refresh content="3;url=?p=account&sub=manage">');
	}
}

// Reset secret questions
function resetSQ()
{
	global $user, $lang, $Account;
	if($Account->resetSecretQuestions($user['id']) == TRUE)
	{
		output_message('success','<b>'.$lang['reset_secretq_success'].' Please wait while you are redirected...
			</b><meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
	}
}

// Expansion Changer. Buffer function for the SDL
function changeExp()
{
	global $user, $lang, $DB, $Account;
	if($_POST['switch_wow_type']=='wotlk')
	{
		if($Account->setExpansion($user['id'], 2) == TRUE)
		{
			output_message('success','<b>'.$lang['expansion_set'].'</b><meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
		}
	}
	elseif($_POST['switch_wow_type']=='tbc')
	{
		if($Account->setExpansion($user['id'], 1) == TRUE)
		{
			output_message('success','<b>'.$lang['expansion_set'].'</b><meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
		}
	}
	elseif($_POST['switch_wow_type']=='classic')
	{
		if($Account->setExpansion($user['id'], 0) == TRUE)
		{
			output_message('success','<b>'.$lang['expansion_set'].'</b><meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
		}
	}
}

// Main Detail changing function
function changeDetails()
{
	global $DB, $lang, $user;
	$_POST['profile']['signature'] = htmlspecialchars($_POST['profile']['signature']);	
	$DB->query("UPDATE `mw_account_extend` SET	
		`hide_email` = ".$_POST['profile']['hide_email'].",
		`hide_profile` = ".$_POST['profile']['hide_profile'].",
		`gender` = ".$_POST['profile']['gender'].",
		`homepage` = '".$_POST['profile']['homepage']."',
		`msn` = '".$_POST['profile']['msn']."',
		`location` = '".$_POST['profile']['location']."',
		`signature` = '".$_POST['profile']['signature']."'
	WHERE `account_id` = '".$user['id']."'");
	output_message('success', $lang['account_update_success'].'<meta http-equiv=refresh content="4;url=?p=account&sub=manage">');
}
?>