<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => $lang['vote_system'], 'link' => '');
// ==================== //

define("CACHE_FILE", FALSE);

// Here we chack to see if user is logged in, if not, then redirect to account login screen
if($user['id'] <= 0)
{
    redirect('?p=account&sub=login',1);
}

// Check to see what realm we are using
$realm_info_new = get_realm_byid($user['cur_selected_realmd']);
$rid = $realm_info_new['id'];

// Some glabal settings. You shouldnt need to touch this stuff
$ip_voting_period = 60 * 60 * 24; // IP voting period (in seconds)


// Here we get the sites and rewards from the database
$vote_sites = $DB->select("SELECT * FROM mw_vote_sites");

// This get the vote system started, we need to initiate the user
function initUser()
{
	global $ip_voting_period, $DB, $user;

	// Table voting
	$get_voting = $DB->selectRow("SELECT * FROM `mw_voting` WHERE `user_ip` LIKE '".$_SERVER["REMOTE_ADDR"]."' LIMIT 1");
	if ($get_voting != FALSE)
	{
		if((time() - $get_voting['time']) > $ip_voting_period)
		{
			$DB->query("UPDATE `mw_voting` SET `sites` = 0 WHERE `user_ip` LIKE '".$_SERVER["REMOTE_ADDR"]."' LIMIT 1");
			$return = array('sites' => 0, 'time' => $get_voting['time']);
		}
		else
		{
			$return = array('sites' => $get_voting['sites'], 'time' => $get_voting['time']);
		}
	}
	else
	{
		$DB->query("INSERT INTO `mw_voting` (`user_ip`) VALUES ('".$_SERVER["REMOTE_ADDR"]."')");
		$return = array('sites' => 0, 'time' => 0);
	}
	return $return;
}

function sec_to_dhms($sec, $show_days = false)
{
	$days = intval($sec / 86400);
	$hours = intval(($sec / 3600) % 24);
	$minutes = intval(($sec / 60) % 60);
	$seconds = intval($sec % 60);
	return $days." Days, ".$hours." H, ".$minutes." M ".$seconds." s";
}

function vote($site)
{
	global $cfg, $DB, $user;
	$tab_sites = $DB->selectRow("SELECT * FROM mw_vote_sites WHERE `id`='$site'");
	
	// First we check to see the users hasnt clicked vote twice
	$get_voting = $DB->selectRow("SELECT * FROM `mw_voting` WHERE `user_ip` LIKE '".$_SERVER["REMOTE_ADDR"]."' LIMIT 1");
	if($get_voting['sites'] & $tab_sites['site_key'])
	{
		output_message('validation', 'You have already voted for this site in the last 24 hours! Redirecting...
			<meta http-equiv=refresh content="4;url=?p=vote">');
		echo "<br /><br />";
	}
	else
	{
		if($tab_sites != FALSE)
		{
			if($cfg->get('module_vote_onlinecheck') == 1)
			{
				$fp = @fsockopen($tab_sites['hostname'], 80, $errno, $errstr, 3);
			}
			else
			{
				$fp = True;
			}
			if($fp)
			{
				if($cfg->get('module_vote_onlinecheck') == 1)
				{
					fclose($fp);
				}
				
				$DB->query("UPDATE `mw_voting` SET 
					`sites`=(`sites` | ".$tab_sites['site_key']."), 
					`time`='".time()."' 
				  WHERE `user_ip` LIKE '".$_SERVER["REMOTE_ADDR"]."' LIMIT 1"
				);
				
				$DB->query("UPDATE `mw_account_extend` SET 
					`web_points`=(`web_points` + ".$tab_sites['points']."), 
					`date_points`=(`date_points` + ".$tab_sites['points']."),
					`total_votes`=(`total_votes` + 1), 
					`points_earned`=(`points_earned` + ".$tab_sites['points'].")  
				   WHERE `account_id` = ".$user['id']." LIMIT 1"
				);
				echo "<script type=\"text/javascript\">setTimeout(window.open('".$tab_sites['votelink']."', '_self'),0);</script>";
			}
			else
			{
				output_message('error', 'Unable to connect to votesite. Please try again later.');
			}
		}
		else
		{
			output_message('error', 'There is no vote site with this unique ID.');

		}
	}
}
?>