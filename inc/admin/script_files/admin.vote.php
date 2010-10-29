<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

$get_sites = $DB->select("SELECT * FROM `mw_vote_sites`");

function editSite()
{
	global $DB;
	$DB->query("UPDATE `mw_vote_sites` SET
		`hostname`='".$_POST['hostname']."',
		`votelink`='".$_POST['votelink']."',
		`image_url`='".$_POST['image_url']."',
		`points`='".$_POST['points']."'
	  WHERE `id`='".$_GET['id']."'
	");
	output_message('success', 'Link successfully updated!');
}

function deleteSite()
{
	global $DB;
	$DB->query("DELETE FROM `mw_vote_sites` WHERE `id`='".$_GET['id']."'");
	output_message('success', 'Deleted Votesite');
}

function addSite()
{
	global $DB;
	$DB->query("INSERT INTO mw_vote_sites(
		`hostname`,
		`votelink`,
		`image_url`,
		`points`)
	  VALUES(
		'".$_POST['link_host']."', 
		'".$_POST['link']."', 
		'".$_POST['link_image']."', 
		'".$_POST['link_points']."'
		)
	");
	output_message('success', 'Votesite successfully added to Database!');
}
?>