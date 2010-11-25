<?php
if(INCLUDED!==true)exit;
// ==================== //
if($_GET['text']=='license'){
    $pathway_info[] = array('title'=>'License','link'=>'');
    $content = file_get_contents('lang/gnu_gpl/'.$GLOBALS["user_cur_lang"].'.html');
}
?>
