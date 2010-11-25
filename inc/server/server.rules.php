<?php
if(INCLUDED!==true)exit;

$pathway_info[] = array('title' => $lang['server_rules'], 'link' => '');
$content = file_get_contents('lang/server_rules/'.$GLOBALS["user_cur_lang"].'.html');

?>
