<?php
/***********************************************/
/*	    Common site functions and variables    */
/***********************************************/

//======= SITE VARIABLES =======//

// Define realm types
$realm_type_def = array(
    0 => 'Normal',
    1 => 'PVP',
    4 => 'Normal',
    6 => 'RP',
    8 => 'RPPVP',
    16 => 'FFA_PVP'
);

// Define realm timezones
$realm_timezone_def = array(
     0 => 'Unknown',
     1 => 'Development',
     2 => 'United States',
     3 => 'Oceanic',
     4 => 'Latin America',
     5 => 'Tournament',
     6 => 'Korea',
     7 => 'Tournament',
     8 => 'English',
     9 => 'German',
    10 => 'French',
    11 => 'Spanish',
    12 => 'Russian',
    13 => 'Tournament',
    14 => 'Taiwan',
    15 => 'Tournament',
    16 => 'China',
    17 => 'CN1',
    18 => 'CN2',
    19 => 'CN3',
    20 => 'CN4',
    21 => 'CN5',
    22 => 'CN6',
    23 => 'CN7',
    24 => 'CN8',
    25 => 'Tournament',
    26 => 'Test Server',
    27 => 'Tournament',
    28 => 'QA Server',
    29 => 'CN9',
);

//======= SITE FUNCTIONS =======//

// Set up out messages like error and success boxes
function output_message($type,$text,$file='',$line='')
{
    if($file)$text .= "\n<br>in file: $file";
    if($line)$text .= "\n<br>on line: $line";
    echo "<div class=\"".$type."\">$text</div>";
}

// Custom Error Handler
function customError($errno, $errstr)
{
	echo "<div class=\"error\">";
	echo "<b>Error:</b> [$errno] $errstr<br />";
	//echo "Ending Script";
	echo "</div>";
	//die();
}

function sha_password($user,$pass)
{
    $user = strtoupper($user);
    $pass = strtoupper($pass);
    return SHA1($user.':'.$pass);
}

// ======== Realm Functions ======== //
function get_realm_byid($id)
{
    global $DB;
    $search_q = $DB->selectRow("SELECT * FROM realmlist WHERE id=".$id."");
    return $search_q;
}

function check_port_status($ip, $port)
{
    $ERROR_NO = null;
    $ERROR_STR = null;
	$fp1 = fsockopen($ip, $port, $ERROR_NO, $ERROR_STR,(float)1.0);
    if($fp1)
	{
        fclose($fp1);
		return true;
    }
	else
	{
        return false;
    }
}

// ======== Print Gold Functions ======== //
function parse_gold($varnumber) 
{

	$gold = array();
	$gold['gold'] = intval($varnumber/10000);
	$gold['silver'] = intval(($varnumber % 10000)/100);
	$gold['copper'] = (($varnumber % 10000) % 100);

	return $gold;
}

function get_print_gold($gold_array) 
{
	if($gold_array['gold'] > 0) 
	{
		echo $gold_array['gold'];
		echo "<img src='inc/admin/images/gold.GIF' border='0'>";
	}
	if($gold_array['silver'] > 0) 
	{
		echo $gold_array['silver'];
		echo "<img src='inc/admin/images/silver.GIF' border='0'>";
	}
	if($gold_array['copper'] > 0) 
	{
		echo $gold_array['copper'];
		echo "<img src='inc/admin/images/copper.GIF' border='0'>";
	}
}

function print_gold($gvar) 
{
	if($gvar == '---') 
	{
		echo $gvar;
	}
	else 
	{
		get_print_gold(parse_gold($gvar));
	}
}

//===== MAIL FUNCTIONS =====//

