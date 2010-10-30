<?php

include('core/class.config.php');
include('core/class.database.php');
include('core/class.paypal.php');

$cfg = new Config;
$Paypal = new Paypal;
$DB = new Database(
	$cfg->getDbInfo('db_host'), 
	$cfg->getDbInfo('db_port'), 
	$cfg->getDbInfo('db_username'), 
	$cfg->getDbInfo('db_password'), 
	$cfg->getDbInfo('db_name')
);

// Lets check to see if we are valid or not
$Paypal->setLogFile('core/logs/ipn_log.txt');
$check = $Paypal->checkPayment($_POST);
if($check == TRUE)
{
	// We must break down all the fancy stuff to get the account ID
	$account = explode(" --- ", $_POST['item_name']);
	$pre_accountid = $account['1'];
	$pre_accountid = str_replace("Account: ", "", $pre_accountid);
	$pre_accountid = explode("(#", $pre_accountid);
	$accountid = str_replace(")", "", $pre_accountid['1']);
	
	if(isset($_POST['pending_reason']))
	{
		$pending_reason = $_POST['pending_reason'];
	}
	else
	{
		$pending_reason = NULL;
	}
	
	if(isset($_POST['reason_code']))
	{
		$reason_code = $_POST['reason_code'];
	}
	else
	{
		$reason_code = NULL;
	}
	
	
	// Do the DB injection here
	$DB->query("INSERT INTO `mw_donate_transactions`(
		`trans_id`,
		`account`,
		`buyer_email`,
		`payment_type`,
		`payment_status`,
		`pending_reason`,
		`reason_code`,
		`amount`,
		`item_given`)
	   VALUES(
		'".$_POST['txn_id']."',
		'".$accountid."',
		'".$_POST['buyer_email']."',
		'".$_POST['payment_type']."',
		'".$_POST['payment_status']."',
		'".$pending_reason."',
		'".$reason_code."',
		'".$_POST['mc_gross']."',
		'0'
		)
	");
}
?>