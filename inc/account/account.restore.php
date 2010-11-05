<?php
if(INCLUDED!==true)exit;
// ==================== //
$pathway_info[] = array('title'=>$lang['retrieve_pass'],'link'=>'');
// ==================== //

// Load secret Questions
$sc_q = $DB->select("SELECT * FROM secret_questions");
	
// If user has requested his password be reset
if($_POST['retr_login'] && $_POST['retr_email'] && $_POST['secretq1'] && $_POST['secretq2'] && $_POST['secreta1'] && $_POST['secreta2']) 
{
  
	//set return as true - we will make false if something is wrong
	$return = TRUE;
  
	/*Check 1*/
	$username = strip_if_magic_quotes($_POST['retr_login']);
	if (check_for_symbols($username,1) == TRUE)
	{
		$return = FALSE;
	}
	else if ($DB->selectRow("SELECT * FROM `account` WHERE username='".$username."'") == false)
	{
		$username == FALSE;
		$return = FALSE;
	}
	else
	{
		$d = $DB->selectRow("SELECT * FROM `account` WHERE username='".$username."'");
		$username =& $d['id'];
		$username_name =& $d['username'];
		$email =& $d['email'];
		
		$posted_email =& $_POST['retr_email'];
		
		/*Check 2*/
		if($email != $posted_email)
		{
			$return = FALSE;
		}
	}

	$secreta1 =& $_POST['secreta1'];
	$secreta2 =& $_POST['secreta2'];  
	/*Check 3*/
	if (check_for_symbols($_POST['secreta1']) || check_for_symbols($_POST['secreta2'])) 
	{
		$return = FALSE;
	}
	  
	if ($return == FALSE)
	{
		output_message('error','<b>'.$lang['fail_restore_pass'].'</b><meta http-equiv=refresh content="3;url=index.php?p=account&sub=restore">');
	}
	elseif ($return == TRUE) 
	{
		$rp_sq1 = strip_if_magic_quotes($_POST['secretq1']);
		$rp_sq2 = strip_if_magic_quotes($_POST['secretq2']);
		$rp_sa1 = strip_if_magic_quotes($_POST['secreta1']);
		$rp_sa2 = strip_if_magic_quotes($_POST['secreta2']);
		$we = $DB->selectRow("SELECT account_id FROM `account_extend` WHERE account_id='".$username." AND secretq1='".$rp_sq1."' AND secretq2='".$rp_sq2."' AND secreta1='".$rp_sa1."' AND secreta2='".$rp_sa2."'");
		if($we !== FALSE)
		{
			$pas = random_string(7);
			$c_pas = $Account->sha_password($username_name,$pas);
			$DB->query("UPDATE `account` SET sha_pass_hash='".$c_pas."' WHERE id='".$username."'");
			$DB->query("UPDATE `account` SET sessionkey=NULL WHERE id='".$username."'");
			output_message('success','<b>'.$lang['restore_pass_ok'].'<br /> New password: '.$pas.'</b>');
		}
		else
		{
			output_message('error','<b>'.$lang['fail_restore_pass'].'</b><meta http-equiv=refresh content="3;url=index.php?n=account&sub=restore">');
		}
	}
}
?>
