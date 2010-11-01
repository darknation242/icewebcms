<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

// ==================== //
$pathway_info[] = array('title'=>$lang['realms_status'],'link'=>'');
// ==================== //

$items = array();
$items = $DB->select("SELECT * FROM `realmlist` ORDER BY `name`");
$i = 0;
foreach($items as $i => $result)
{
    /*Extra: Add because realms is not going to be affected by anything*/
    $dbinfo_mangos = explode(';', $result['dbinfo']);  // username;password;port;host;DBName
 
	//DBinfo column:  username;password;port;host;WorldDBname;CharDBname
	$mangosALL = array(
		'db_host'     => $dbinfo_mangos['3'], //ip of db world
		'db_port'     => $dbinfo_mangos['2'], //port
		'db_username' => $dbinfo_mangos['0'], //world user
		'db_password' => $dbinfo_mangos['1'], //world password
		'db_name'     => $dbinfo_mangos['4'], //world db name
		'db_char'     => $dbinfo_mangos['5'], //character db name
		'db_encoding' => 'utf8',              // don't change
	);


    // Important! This assigns a connection to the spesific connection we have.. NOT remove this!
    $WDB_EXTRA = new Database(
		$mangosALL['db_host'], 
		$mangosALL['db_port'], 
		$mangosALL['db_username'], 
		$mangosALL['db_password'],
		$mangosALL['db_name']
	);
	
	 $CDB_EXTRA = new Database(
		$mangosALL['db_host'], 
		$mangosALL['db_port'], 
		$mangosALL['db_username'], 
		$mangosALL['db_password'],
		$mangosALL['db_char']
	);
   

    $population=0;
    if($res_color==1)
	{
		$res_color=2;
	}
	else
	{
		$res_color=1;
	}
    $realm_type = $realm_type_def[$result['icon']];
	$realm_num = $result['id'];
    if(check_port_status($result['address'], $result['port']) === TRUE)
    {
        $res_img = './templates/WotLK/images/icons/uparrow2.gif';
        if($WDB_EXTRA && $CDB_EXTRA === TRUE) 
		{
            $population = $CDB_EXTRA->select("SELECT count(*) FROM `characters` WHERE online=1");
            $uptime = time () - $DB->selectCell("SELECT `starttime` FROM `uptime` WHERE `realmid`='$realm_num' ORDER BY `starttime` DESC LIMIT 1");
        }
    }
    else
    {
        $res_img = './templates/WotLK/images/icons/downarrow2.gif';
        $population_str = 'n/a';
        $uptime = 0;
    }
    $items[$i]['res_color'] = $res_color;
    $items[$i]['img'] = $res_img;
    $items[$i]['name'] = $result['name'];
    $items[$i]['type'] = $realm_type;
    $items[$i]['pop'] = $population;
    $items[$i]['uptime'] = $uptime;
    unset($WDB_EXTRA);
    unset($CDB_EXTRA);
}

function parse_time($number) 
{
	$time = array();
    $time['d'] = intval($number/3600/24);
	$time['h'] = intval(($number % (3600*24))/3600);
	$time['m'] = intval(($number % 3600)/60);
	$time['s'] = (($number % 3600) % 60);

	return $time;
}

function print_time($time_array) 
{
	global $lang;
	$count = 0;
	if($time_array['d'] > 0) 
	{
		echo $time_array['d'];
		echo $lang['rs_days'];
		$count++;
	}
	if($time_array['h'] > 0) 
	{
        if ($count > 0) 
		{
			echo ',';
		}
		echo $time_array['h'];
		echo $lang['rs_hours'];
		$count++;
	}
	if($time_array['m'] > 0) 
	{
		if ($count > 0)
		{
			echo ',';
		}
		echo $time_array['m'];
		echo $lang['rs_minutes'];
		$count++;
	}
	if($time_array['s'] > 0) 
	{
		if ($count > 0)
		{
			echo ',';
		}
		echo $time_array['s'];
		echo $lang['rs_seconds'];
	}
}
?>
