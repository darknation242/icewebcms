<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

$getrealms = $DB->select("SELECT * FROM `realmlist`");

function updateRealm()
{
	global $DB;
	$DB->query("UPDATE `realmlist` SET 
		`name`= '".$_POST['realm_name']."',
		`address`= '".$_POST['realm_address']."',
		`port`= '".$_POST['realm_port']."',
		`icon`= '".$_POST['icon']."',
		`timezone`= '".$_POST['timezone']."',
		`dbinfo`= '".$_POST['db_user'].";".$_POST['db_pass'].";".$_POST['db_port'].";".$_POST['db_host'].";".$_POST['db_name'].";".$_POST['db_char']."',
		`ra_info`= '".$_POST['ra_type'].";".$_POST['ra_port'].";".$_POST['ra_user'].";".$_POST['ra_pass']."',
		`site_enabled`= '".$_POST['site_enabled']."'
	   WHERE `id`=".$_GET['id']."
	");
	output_message('success', 'Realm Successfully Updated!');
}
?>