// Send Mail
function send_email($goingto,$toname,$sbj,$messg) 
{
	global $cfg;
	define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors
	$core_em = $cfg->get('site_email');
		
	// If email type "0" (SMTP)
	if($cfg->get('email_type') == 0) 
	{ 
		require_once 'core/mail/SMTP.php'; // path to 'SMTP.php' file from XPM4 package

		$f = ''.$core_em.''; // from mail address
		$t = ''.$goingto.''; // to mail address

		// standard mail message RFC2822
		$m = 'From: '.$f."\r\n".
			'To: '.$t."\r\n".
			'Subject: '.$sbj."\r\n".
			'Content-Type: text/plain'."\r\n\r\n".
			''.$messg.'';

		$h = explode('@', $t); // get client hostname
		$c = SMTP::MXconnect($h[1]); // connect to SMTP server (direct) from MX hosts list
		$s = SMTP::Send($c, array($t), $m, $f); // send mail
		// print result
		if ($s) output_message('notice', 'Mail Sent!');
		else output_message('alert', print_r($_RESULT));
		SMTP::Disconnect($c); // disconnect
	}
	elseif($cfg->get('email_type') == 1) 	// If email type "1" (MIME)
	{
		require_once 'core/mail/MIME.php'; // path to 'MIME.php' file from XPM4 package

		// compose message in MIME format
		$mess = MIME::compose($messg);
		// send mail
		$send = mail($goingto, $sbj, $mess['content'], 'From: '.$core_em.''."\n".$mess['header']);
		// print result
		echo $send ? output_message('notice', 'Mail Sent!') : output_message('alert', 'Error!');
	}
	elseif($cfg->get('email_type') == 2)	// If email type "2" (MTA Relay)
	{
		require_once 'core/mail/MAIL.php'; // path to 'MAIL.php' file from XPM4 package

		$m = new MAIL; // initialize MAIL class
		$m->From($core_em); // set from address
		$m->AddTo($goingto); // add to address
		$m->Subject($sbj); // set subject 
		$m->Html($messg); // set html message

		// connect to MTA server 'smtp.hostname.net' port '25' with authentication: 'username'/'password'
		if($cfg->get('email_use_secure') == 1) 
		{
			$c = $m->Connect($cfg->get('email_smtp_host'), $cfg->get('email_smtp_port'), $cfg->get('email_smtp_user'), $cfg->get('email_smtp_pass'), $cfg->get('email_smtp_secure')) 
				or die(print_r($m->Result));
		}
		else
		{
			$c = $m->Connect($cfg->get('email_smtp_host'), $cfg->get('email_smtp_port'), $cfg->get('email_smtp_user'), $cfg->get('email_smtp_pass')) 
				or die(print_r($m->Result));
		}

		// send mail relay using the '$c' resource connection
		echo $m->Send($c) ? output_message('notice', 'Mail Sent!') : output_message('alert', 'Error! Please check your config and make sure you inserted your MTA info correctly.');

		$m->Disconnect(); // disconnect from server
		// print_r($m->History); // optional, for debugging
	}
}

function load_smiles($dir='images/smiles/')
{
    $allfiles = scandir($dir);
    $smiles = array_diff($allfiles, array(".", "..", ".svn", "Thumbs.db", "index.html"));
    return $smiles;
}

// Gets Banned IP's. Mainly Used in the Auth.class.php
function get_banned($account_id,$returncont)
{
    global $DB;

    $get_last_ip = $DB->selectRow("SELECT * FROM account WHERE id='".$account_id."'");
    $db_IP = $get_last_ip['last_ip'];

    $ip_check = $DB->selectRow("SELECT * FROM ip_banned WHERE ip='".$db_IP."'");
    if ($ip_check == FALSE)
	{
        if ($returncont == "1")
		{
            return FALSE;
        }
    }
	else
	{
        if ($returncont == "1")
		{
            return TRUE;
        }
        else
		{
            return $db_IP;
        }
    }
}

// ======== Misc functions ======= // 
function redirect($linkto,$type=0,$wait_sec=0)
{
    if($linkto)
	{
        if($type==0)
		{
            $GLOBALS['redirect'] = '<meta http-equiv=refresh content="'.$wait_sec.';url='.$linkto.'">';
        }
		else
		{
            header("Location: ".$linkto);
        }
    }
}

