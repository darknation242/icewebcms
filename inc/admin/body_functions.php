<?php
if(ini_get('allow_url_fopen') == '1') 
{ 
	$allowfopen = "<font color='green'>Yes</font>"; 
}
else
{ 
	$allowfopen = "<font color='red'>No!</font>"; 
}

if(function_exists("fsockopen")) 
{
   $fsock = "<font color='green'>Yes</font>";
}
else
{
	$fsock = "<font color='red'>No!</font>";
}

?>