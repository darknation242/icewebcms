<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

$get_faq = $DB->select("SELECT * FROM `mw_faq`");

function editFaq()
{
	global $DB;
	$DB->query("UPDATE `mw_faq` SET
		`question`='".$_POST['question']."',
		`answer`='".$_POST['answer']."'
	  WHERE `id`='".$_GET['id']."'
	");
	output_message('success', 'Faq successfully updated!');
}

function deleteFaq()
{
	global $DB;
	$DB->query("DELETE FROM `mw_faq` WHERE `id`='".$_GET['id']."'");
	output_message('success', 'Deleted Faq');
}

function addFaq()
{
	global $DB;
	$DB->query("INSERT INTO mw_faq(
		`question`,
		`answer`)
	  VALUES(
		'".$_POST['question']."',  
		'".$_POST['answer']."'
		)
	");
	output_message('success', 'Faq successfully added to Database!');
}
?>