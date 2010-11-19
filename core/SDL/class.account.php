<?php
// Account class for MangosWebSDL written by Steven Wilson, aka Wilson212
// Some functions used from the original MangosWeb AUTH Class

class Account
{
	var $DB;
    var $user = array(
		'id'    => -1,
		'username'  => 'Guest',
		'account_level' => 1,
		'theme' => 0
    );
	
	// Initialize with checking for user cookies, and getting their IP
    function __construct()
    {
        global $cfg, $DB;
        $this->DB = $DB;
        $this->check();
        $this->user['ip'] = $_SERVER['REMOTE_ADDR'];
		if($cfg->get('module_onlinelist') == 1)
		{
			if($this->user['id'] < 1)
			{
				$this->onlinelist_addguest();
			}
			else 
			{
				$this->onlinelist_add();
			}
			$this->onlinelist_update();
		}
        $this->lastvisit_update($this->user);
    }

	 // Checks if user is logged in already
    function check()
    {
        global $cfg;
        if(isset($_COOKIE[((string)$cfg->get('site_cookie'))]))
		{
            list($cookie['user_id'], $cookie['account_key']) = @unserialize(stripslashes($_COOKIE[((string)$cfg->get('site_cookie'))]));
            if($cookie['user_id'] < 1)
			{
				return false;
			}
            $res = $this->DB->selectRow("
                SELECT * FROM account
                LEFT JOIN mw_account_extend ON account.id = mw_account_extend.account_id
                LEFT JOIN mw_account_groups ON mw_account_extend.account_level = mw_account_groups.account_level
                WHERE id ='".$cookie['user_id']."'");
            if($this->isBannedAccount($res['id']) == TRUE)
			{
				output_message('error','Your account is currently banned');
                $this->setgroup();
                $this->logout();
                return false;
            }
            if($res['activation_code'] != NULL)
			{
				output_message('warning','Your account is not active');
                $this->setgroup();
                return false;
            }
            if($this->matchAccountKey($cookie['user_id'], $cookie['account_key']))
			{
                unset($res['sha_pass_hash']);
                $this->user = $res;
                return true;
            }
			else
			{
                $this->setgroup();
                return false;
            }
        }
		else
		{
            $this->setgroup();
            return false;
        }
    }

	// Main login script
    function login($params)
    {
        global $cfg;
        $success = 1;
        if (empty($params)) 
		{
			return false;
		}
        if (empty($params['username']))
		{
            output_message('validation','You did not provide your username');
            $success = 0;
        }
        if (empty($params['sha_pass_hash']))
		{
            output_message('validation','You did not provide your password');
            $success = 0;
        }
        $res = $this->DB->selectRow("
            SELECT id, username, sha_pass_hash, locked FROM account
            WHERE username='".$params['username']."'");
		$res2 = $this->DB->selectRow("
			SELECT * FROM mw_account_extend 
			WHERE `account_id`='".$res['id']."'");
        if($res['id'] < 1)
		{
			$success = 0;
			output_message('alert','Bad username');
		}
        if($this->isBannedAccount($res['id']) == TRUE)
		{
            output_message('error','Your account is currently banned');
            $success = 0;
        }
        if($res2['activation_code'] != NULL)
		{
            output_message('error','Your account is not active. Please check your email to activate your account.');
            $success = 0;
        }
		
        if($success != 1) 
		{
			return FALSE;
		}
		else
		{
			if( strtoupper($res['sha_pass_hash']) == strtoupper($params['sha_pass_hash']))
			{
				$this->user['id'] = $res['id'];
				$this->user['name'] = $res['username'];
				$generated_key = $this->generate_key();
				$this->addOrUpdateAccountKeys($res['id'],$generated_key);
				$uservars_hash = serialize(array($res['id'], $generated_key));
				$cookie_expire_time = intval($cfg->get('account_key_retain_length'));
				if(!$cookie_expire_time) 
				{
					$cookie_expire_time = (60*60*24*365);   //default is 1 year
				}
				(string)$cookie_name = $cfg->get('site_cookie');
				(string)$cookie_href = $cfg->get('site_href');
				(int)$cookie_delay = (time()+$cookie_expire_time);
				setcookie($cookie_name, $uservars_hash, $cookie_delay, $cookie_href);
				return TRUE;
			}
			else
			{
				output_message('validation','Your password is incorrect');
				return FALSE;
			}
		}
    }

    function logout()
    {
        global $cfg;
        setcookie((string)$cfg->get('site_cookie'), '', time()-3600,(string)$cfg->get('site_href'));
        $this->removeAccountKeyForUser($this->user['id']);
    }
	
	// Main register script
    function register($params, $account_extend = NULL)
    {
        global $cfg;
        $success = 1;
        if(empty($params)) 
		{
			return false;
		}
        if(empty($params['username']))
		{
            output_message('validation','You did not provide your username');
            $success = 0;
        }
        if(empty($params['sha_pass_hash']) || $params['sha_pass_hash'] != $params['sha_pass_hash2'])
		{
            output_message('validation','You did not provide your password or confirm pass');
            $success = 0;
        }
        if(empty($params['email']))
		{
            output_message('validation','You did not provide your email');
            $success = 0;
        }
		if($this->isBannedIp($res['id']) == TRUE)
		{
            output_message('error','Your IP Address is currently banned');
            $success = 0;
        }
        if($success != 1) 
		{
			return false;
		}
        unset($params['sha_pass_hash2']);
        $password = $params['password'];
        unset($params['password']);
		
		// If email activation is set
        if((int)$cfg->get('require_act_activation') == 1)
		{
            $tmp_act_key = $this->generate_key();
            $params['locked'] = 1;
			$acc_id = $this->DB->query("INSERT INTO account(
				`username`,
				`sha_pass_hash`,
				`email`,
				`locked`,
				`expansion`)
			   VALUES(
				'".$params['username']."',
				'".$params['sha_pass_hash']."',
				'".$params['email']."',
				'".$params['locked']."',
				'".$params['expansion']."')
			   ");
			   
			// If the insert into account query was successful
            if($acc_id == TRUE)
			{
				$u_id = $this->DB->selectCell("SELECT `id` FROM `account` WHERE `username` LIKE '".$params['username']."'");
				
                // If we dont want to insert special stuff in account_extend...
                if ($account_extend == NULL)
				{
                    $this->DB->query("INSERT INTO mw_account_extend(
						`account_id`,
						`registration_ip`,
						`activation_code`)
					   VALUES(
						'".$u_id."',
						'".$_SERVER['REMOTE_ADDR']."',
						'".$tmp_act_key."')
					");
                }
				// We do want to insert into account extend
                else 
				{
                    $this->DB->query("INSERT INTO mw_account_extend(
						`account_id`, 
						`registration_ip`, 
						`activation_code`, 
						`secret_q1`, 
						`secret_a1`, 
						`secret_q2`, 
						`secret_a2`)
					   VALUES(
						'".$u_id."',
						'".$_SERVER['REMOTE_ADDR']."',
						'".$tmp_act_key."',
						'".$account_extend['secretq1']."', 
						'".$account_extend['secreta1']."', 
						'".$account_extend['secretq2']."', 
						'".$account_extend['secreta2']."')
					");
                }
				
				// Send email
                $act_link = (string)$cfg->get('site_base_href').'?p=account&sub=activate&id='.$u_id.'&key='.$tmp_act_key;
                $email_text  = '== Account activation =='."\n\n";
                $email_text .= 'Username: '.$params['username']."\n";
                $email_text .= 'Password: '.$password."\n";
                $email_text .= 'This is your activation key: '.$tmp_act_key."\n";
                $email_text .= 'CLICK HERE : '.$act_link."\n";
                send_email($params['email'],$params['username'],'== '.(string)$cfg->get('site_title').' account activation ==',$email_text);
                return TRUE;
            }
			
			// Insert into account table failed
			else
			{
                return FALSE;
            }
        }
		
		// Email activation disabled
		else
		{
			$acc_id = $this->DB->query("INSERT INTO account(
				`username`,
				`sha_pass_hash`,
				`email`,
				`expansion`)
			   VALUES(
				'".$params['username']."',
				'".$params['sha_pass_hash']."',
				'".$params['email']."',
				'".$params['expansion']."')
			");
			
			// If insert into account table was successfull
            if($acc_id == TRUE)
			{
				$u_id = $this->DB->selectCell("SELECT `id` FROM `account` WHERE `username` LIKE '".$params['username']."'");
                if ($account_extend == NULL)
				{
                    $this->DB->query("INSERT INTO mw_account_extend(
						`account_id`, 
						`registration_ip`)
					   VALUES(
						'".$u_id."',
						'".$_SERVER['REMOTE_ADDR']."'
					   )
					");
                }
				else
				{
                    $this->DB->query("INSERT INTO mw_account_extend(
						`account_id`, 
						`registration_ip`, 
						`secret_q1`, 
						`secret_a1`, 
						`secret_q2`, 
						`secret_a2`)
					   VALUES(
						'".$u_id."',
						'".$_SERVER['REMOTE_ADDR']."',
						'".$account_extend['secretq1']."', 
						'".$account_extend['secreta1']."', 
						'".$account_extend['secretq2']."', 
						'".$account_extend['secreta2']."')
					");
                }
                return TRUE;
            }
            else
			{
                return FALSE;
            }
        }
    }
	
	// Last update set the current time under the account_extend database to get
	// an approximate time when the user was last online. Post $user['id'] here.
	function lastvisit_update($uservars)
    {
        if($uservars['id'] > 0)
		{
            if(time() - $uservars['last_visit'] > 60*10)
			{
                $this->DB->query("UPDATE `mw_account_extend` SET last_visit='".time()."' WHERE account_id='".$uservars['id']."' LIMIT 1");
            }
        }
    }
	
	function getgroup($g_id=FALSE)
	{
        $res = $this->DB->selectRow("SELECT * FROM mw_account_groups WHERE account_level='".$g_id."'");
        return $res;
    }
	
	function setgroup($gid=1) // 1 - guest, 5- banned
    {
        $guest_g = $this->getgroup($gid);
        $this->user = array_merge($this->user,$guest_g);
    }
	
	// Converts the username:password into a SHA1 encryption
	function sha_password($user, $pass)
	{
		$user = strtoupper($user);
		$pass = strtoupper($pass);
		return SHA1($user.':'.$pass);
	}
	
	// Check if the username is available. Post user['username'] here.
    function isAvailableUsername($username)
	{
        $res = $this->DB->count("SELECT COUNT(*) FROM account WHERE username='".$username."'");
        if($res < 1) 
		{
			return TRUE; // username is available
		}
		else
		{
			return FALSE; // username is not available
		}
    }

	// Check if the email is available. Post an email address here.
    function isAvailableEmail($email)
	{
        $res = $this->DB->count("SELECT COUNT(*) FROM account WHERE email='".$email."'");
        if($res < 1) 
		{
			return TRUE; // email is available
		}
		else
		{
			return FALSE; // email is not available
		}
    }
	
	// Checks if the email is in valid format.
    function isValidEmail($email)
	{
        if(preg_match('#^.{1,}@.{2,}\..{2,}$#', $email)==1)
		{
            return TRUE; // email is valid
        }
		else
		{
            return FALSE; // email is not valid
        }
    }
	
	// Checks if the register key is valid
    function isValidRegkey($key)
	{
        $res = $this->DB->selectCell("SELECT `id` FROM `mw_regkeys` WHERE `key`='".$key."'");
        if($res != FALSE) 
		{
			return TRUE; // key is valid
		}
        else
		{
			return FALSE; // key is not valid
		}
    }
	
	// Checks is the account activation key is valid
    function isValidActivationKey($key)
	{
        $res = $this->DB->selectCell("SELECT `account_id` FROM `mw_account_extend` WHERE `activation_code`='".$key."'");
        if($res != FALSE) 
		{
			return $res; // key is valid
		}
		else
		{
			return FALSE; // key is not valid
		}
    }
	
	function isBannedAccount($account_id)
	{
		global $DB;
		$check = $DB->count("SELECT COUNT(*) FROM `account_banned` WHERE `id`='".$account_id."' AND `active`=1");
		if ($check < 1)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function isBannedIp()
	{
		global $DB;
		$check = $DB->count("SELECT COUNT(*) FROM `ip_banned` WHERE `ip`='".$_SERVER['REMOTE_ADDR']."'");
		if ($check < 1)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	// Generate a unique key
    function generate_key()
    {
        $str = microtime(1);
        return sha1(base64_encode(pack("H*", md5(utf8_encode($str)))));
    }
	
	// Generate multiple keys. Post amount of keys needed
    function generate_keys($n)
    {
        set_time_limit(600);
        for($i=1;$i<=$n;$i++)
        {
            if($i > 1000)
			{
				exit;
			}
            $keys[] = $this->generate_key();
            $slt = 15000;
            usleep($slt);
        }
        return $keys;
    }
	
	// Deletes a register key
    function delete_key($key)
	{
        $this->DB->query("DELETE FROM `mw_regkeys` WHERE `key`='".$key."'");
		return TRUE;
    }
	
	// Gets all the users info from the database including username, email
	// account level, id, and all sorts. post an account id here
	function getProfile($acct_id=FALSE)
	{
		global $cfg;
		$res = $this->DB->selectRow("
			SELECT * FROM account
			LEFT JOIN mw_account_extend ON account.id = mw_account_extend.account_id
			LEFT JOIN mw_account_groups ON mw_account_extend.account_level = mw_account_groups.account_level
			WHERE id='".$acct_id."'");
        return $res;
    }
	
	// Returns an account username. Post an account ID here.
    function getLogin($acct_id=FALSE)
	{
        $res = $this->DB->selectRow("SELECT username FROM account WHERE id='".$acct_id."'");
        if($res == FALSE)
		{
			return FALSE;  // no such account
		}
		else
		{
			return $res;
		}
    }
	
	// Gets an account id. Post username here
    function getAccountId($acct_name=FALSE)
	{
        $res = $this->DB->selectCell("SELECT id FROM account WHERE username='".$acct_name."'");
        if($res == FALSE)
		{
			return FALSE;  // no such account
		}
		else
		{
			return $res;
		}
    }
	
	// Loads characters list for a specific account
	function getCharacterList($id)
	{
		global $CDB;
		$list = $CDB->select("SELECT * FROM `characters` WHERE `account`='".$id."'");
		return $list;
	}
	
	// Loads secret questions from the Database and returns them in an array.
	function getSecretQuestions()
	{
		$getsc = $this->DB->select("SELECT * FROM `mw_secret_questions`");
		return $getsc;
	}
	
	// Sets an accounts email. Post an account id and new email address.
	function setEmail($id, $newemail)
	{
		$id = mysql_real_escape_string($id);
        $newemail = mysql_real_escape_string($newemail);
		$this->DB->query("UPDATE `account` SET `email`='".$newemail."' WHERE `id`='$id' LIMIT 1");
		return TRUE;
	}
	
	// Sets the expansion for an account. Post an account id and Expansion number here.
	// 2 = WotLK, 1 = TBC, 0 = Base
	function setExpansion($id, $nexp)
    {
        $id = mysql_real_escape_string($id);
        $nexp = mysql_real_escape_string($nexp);
        $this->DB->query("UPDATE `account` SET `expansion`='$nexp' WHERE `id`=$id");
        return TRUE;
    }
	
	// Sets a password for an account. Post an account id and New password here.
	function setPassword($id, $newpass)
    {
        $id = mysql_real_escape_string($id);
        $newpass = mysql_real_escape_string($newpass);
        $username = $this->DB->selectCell("SELECT `username` FROM `account` WHERE `id`='$id' LIMIT 1");
		if($username != FALSE)
		{
			$pass_hash = $this->sha_password($username, $newpass);
			$this->DB->query("UPDATE `account` SET `sha_pass_hash`='$pass_hash', `sessionkey`= NULL, `v`= '0', `s`= '0' WHERE `id`='$id' LIMIT 1");
			return TRUE;
		}
		else
		{
			return FALSE;
		}
    }
	
	// Sets the secret questions and answers for an account.
	// Post in order, account id, question 1 ,  answer 1, question 2, answer 2.
	function setSecretQuestions($id, $sq1, $sa1, $sq2, $sa2)
	{
		$sq1 = strip_if_magic_quotes($sq1);
		$sa1 = strip_if_magic_quotes($sa1);
		$sq2 = strip_if_magic_quotes($sq2);
		$sa2 = strip_if_magic_quotes($sa2);
		
		// Check for symbols
		if(check_for_symbols($sa1) == FALSE && check_for_symbols($sa2) == FALSE && $sq1 != '0' && $sq2!= '0')
		{
			if(strlen($sa1) >= 4 && strlen($sa2) >= 4)
			{
				if($sa1 != $sa2 && $sq1 != $sq2)
				{
					$this->DB->query("UPDATE `mw_account_extend` SET `secret_q1`='$sq1', `secret_q2`='$sq2', `secret_a1`='$sa1', `secret_a2`='$sa2' WHERE `account_id`='$id'");
					return 1; // 1 = Set
				}
				else
				{
					return 2; // 2 = Answers or questions where the same
				}
			}
			else
			{
				return 3; // Answers where less then 4 characters long
			}
		}
		else
		{
			return 4; // Answers contained symbols
		}
	}
	
	function resetSecretQuestions($id)
	{
		$this->DB->query("UPDATE mw_account_extend SET secret_q1=NULL, secret_q2=NULL, secret_a1=NULL, secret_a2=NULL WHERE account_id='".$id."'");
		return TRUE;
	}
	
	function deleteAvatar($id, $file)
	{
		if(@unlink('images/avatars/'.$file))
		{
			$this->DB->query("UPDATE mw_account_extend SET avatar=NULL WHERE account_id=".$id." LIMIT 1");
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	// === ONLINE FUNCTIONS === //
    function onlinelist_update()  // Updates list & delete old
    {
        $GLOBALS['guests_online'] = 0;
        $rows  = $this->DB->select("SELECT * FROM `mw_online`");
        foreach($rows as $result_row)
        {
            if(time()-$result_row['logged'] <= 60*5)
            {
                if($result_row['user_id'] > 0)
				{
					$GLOBALS['users_online'][] = $result_row['user_name'];
                }
				else
				{
					$GLOBALS['guests_online']++;
                }
            }
            else
            {
                $this->DB->query("DELETE FROM `mw_online` WHERE `id`='".$result_row['id']."' LIMIT 1");
            }
        }
    }

    function onlinelist_add() // Add or update list with new user
    {
        global $user;

        $result = $this->DB->count("SELECT COUNT(*) FROM `mw_online` WHERE `user_id`='".$this->user['id']."'");
        if($result > 0)
        {
            $this->DB->query("UPDATE `mw_online` SET 
				`user_ip`='".$this->user['ip']."',
				`logged`='".time()."',
				`currenturl`='".$_SERVER['REQUEST_URI']."' 
			  WHERE `user_id`='".$this->user['id']."' LIMIT 1
			");
        }
        else
        {
            $this->DB->query("INSERT INTO `mw_online`(
				`user_id`,
				`user_name`,
				`user_ip`,
				`logged`,
				`currenturl`) 
			  VALUES(
				'".$this->user['id']."',
				'".$this->user['username']."',
				'".$this->user['ip']."',
				'".time()."',
				'".$_SERVER['REQUEST_URI']."')
			");
        }
    }

    function onlinelist_addguest() // Add or update list with new guest
    {
        global $user;

        $result = $this->DB->count("SELECT  COUNT(*) FROM `mw_online` WHERE `user_id`='0' AND `user_ip`='".$this->user['ip']."'");
        if($result > 0)
        {
            $this->DB->query("UPDATE `mw_online` SET 
				`user_ip`='".$this->user['ip']."',
				`logged`='".time()."',
				`currenturl`='".$_SERVER['REQUEST_URI']."' 
			  WHERE `user_id`='0' AND `user_ip`='".$this->user['ip']."' LIMIT 1");
        }
        else
        {
            $this->DB->query("INSERT INTO `mw_online`(
				`user_ip`,
				`logged`,
				`currenturl`) 
			  VALUES(
				'".$this->user['ip']."',
				'".time()."',
				'".$_SERVER['REQUEST_URI']."')
			");
        }
    }

	
	// === ACCOUNT KEY FUNCTIONS === //
	function matchAccountKey($id, $key) 
	{
		$this->clearOldAccountKeys();
		global $DB;
		$count = $this->DB->selectRow("SELECT * FROM mw_account_keys WHERE id='$id'");
		if($count == FALSE) 
		{
			return FALSE;
		}
		else
		{
			$account_key = $this->DB->selectRow("SELECT * FROM mw_account_keys WHERE id='$id'");
			if($key == $account_key['key']) 
			{
				return TRUE;
			}
			else 
			{
				output_message('error', 'Account Keys Error!');
				return FALSE;
			}
		}
	}

	function clearOldAccountKeys() 
	{
		global $DB;
		global $cfg;

		$cookie_expire_time = (int)$cfg->get('account_key_retain_length');
		if(!$cookie_expire_time) 
		{
			$cookie_expire_time = (60*60*24*365);   //default is 1 year
		}

		$expire_time = time() - $cookie_expire_time;

		$this->DB->query("DELETE FROM mw_account_keys WHERE assign_time < ".$expire_time."");
	}

	function addOrUpdateAccountKeys($id, $key) 
	{
		global $DB;

		$current_time = time();
		$go = $DB->selectRow("SELECT * FROM mw_account_keys WHERE id = '".$id."'");
		if($go == FALSE) //need to INSERT
		{
			$this->DB->query("INSERT INTO mw_account_keys (`id`, `key`, `assign_time`) VALUES ('$id', '$key', '$current_time')");
		}
		else //need to UPDATE
		{              
			$this->DB->query("UPDATE `mw_account_keys` SET `key`='$key', `assign_time`='$current_time' WHERE `id`='$id'");
		}
	}

	function removeAccountKeyForUser($id) 
	{
		global $DB;

		$count = $this->DB->selectRow("SELECT * FROM mw_account_keys where id ='$id'");
		if($count == FALSE) 
		{
			//do nothing
		}
		else 
		{
			$this->DB->query("DELETE FROM mw_account_keys WHERE id ='$id'");
		}
	}
}
?>