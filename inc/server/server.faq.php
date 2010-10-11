<?php
if(INCLUDED!==true)exit;

// ==================== //
$pathway_info[] = array('title'=>'FAQ','link'=>'');
// ==================== //

$alltopics = $DB->select("SELECT * FROM `site_faq` ORDER BY `id`");

$cc1 = 0;
?>