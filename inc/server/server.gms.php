<?php
if(INCLUDED!==true)exit;

$pathway_info[] = array('title'=>'GM list','link'=>'');

$gmlevel_w = array('Users','Moderators','Game Masters','Administrators');

// Get the correct result for our server core
if($project == "mangos") {
$result = $DB->select("
    SELECT username, gmlevel 
    FROM account 
    WHERE gmlevel>0
    ORDER BY gmlevel,username 
");
}elseif($project == "trinity") {
$result = $DB->select("
    SELECT id, gmlevel 
    FROM account_access 
    WHERE gmlevel>0
    ORDER BY gmlevel,id 
");
$result2 = $DB->select("SELECT username FROM account WHERE id=?d",$r['id']);
}else{
echo "Your current emulator \"" . $project . "\", isn't supported yet";
exit;
}

$gm_groups = array();
foreach($result as $r){
    $gm_groups[$r['gmlevel']][] = $r['id'];
}
$gm_groups = array_reverse($gm_groups,true);
unset($result);
?>