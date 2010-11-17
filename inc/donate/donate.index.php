<?php
if(INCLUDED!==true)exit;
// ==================== //
$pathway_info[] = array('title'=>$lang['donate'],'link'=>'index.php?n=community&sub=donate');
// ==================== //


if($user['id']<=0){
    redirect('index.php?n=account&sub=login',1);
}
else
{
if(!$_GET['action'])
{
        $profile = $auth->getprofile($user['id']);
        $profile['signature'] = str_replace('<br />','',$profile['signature']);
}
}
function parse_gold($number) {

	$gold = array();
	$gold['gold'] = intval($number/10000);
	$gold['silver'] = intval(($number % 10000)/100);
	$gold['copper'] = (($number % 10000) % 100);

	return $gold;
}
function print_gold($gold_array) {
	global $currtmp;
	if($gold_array['gold'] > 0) {
		echo $gold_array['gold'];
		echo "<img src='".$currtmp."/images/ah_system/gold.GIF'>";
	}
	if($gold_array['silver'] > 0) {
		echo $gold_array['silver'];
		echo "<img src='".$currtmp."/images/ah_system/silver.GIF'>";
	}
	if($gold_array['copper'] > 0) {
		echo $gold_array['copper'];
		echo "<img src='".$currtmp."/images/ah_system/copper.GIF'>";
	}
}

function final_print_gold($var) {
	if($var == '---') {
		echo $var;
	}
	else {
		print_gold(parse_gold($var));
	}
}
?>
