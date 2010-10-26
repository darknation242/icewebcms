<?php
if(INCLUDED!==true)exit;
// ==================== //
$pathway_info[] = array('title'=>$lang['char_manage'],'link'=>'');
// ==================== //


// Here we chack to see if user is logged in, if not, then redirect to account login screen
if($user['id']<=0)
{
    redirect('index.php?p=account&sub=login',1);
}
/*
// Here we see if the site admin has the rename system enabled
if ((int)$MW->getConfig->character_tools->rename){
    $show_rename = true;
}else{ 
$show_rename = false;
}

// Here we see if the site admin has the re-customization system enabled
if ((int)$MW->getConfig->character_tools->re_customization){
    $show_custom = true;
}else{ 
$show_custom = false;
}

// Here we see if the site admin has the race changer system enabled
if ((int)$MW->getConfig->character_tools->race_changer){
    $show_changer = true;
}else{ 
$show_changer = false;
}

// Here we see if the site admin allows faction changes 
if ((int)$MW->getConfig->character_tools->faction_change){
    $allow_faction_change = true;
}else{ 
$allow_faction_change = false;
}
*/

// The character rename starts here
$account_id = $user['id'];
$char_rename_points = 0;
$char_custom_points = 0;
$char_faction_points = 0;

// Functions
function check_if_online($name, $CHDB)
{

}
function check_if_name_exist($newname, $CHDB)
{

}
function change_name($name,$newname,$account_id, $CHDB, $DB)
{

}

// Here is WHERE the re-customization scripts start
function customize($name, $CHDB, $DB, $account_id)
{

}

// Here is where the "Race changer / Faction changer" scripts start
function check_guild($guid)
{

}
?>