function check_for_symbols($string, $space_check = 0)
{
    //$space_check=1 means space is not allowed
    $len=strlen($string);
    $allowed_chars="abcdefghijklmnopqrstuvwxyzæøåABCDEFGHIJKLMNOPQRSTUVWXYZÆØÅ0123456789";
    if(!$space_check) 
	{
        $allowed_chars .= " ";
    }
    for($i=0;$i<$len;$i++)
        if(strstr($allowed_chars,$string[$i]) == FALSE)
            return TRUE;
    return FALSE;
}

// used in account retrieve
function strip_if_magic_quotes($value)
{
    if (get_magic_quotes_gpc()) 
	{
        $value = stripslashes($value);
    }
    return $value;
}

function add_pictureletter($text)
{
	global $currtmp;
    $letter = substr($text, 0, 1);
    $imageletter = strtr(strtolower($letter),"ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ",
                                             "sozsozyyuaaaaaaaceeeeiiiidnoooooouuuuysaaaaaaaceeeeiiiionoooooouuuuyy");
    if (strpos("abcdefghijklmnopqrstuvwxyz", $imageletter) === false)
	{
        return $text;
	}
    $img = '<img src="'.$currtmp.'/images/letters/'.$imageletter.'.gif" alt="'.$letter.'" align="left"/>';
    $output = $img . substr($text, 1);
    return $output;
}

function random_string($counts)
{
    $str = "abcdefghijklmnopqrstuvwxyz";//Count 0-25
    $o = 0;
    for($i=0;$i<$counts;$i++)
	{
        if ($o == 1)
		{
            $output .= rand(0,9);
            $o = 0;
        }
		else
		{
            $o++;
            $output .= $str[rand(0,25)];
        }
    }
    return $output;
}

// ========== BB code -> HTML / HTML -> BBcode functions =========== //

// my_preview switches from BBcode to HTML
function my_preview($text,$userlevel=0) 
{
    if($userlevel<1)
	{
		$text = htmlspecialchars($text);
		if (get_magic_quotes_gpc())
		{
			$text = stripslashes($text);
		}
	}
    $text = nl2br($text);
    $text = preg_replace("/\\[b\\](.*?)\\[\\/b\\]/s","<b>$1</b>",$text);
    $text = preg_replace("/\\[i\\](.*?)\\[\\/i\\]/s","<i>$1</i>",$text);
    $text = preg_replace("/\\[u\\](.*?)\\[\\/u\\]/s","<u>$1</u>",$text);
    $text = preg_replace("/\\[s\\](.*?)\\[\\/s\\]/s","<s>$1</s>",$text);
    $text = preg_replace("/\\[hr\\]/s","<hr>",$text);
    $text = preg_replace("/\\[code\\](.*?)\\[\\/code\\]/s","<code>$1</code>",$text);
    //$text = preg_replace("/\[blockquote\](.*?)\[\/blockquote\]/s","<blockquote>$1</blockquote>",$text);
    if (strpos($text, 'blockquote') !== false)
    {
        if(substr_count($text, '[blockquote') == substr_count($text, '[/blockquote]')){
            $text = str_replace('[blockquote]', '<blockquote><div>', $text);
            $text = preg_replace('#\[blockquote=(&quot;|"|\'|)(.*)\\1\]#sU', '<blockquote><span class="bhead">Quote: $2</span><div>', $text);
            $text = preg_replace('#\[\/blockquote\]\s*#', '</div></blockquote>', $text);
        }
    }
    // Blizz quote <small><hr color="#9e9e9e" noshade="noshade" size="1"><small class="white">Q u o t e:</small><br>Text<hr color="#9e9e9e" noshade="noshade" size="1"></small>
    $text = preg_replace("/\\[img\\](.*?)\\[\\/img\\]/s","<img src=\"$1\" align=\"absmiddle\">",$text);
    $text = preg_replace("/\\[attach=(\\d+)\\]/se","check_attach('\\1')",$text);
    $text = preg_replace("/\\[url=(.*?)\\](.*?)\\[\\/url\\]/s","<a href=\"$1\" target=\"_blank\">$2</a>",$text);
    $text = preg_replace("/\\[size=(.*?)\\](.*?)\\[\\/size\\]/s","<font class='$1'>$2</font>",$text);
    $text = preg_replace("/\\[align=(.*?)\\](.*?)\\[\\/align\\]/s","<p align='$1'>$2</p>",$text);
    $text = preg_replace("/\\[color=(.*?)\\](.*?)\\[\\/color\\]/s","<font color=\"$1\">$2</font>",$text);
    $text = preg_replace("/[^\\'\"\\=\\]\\[<>\\w]([\\w]+:\\/\\/[^\n\r\t\\s\\[\\]\\>\\<\\'\"]+)/s"," <a href=\"$1\" target=\"_blank\">$1</a>",$text);
    return $text;
}

