<?php
if(INCLUDED!==true)exit;
// ==================== //
$pathway_info[] = array('title'=>$lang['register'],'link'=>'');
include('core/lib/class.captcha.php');


$regparams = array(
	'MIN_LOGIN_L' => 3,
	'MAX_LOGIN_L' => 16,
	'MIN_PASS_L'  => 4,
	'MAX_PASS_L'  => 16
	);
	
// ==================== //
if($user['id'] > 0)
{
	redirect('index.php?p=account&sub=manage',1);
}

if(isset($_POST['disagree']))
{
	redirect('index.php',1);
}	

// Load Secret Questions
$sc_q = $DB->select("SELECT * FROM mw_secret_questions");

// Define that users can register (for error reporting)
$allow_reg = TRUE;

// Init the error array
$err_array = array();
$err_array[0] = $lang['ref_fail'];

// If users are limited to how many accounts per IP, we find out how many this IP has.
if($cfg->get('max_act_per_ip') > 0)
{
	$count_ip = $DB->count("SELECT COUNT(*) FROM mw_account_extend WHERE registration_ip='".$_SERVER['REMOTE_ADDR']."'");
	if($count_ip >= (int)$cfg->get('max_act_per_ip'))
	{
		output_message('alert',$lang['reg_acclimit']);
		$allow_reg = false;
		$err_array[] = "You are not allowed to create any more accounts. If you feel this is in error, please contact the administrator.";
		$err_array[] = "If you are registering through a shared connection, it is advised that you use a private connection for registration.";
	}
}


// When finished registering, this is the function
function finalize()
{
	global $DB, $cfg, $allow_reg, $Account;
	
	// Check to see if we still are allowed to register
	if($allow_reg == TRUE)
	{
		// Inizialize variable, we use this after. Use this to add extensions.
		$notreturn = FALSE;

		// Extensions
		// Each extention you see down-under will check for specific user input,
		// In this step we set "requirements" for what user may input.

		// Ext 1 - Image verification
		if ($cfg->get('reg_act_imgvar') == 1)
		{
			$image_key =& $_POST['image_key'];
			$filename = quote_smart($_POST['filename_image']);
			$correctkey = $DB->selectCell("SELECT key FROM mw_acc_creation_captcha WHERE filename=".$filename);
			if (strtolower($correctkey) != strtolower($image_key) || $image_key == '')
			{
				$notreturn = TRUE;
				$err_array[] = "Inputted text for Image Verification was incorrect.";
			}
		}

		// Ext 2 - secret questions
		if ($cfg->get('reg_secret_questions') == 1)
		{
			if ($_POST['secretq1'] && $_POST['secretq2'] && $_POST['secreta1'] && $_POST['secreta2']) 
			{
				if(check_for_symbols($_POST['secreta1']) || check_for_symbols($_POST['secreta2']))
				{
					$notreturn = TRUE;
					$err_array[] = "Answers to Secret Questions contain unallowed symbols.";
				}
				if($_POST['secretq1'] == $_POST['secretq2']) 
				{
					$notreturn = TRUE;
					$err_array[] = "Secret Questions cannot be the same.";
				}
				if($_POST['secreta1'] == $_POST['secreta2']) 
				{
					$notreturn = TRUE;
					$err_array[] = "Answers to Secret Questions cannot be the same.";
				}
				if(strlen($_POST['secreta1']) < 4 || strlen($_POST['secreta2']) < 4) 
				{
					$notreturn = TRUE;
					$err_array[] = "Answers to Secret Questions must be at least 4 characters in length.";
				}
			}
			else 
			{
				$notreturn = TRUE;
				$err_array[] = "User didn't type any answers to the secret questions.";
			}
		}

		// Ext 3 - make sure password is not username
		if($_POST['r_login'] == $_POST['r_pass']) 
		{
			$notreturn = TRUE;
			$err_array[] = "Password cannot be the same as username.";
		}

		// Main add into the database
		if ($notreturn == FALSE)
		{
			if($Account->register(array(
				'username' => $_POST['r_login'],
				'sha_pass_hash' => $Account->sha_password($_POST['r_login'],$_POST['r_pass']),
				'sha_pass_hash2' => $Account->sha_password($_POST['r_login'],$_POST['r_cpass']),
				'email' => $_POST['r_email'],
				'expansion' => $_POST['r_account_type'],
				'password' => $_POST['r_pass']), 
					array(
					'secretq1'=> strip_if_magic_quotes($_POST['secretq1']),
					'secreta1' => strip_if_magic_quotes($_POST['secreta1']),
					'secretq2' => strip_if_magic_quotes($_POST['secretq2']), 
					'secreta2' => strip_if_magic_quotes($_POST['secreta2']))
					) == TRUE)
			{
				if($cfg->get('reg_invite') == 1)
				{
					$Account->delete_key($_POST['r_key']);
				}
				$reg_succ = TRUE;
			}
			else
			{
				$reg_succ = FALSE;
				$err_array[] = "Account Creation [FATAL ERROR]: User cannot be created, likely due to incorrect database configuration.  Contact the administrator.";
			}
		}
		else
		{
			$reg_succ = FALSE;
		}
		  
		// If there were any errors, then they are outputed here
		if($reg_succ == FALSE) 
		{
			if(!$err_array[1]) 
			{
				$err_array[1] = $lang['ref_fail'].": Unknown Reason";
			}
			$output_error = implode("<br>\n",$err_array);
			output_message('error',$output_error);
		}
		else
		{
			return TRUE;
		}
	}
	else
	{
		return FALSE;
	}
}
?>
