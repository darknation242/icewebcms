<?php//========================//if(INCLUDED!==true) {	echo "Not Included!"; exit;}//=======================//$realms = $DB->select("SELECT * FROM realmlist ORDER BY `id` ASC");function saveConfig() {	$Config = new Config();			// Store New/Changed config items	foreach ($_POST as $item => $val) 	{		$key = explode('__', $item);		if ($key[0] == 'cfg') 		{			$Config->set($key[1],$val);		}	}	$Config->Save();	output_message('success','Finished! Config Successfully Updated. Please wait while your redirected... 		<meta http-equiv=refresh content="3;url=?p=admin&sub=siteconfig">');}?>