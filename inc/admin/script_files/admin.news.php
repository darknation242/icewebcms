<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//
$gettopics = $DB->select("SELECT `title`,`id`,`posted_by`,`post_time` FROM `site_news`");

// If posting a new News post
function addNews($subj,$message,$un) 
{
	global $DB;
    if(!$subj | !$message)
	{
		output_message('validation', 'You left a field empty!');
	}
	else
	{
		$post_time = time();
		$sql =  "INSERT INTO site_news(title, message, posted_by, post_time) VALUES('".$subj."','".$message."','".$un."','".$post_time."')";
        $tabs = $DB->query($sql);
		output_message('success', 'Successfully added news to database!');
    }
}
function editNews($idz,$mess) 
{
	global $DB;
	if(!$mess)
	{
		output_message('validation', 'You left a field empty!');
	}
	else
	{
		$DB->query("UPDATE `site_news` SET `message`='$mess' WHERE `id`='$idz'");
		output_message('success', 'Successfully edited news in database!');
	}
}
function delNews($idzz) 
{
	global $DB;
	$DB->query("DELETE FROM `site_news` WHERE `id`='$idzz'");
	output_message('success', 'Deleted News Item');
}
?>