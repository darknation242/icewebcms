<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => $lang['donate'], 'link' => '');
// ==================== //

// We define not to cache the page
define("CACHE_FILE", FALSE);

// Lets check to see the user is logged in
if($user['id'] <= 0)
{
    redirect('?p=account&sub=login',1);
}

// Include the paypal class
include('core/lib/class.paypal.php');
$Paypal = new Paypal;

// Get an array of all donate packages
$donate_packages = $DB->select("SELECT * FROM `mw_donate_packages`");

function confirmPayment()
{
	global $DB, $user, $lang;
	$pay = $DB->selectRow("SELECT * FROM `mw_donate_transactions` WHERE `account`='".$user['id']."' AND `item_given`='0' LIMIT 1");
	if($pay == FALSE)
	{
		output_message('validation', $lang['donate_no_trans']);
		echo '<br /><br /><center><b><u>Redirecting...</u></b></center> <meta http-equiv=refresh content="8;url=?p=donate">';
	}
	else
	{
		// Nedd to do checks to make sure the payment is good
		$DB->query("UPDATE `mw_donate_transactions` SET `item_given`='1' WHERE `account`='".$user['id']."' AND `id`='".$pay['id']."' LIMIT 1");
		output_message('success', $lang['donate_points_given']);
	}
}
?>
