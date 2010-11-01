<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

$pathway_info[] = array('title'=>$lang['howtoplay'],'link'=>'');
$content = file_get_contents("lang/howtoplay/".$GLOBALS['user_cur_lang'].".html");

?>

