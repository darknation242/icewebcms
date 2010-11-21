<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

if(isset($_POST['reset']))
{
	redirect('?p=admin&sub=cache&action=reset',1);
}

function clearCache()
{
	global $Core;
	$Core->clearCache();
	output_message('success', 'Cache cleared successfully! Please wait while your redirected... 
		<meta http-equiv=refresh content="3;url=?p=admin&sub=cache">');
}

function saveConfig() 
{
	$Config = new Config();
		
	// Store New/Changed config items
	foreach ($_POST as $item => $val) 
	{
		$key = explode('__', $item);
		if ($key[0] == 'cfg') 
		{
			$Config->set($key[1],$val);
		}
	}
	$Config->Save();
	output_message('success','Finished! Cache Settings Successfully Updated. Please wait while your redirected... 
		<meta http-equiv=refresh content="3;url=?p=admin&sub=cache">');
}
?>