// my_previewreverse switches from HTML to BBcode
function my_previewreverse($text)
{
    $text = str_replace('<br />','',$text);
    $text = preg_replace("/<b>(.*?)<\\/b>/s","[b]$1[/b]",$text);
    $text = preg_replace("/<i>(.*?)<\\/i>/s","[i]$1[/i]",$text);
    $text = preg_replace("/<u>(.*?)<\\/u>/s","[u]$1[/u]",$text);
    $text = preg_replace("/<s>(.*?)<\\/s>/s","[s]$1[/s]",$text);
    $text = preg_replace("/<hr>/s","[hr]",$text);
    $text = preg_replace("/<code>(.*?)<\\/code>/s","[code]$1[/code]",$text);
    //$text = preg_replace("/<blockquote>(.*?)<\/blockquote>/s","[blockquote]$1[/blockquote]",$text);
    if (strpos($text, 'blockquote') !== false)
    {
        if(substr_count($text, '<blockquote>') == substr_count($text, '</blockquote>'))
		{
            $text = str_replace('<blockquote><div>', '[blockquote]', $text);
            $text = preg_replace('#\<blockquote><span class="bhead">\w+: (&quot;|"|\'|)(.*)\\1\<\/span><div>#sU', '[blockquote="$2"]', $text);
            $text = preg_replace('#<\/div><\/blockquote>\s*#', '[/blockquote]', $text);
        }
    }
    $text = preg_replace("/<img src=.([^'\"<>]+). align=.absmiddle.>/s","[img]$1[/img]",$text);
    $text = preg_replace("/(<a href=.*?<\\/a>)/se","check_url_reverse('\\1')",$text);
    $text = preg_replace("/<font color=.([^'\"<>]+).>([^<>]*?)<\\/font>/s","[color=$1]$2[/color]",$text);
    $text = preg_replace("/<font class=.([^'\"<>]+).>([^<>]*?)<\\/font>/s","[size=$1]$2[/size]",$text);
    $text = preg_replace("/<p align=.([^'\"<>]+).>([^<>]*?)<\\/p>/s","[align=$1]$2[/align]",$text);
    return $text;
}

/**
 * Composes a mangosweb url which can be used in templates for example.
 *
 * Using this function instead of handcrafted index.php?n=..&sub=.. allows
 * for easier transparent url rewriting. It also implementes encoding the
 * entities in the url so you can echo the result of this result directly
 * in your html code without causing it fail W3C validation.
 *
 * @param $page string The page to be targeted by this url
 * @param $subpage string The subpage to be targeted by this url
 * @param $params array An optional array containing additional arguments to be passed when requesting the url (default empty)
 * @param $encodentities boolean Encode the entities (like replacing & by &amp;) so it can be used in html templates directly? (defaults to true)
 * @result string The url containing all the given parameters
 * @todo Make a config option for url rewriting and implement an if switch
 *       here to make urls like /account/manage instead of 
 *       index.php?n=account&sub=manage possible.
 */
function mw_url($page, $subpage, $params=null, $encodeentities=true) 
{
    $url = "index.php?p=$page&sub=$subpage";
    if (is_array($params)) 
	{
        foreach($params as $key=>$value) 
		{
            $url .= "&$key=$value";
        }
    }
    return $encodeentities ? htmlentities($url) : $url;
}
?>