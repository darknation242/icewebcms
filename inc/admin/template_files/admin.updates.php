<?php 
$upd = getContents();
$listup = explode(",", $upd);
foreach($listup as $list) 
{
	if($list > $Core->version) 
	{
		$uplist = "<option value=".$list.">".$list."</option>";
	}
} 
$perms = $Core->load_permissions();
if($perms[0] == 1 && $perms[1] == 1)
{
	$good_perms = 1
}
else
{
	$good_perms = 0
}
if($good_perms == 0)
{
	output_message('error', 'Allow Fopen disabled by server!');
}
?>