<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

$get_pack = $DB->select("SELECT * FROM `mw_donate_packages`");

function editPkg()
{
	global $DB;
	$DB->query("UPDATE `mw_donate_packages` SET
		`desc`='".$_POST['desc']."',
		`cost`='".$_POST['cost']."',
		`points`='".$_POST['points']."'
	  WHERE `id`='".$_GET['id']."'
	");
	output_message('success', 'Donate Package successfully updated!');
}

function deletePkg()
{
	global $DB;
	$DB->query("DELETE FROM `mw_donate_packages` WHERE `id`='".$_GET['id']."'");
	output_message('success', 'Deleted Package');
}

function addPkg()
{
	global $DB;
	$DB->query("INSERT INTO mw_donate_packages(
		`desc`,
		`cost`,
		`points`)
	  VALUES(
		'".$_POST['desc']."', 
		'".$_POST['cost']."', 
		'".$_POST['points']."'
		)
	");
	output_message('success', 'Doante Package successfully added to Database!');
}
?>