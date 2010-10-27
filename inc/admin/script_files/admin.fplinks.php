<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

$mainnav_links = array(
	'1-News', 
	'2-Account', 
	'3-GameGuide', 
	'4-Interactive', 
	'5-Media', 
	'6-Forums', 
	'7-Community',
	'8-Support'
	);

function updateOrder()
{
	global $DB;
	foreach($_POST as $key => $value)
	{
		$DB->query("UPDATE `menu_items` SET `order`='$value' WHERE `id`='$key'");
	}
	output_message('success', 'Link Order updated successfully!');
}

function editLink()
{
	global $DB;
	$DB->query("UPDATE `menu_items` SET
		`menu_id`='".$_POST['menu_id']."',
		`link_title`='".$_POST['link_title']."',
		`link`='".$_POST['link']."',
		`guest_only`='".$_POST['guest_only']."',
		`account_level`='".$_POST['account_level']."'
	  WHERE `id`='".$_GET['linkid']."'
	");
	output_message('success', 'Link successfully updated!');
}

function addLink()
{
	global $DB;
	$DB->query("INSERT INTO menu_items(
		`menu_id`,
		`link_title`,
		`link`,
		`guest_only`,
		`account_level`)
	  VALUES(
		'".$_POST['menu_id']."', 
		'".$_POST['link_title']."', 
		'".$_POST['link']."', 
		'".$_POST['guest_only']."', 
		'".$_POST['account_level']."')
	");
	output_message('success', 'Link successfully added to Database!');
}
?>