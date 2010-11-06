<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!";
	exit;
}
//=======================//

// Setup the cache
define('CACHE_FILE', TRUE);

$postnum = 0;
$hl = '';

if ($cfg->get('fp_hitcounter') == 1)
{
    $count_my_page = "templates/offlike/hitcounter.txt";
    $hits = (int)file_get_contents($count_my_page);
    $hits++;
    file_put_contents($count_my_page, $hits);
}
if($cfg->get('enable_cache') == 1 && $Core->isCached($_COOKIE['cur_selected_theme']."_frontpage.index") != TRUE)
{
	$alltopics = $DB->select("SELECT * FROM mw_news ORDER BY `id` DESC");
	$servers = array();
	$multirealms = $DB->select("SELECT * FROM `realmlist` ORDER BY `id` ASC");
	foreach ($multirealms as $realmnow_arr)
	{
		if($cfg->get('fp_serverinfo') == 1)
		{
			$data = $DB->selectRow("SELECT address, port, timezone, icon, name, dbinfo FROM realmlist WHERE id ='".$realmnow_arr['id']."' LIMIT 1");
			$realm_data_explode = explode(';', $data['dbinfo']);

			$mangosALL = array();
			
				//DBinfo column:  username;password;port;host;WorldDBname;CharDBname
				$mangosALL = array(
					'db_type' => 'mysql',
					'db_host' => $realm_data_explode['3'],  //ip of db world
					'db_port' => $realm_data_explode['2'], //port
					'db_username' => $realm_data_explode['0'], //world user
					'db_password' => $realm_data_explode['1'], //world password
					'db_name' => $realm_data_explode['4'],  //world db name
					'db_char' => $realm_data_explode['5'], //character db name
					'db_encoding' => 'utf8'
				);
			unset($realm_data_explode);

			$CHDB_EXTRA = new Database(
				$mangosALL['db_host'],
				$mangosALL['db_port'],
				$mangosALL['db_username'],
				$mangosALL['db_password'],
				$mangosALL['db_char']
			);
			unset($mangosALL); // Free up memory.

			$server = array();
			$server['name'] = $data['name'];
			if((int)$cfg->get('fp_realmstatus') == 1)
			{
				$checkaddress = $data['address'];
				$server['realm_status'] = (check_port_status($checkaddress, $data['port']) === true) ? true : false;
			}
			$changerealmtoparam = array("changerealm_to" => $realmnow_arr['id']);
			if($cfg->get('fp_playersonline') == 1){
				$server['playersonline'] = $CHDB_EXTRA->selectCell("SELECT count(1) FROM `characters` WHERE online=1");
				$server['onlineurl'] = mw_url('server', 'playersonline', $changerealmtoparam);
			}
			if($cfg->get('fp_serverip') == 1)
			{
				$server['server_ip'] = $data['address'];
			}
			if($cfg->get('fp_servertype') == 1)
			{
				$server['type'] = $realm_type_def[$data['icon']];
			}
			if($cfg->get('fp_serverlang') == 1)
			{
				$server['language'] = $realm_timezone_def[$data['timezone']];
			}
			if($cfg->get('fp_serverpop') == 1)
			{
				$server['population'] = $CHDB_EXTRA->selectCell("SELECT count(1) FROM `characters` WHERE online=1");
			}
			if($cfg->get('fp_serveract') == 1)
			{
				$server['accounts'] = $DB->selectCell("SELECT count(*) FROM `account`");
			}
			if($cfg->get('fp_serveractive_act') == 1)
			{
				$server['active_accounts'] = $DB->selectCell("SELECT count(1) FROM `account` WHERE `last_login` > ?", date("Y-m-d", strtotime("-2 week")) . " 00:00:00");
			}
			if($cfg->get('fp_serverchars') == 1)
			{
				$server['characters'] = $CHDB_EXTRA->selectCell("SELECT count(1) FROM `characters`");
			}
			unset($CHDB_EXTRA, $data); // Free up memory.

			$server['moreinfo'] = $cfg->get('fp_server_moreinfo') && 0; // 0 is suppossed to signify that PATH TO SERVER CONFIG IS NOT NULL
			$servers[] = $server;
		}
	}
	unset($multirealms);
	/*
	if((int)$MW->getConfig->components->right_section->users_on_homepage){
		$usersonhomepage = $DB->selectCell("SELECT count(1) FROM `online`");
	}
	*/
}