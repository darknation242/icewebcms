<?php
if(INCLUDED!==true)exit;
// ==================== //
$pathway_info[] = array('title'=>$lang['accediting'],'link'=>'');
// ==================== //

// check if the user is logged in. if not, redirect
if($user['id'] <= 0)
{
    redirect('index.php?n=account&sub=login',1);
}

// First we need to load the users profile
$profile = $auth->getProfile($user['id']);
$profile['signature'] = str_replace('<br />','',$profile['signature']);

// Load secret questions as $secret_1
$secret_q = $auth->getSecretQuestions();

// ==== Functions ==== //

// Change Email, Buffer function for the SDL
function changeemail()
{
	global $lang, $user, $auth;
	$newemail = trim($_POST['new_email']);
	
	// First we check if the email is valid
	if($auth->isvalidemail($newemail))
	{	
		//Next we see if the email is used already
		if($auth->isavailableemail($newemail))
		{
			// Now we set the email by using the SDL
			if($auth->setEmail($user['id'], $newemail) == TRUE)
			{
				output_message('success','<b>'.$lang['change_mail'].'</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=manage">');
			}
		}
		else
		{
			output_message('validation','<b>'.$lang['reg_checkemailex'].'</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=manage">');
		}
	}
	else
	{
		output_message('validation','<b>'.$lang['bad_mail'].'</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=manage">');
	}
}

// Change Pass. Buffer function for the SDL
function changepass()
{
	global $lang, $user, $auth;
	$newpass = trim($_POST['new_pass']);
	if(strlen($newpass)>3)
	{
		if($auth->setPassword($user['id'], $newpass) == TRUE)
		{
			output_message('success','<b>'.$lang['change_pass_succ'].'</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=manage">');
		}
		else
		{
			output_message('error', '<b>Change Password Failed! Please contact an Administrator');
		}
	}
	else
	{
		output_message('alert','<b>'.$lang['change_pass_short'].'</b><meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
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
						$DB->query("UPDATE account_extend SET avatar='".$user['id'].'.'.$ext."' WHERE account_id=?d LIMIT 1",$user['id']);
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
	global $user, $auth;
	$go = $auth->deleteAvatar($user['id'], $_POST['avatarfile']);
	if($go == TRUE)
	{
		output_message('success', 'Avatar Deleted!<meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
	}
	else
	{
		output_message('error', 'Unable to delete avatar. Please contact an admin.<meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
	}
}

// Change secret questions, Buffer function for the SDL
function changeSQ()
{
	global $user, $lang, $DB, $auth;
	$change = $auth->setSecretQuestions($user['id'], $_POST['secretq1'], $_POST['secreta1'], $_POST['secretq2'], $_POST['secreta2']);
	if($change == TRUE)
	{
		output_message('success','<b>'.$lang['changed_secretq'].'</b><meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
	}
	elseif($change == 2)
	{
		output_message('error','<b>Secret answers or secret questions cannot be the same!</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=manage">');
	}
	elseif($change == 3)
	{
		output_message('error','<b>Secret answers must be at least 4 letters long!</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=manage">');
	}
	else
	{
		output_message('error','<b>Secret answers cannot contain symbols!</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=manage">');
	}
}

// Reset secret questions
function resetSQ()
{
	global $user, $lang;
	$DB->query("UPDATE account_extend SET secretq1='0',secretq2='0',secreta1='0',secreta2='0' WHERE account_id=?d", $user['id']);
	output_message('success','<b>'.$lang['reset_succ_secretq'].'</b><meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
}

// Expansion Changer. Buffer function for the SDL
function changeExp()
{
	global $user, $lang, $DB, $auth;
	if($_POST['switch_wow_type']=='wotlk')
	{
		if($auth->setExpansion($user['id'], 2) == TRUE)
		{
			output_message('success','<b>'.$lang['exp_set'].'</b><meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
		}
	}
	elseif($_POST['switch_wow_type']=='tbc')
	{
		if($auth->setExpansion($user['id'], 1) == TRUE)
		{
			output_message('success','<b>'.$lang['exp_set'].'</b><meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
		}
	}
	elseif($_POST['switch_wow_type']=='classic')
	{
		if($auth->setExpansion($user['id'], 0) == TRUE)
		{
			output_message('success','<b>'.$lang['exp_set'].'</b><meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
		}
	}
}

// Main Detail changing function
function changeDetails()
{
	global $DB, $lang, $user;
	$_POST['profile']['signature'] = htmlspecialchars($_POST['profile']['signature']);	
	$DB->query("UPDATE `account_extend` SET
		`theme` = ".$_POST['profile']['theme'].",	
		`hide_email` = ".$_POST['profile']['hide_email'].",
		`hide_profile` = ".$_POST['profile']['hide_profile'].",
		`gender` = ".$_POST['profile']['gender'].",
		`homepage` = '".$_POST['profile']['homepage']."',
		`msn` = '".$_POST['profile']['msn']."',
		`location` = '".$_POST['profile']['location']."',
		`signature` = '".$_POST['profile']['signature']."'
	WHERE `account_id` = '".$user['id']."'");
	output_message('success', 'Account Information Updated!<meta http-equiv=refresh content="4;url=index.php?p=account&sub=manage">');
}
?>