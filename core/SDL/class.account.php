<?php
// Account class for MangosWebSDL written by Steven Wilson, aka Wilson212
// Most functions used from the original MangosWeb AUTH Class

class Account
{
	var $DB;
    var $user = array(
		'id'    => -1,
		'username'  => 'Guest',
		'account_level' => 1,
		'theme' => 0
    );

    function Account($DB)
    {
        global $cfg;
        $this->DB = $DB;
        $this->check();
        $this->user['ip'] = $_SERVER['REMOTE_ADDR'];
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
            if(get_banned($res['id'], 1) == TRUE)
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
				$this->logout();
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
        if($res['id'] < 1)
		{
			$success = 0;
			output_message('alert','Bad username');
		}
        if(get_banned($res['id'], 1) == TRUE)
		{
            output_message('error','Your account is currently banned');
            $success = 0;
        }
        if($res['activation_code'] != NULL)
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
        if($success!=1) 
		{
			return false;
		}
        unset($params['sha_pass_hash2']);
        $password = $params['password'];
        unset($params['password']);
        if((int)$cfg->get('require_act_activation'))
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
                $act_link = (string)$cfg->get('site_base_href').'index.php?p=account&sub=activate&id='.$acc_id.'&key='.$tmp_act_key;
                $email_text  = '== Account activation =='."\n\n";
                $email_text .= 'Username: '.$params['username']."\n";
                $email_text .= 'Password: '.$password."\n";
                $email_text .= 'This is your activation key: '.$tmp_act_key."\n";
                $email_text .= 'CLICK HERE : '.$act_link."\n";
                send_email($params['email'],$params['username'],'== '.(string)$cfg->get('site_title').' account activation ==',$email_text);
                return true;
            }
			else
			{
				echo "Error with a return true code!";
                return false;
            }
        }
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
            if($acc_id == TRUE)
			{
				$u_id = $this->DB->selectCell("SELECT `id` FROM `account` WHERE `username` LIKE '".$params['username']."'");
				echo $u_id;
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
                return true;
            }
            else
			{
				echo "Bad return";
                return false;
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
	
	// Converts the username:password into a SHA1 encryption
	function sha_password($user, $pass)
	{
		$user = strtoupper($user);
		$pass = strtoupper($pass);
		return SHA1($user.':'.$pass);
	}
	
	// Check if the username is available. Post user['username'] here.
    function isavailableusername($username)
	{
        $res = $this->DB->query("SELECT count(*) FROM account WHERE username='".$username."'");
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
    function isavailableemail($email)
	{
        $res = $this->DB->query("SELECT count(*) FROM account WHERE email='".$email."'");
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
    function isvalidemail($email)
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
    function isvalidregkey($key)
	{
        $res = $this->DB->selectRow("SELECT * FROM mw_regkeys WHERE key='".$key."'");
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
    function isvalidactkey($key)
	{
        $res = $this->DB->selectRow("SELECT * FROM mw_account_extend WHERE activation_code='".$key."'");
        if($res != FALSE) 
		{
			return $res['account_id']; // key is valid
		}
		else
		{
			return FALSE; // key is not valid
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
            $slt = rand(15000, 500000);
            usleep($slt);
        }
        return $keys;
    }
	
	// Deletes a register key
    function delete_key($key)
	{
        $this->DB->query("DELETE FROM mw_regkeys WHERE key='".$key."'");
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
	
	// Returns an account username. Post an account ID here.
    function getlogin($acct_id=FALSE)
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
    function getid($acct_name=FALSE)
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
	
	// Generates a random 40 char hash
    function gethash($str=FALSE)
	{
        if($str)
		{
			return sha1(base64_encode(md5(utf8_encode($str)))); // Returns 40 char hash.
		}
        else 
		{
			return FALSE;
		}
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
        $row = $this->DB->selectRow("SELECT `username` FROM `account` WHERE `id`='$id' LIMIT 1");
        $pass_hash = $this->sha_password($row['username'], $newpass);
        $this->DB->query("UPDATE `account` SET `sha_pass_hash`='$pass_hash', `v`= 0, `s`= 0 WHERE `id`='$id' LIMIT 1");
        return TRUE;
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
			if(strlen($sa1) > 4 && strlen($sa2) > 4)
			{
				if($sa1 != $sa2 && $sq1 != $sq2)
				{
					$this->DB->query("UPDATE mw_account_extend SET secret_q1='$sq1', secret_q2='$sq2', secret_a1='$sa1', secret_a2='$sa2' WHERE account_id='$id'");
					return TRUE;
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