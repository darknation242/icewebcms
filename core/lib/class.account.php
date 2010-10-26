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
                LEFT JOIN account_extend ON account.id=account_extend.account_id
                LEFT JOIN account_groups ON account_extend.account_level=account_groups.account_level
                WHERE id ='".$cookie['user_id']."'");
            if(get_banned($res['id'], 1) == TRUE)
			{
                $this->setgroup();
                output_message('error','Your account is currently banned');
                $this->logout();
                return false;
            }
            if($res['activation_code'] != null)
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
        if($res['activation_code'] != null)
		{
            output_message('error','Your account is not active. Please check your email to activate your account.');
            $success = 0;
        }
        if($success != 1) 
		{
			return false;
		}
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
            return true;
        }
		else
		{
            output_message('validation','Your password is incorrect');
            return false;
        }
    }

    function logout()
    {
        global $cfg;
        setcookie((string)$cfg->get('site_cookie'), '', time()-3600,(string)$cfg->get('site_href'));
        $this->removeAccountKeyForUser($this->user['id']);
    }

    function lastvisit_update($uservars)
    {
        if($uservars['id']>0)
		{
            if(time() - $uservars['last_visit'] > 60*10)
			{
                $this->DB->query("UPDATE `account_extend` SET last_visit='".time()."' WHERE account_id='".$uservars['id']."' LIMIT 1");
            }
        }
    }
	
	// Main register script
    function register($params, $account_extend = false)
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
            if($acc_id = $this->DB->query("INSERT INTO account SET ?a",$params))
			{
                // If we dont want to insert special stuff in account_extend...
                if ($account_extend == NULL)
				{
                    $this->DB->query("INSERT INTO account_extend SET account_id=?d, registration_ip=?, activation_code=?",$acc_id,$_SERVER['REMOTE_ADDR'],$tmp_act_key);
                }
                else 
				{
                    $this->DB->query("INSERT INTO account_extend SET account_id=?d, registration_ip=?, activation_code=?, secret_q1=?s, secret_a1=?s, secret_q2=?s, secret_a2=?s",$acc_id,$_SERVER['REMOTE_ADDR'],$tmp_act_key,$account_extend['secretq1'], $account_extend['secreta1'], $account_extend['secretq2'], $account_extend['secreta2']);
                }
                $act_link = (string)$cfg->get('base_href').'index.php?p=account&sub=activate&id='.$acc_id.'&key='.$tmp_act_key;
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
                return false;
            }
        }
		else
		{
            if($acc_id = $this->DB->query("INSERT INTO account SET ?a",$params))
			{
                if ($account_extend == false)
				{
                    $this->DB->query("INSERT INTO account_extend SET account_id=?d, registration_ip=?, activation_code=?",$acc_id,$_SERVER['REMOTE_ADDR'],$tmp_act_key);
                }
				else
				{
                    $this->DB->query("INSERT INTO account_extend SET account_id=?d, registration_ip=?, activation_code=?, secret_q1=?s, secret_a1=?s, secret_q2=?s, secret_a2=?s",$acc_id,$_SERVER['REMOTE_ADDR'],$tmp_act_key,$account_extend['secretq1'], $account_extend['secreta1'], $account_extend['secretq2'], $account_extend['secreta2']);
                }
                return true;
            }
            else
			{
                return false;
            }
        }
    }
	
    function isavailableusername($username)
	{
        $res = $this->DB->selectCell("SELECT count(*) FROM account WHERE username='".$username."'");
        if($res < 1) return true; // username is available
        return false; // username is not available
    }

    function isavailableemail($email)
	{
        $res = $this->DB->selectCell("SELECT count(*) FROM account WHERE email='".$email."'");
        if($res < 1) 
		{
			return true; // email is available
		}
		else
		{
			return false; // email is not available
		}
    }
	
    function isvalidemail($email)
	{
        if(preg_match('#^.{1,}@.{2,}\..{2,}$#', $email)==1)
		{
            return true; // email is valid
        }
		else
		{
            return false; // email is not valid
        }
    }
	
    function isvalidregkey($key)
	{
        $res = $this->DB->selectRow("SELECT * FROM site_regkeys WHERE key='".$key."'");
        if($res != FALSE) 
		{
			return true; // key is valid
		}
        else
		{
			return false; // key is not valid
		}
    }
	
    function isvalidactkey($key)
	{
        $res = $this->DB->selectRow("SELECT * FROM account_extend WHERE activation_code='".$key."'");
        if($res != FALSE) 
		{
			return $res['account_id']; // key is valid
		}
		else
		{
			return false; // key is not valid
		}
    }
	
    function generate_key()
    {
        $str = microtime(1);
        return sha1(base64_encode(pack("H*", md5(utf8_encode($str)))));
    }
	
    function generate_keys($n)
    {
        set_time_limit(600);
        for($i=1;$i<=$n;$i++)
        {
            if($i>1000)exit;
            $keys[] = $this->generate_key();
            $slt = rand(15000, 500000);
            usleep($slt);
            //sleep(1);
        }
        return $keys;
    }
	
    function delete_key($key)
	{
        $this->DB->query("DELETE FROM site_regkeys WHERE key='".$key."'");
    }
	
	function getProfile($acct_id=false)
	{
		global $cfg;
		if($cfg->get('emulator') == 'trinity') 
		{
			$res = $this->DB->selectRow("
				SELECT * FROM account
				LEFT JOIN account_extend ON account.id=account_extend.account_id
				LEFT JOIN account_groups ON account_extend.account_level=account_groups.account_level
				WHERE id='".$acct_id."'");
		}
		else
		{
			$res = $this->DB->selectRow("
				SELECT * FROM account
				LEFT JOIN account_extend ON account.id=account_extend.account_id
				LEFT JOIN account_groups ON account_extend.account_level=account_groups.account_level
				WHERE id='".$acct_id."'");
		}
        return $res;
    }
	
    function getgroup($g_id=false)
	{
        $res = $this->DB->selectRow("SELECT * FROM account_groups WHERE account_level='".$g_id."'");
        return $res;
    }
	
	function setgroup($gid=1) // 1 - guest, 5- banned
    {
        $guest_g = $this->getgroup($gid);
        $this->user = array_merge($this->user,$guest_g);
    }
	
    function getlogin($acct_id=false)
	{
        $res = $this->DB->selectRow("SELECT username FROM account WHERE id='".$acct_id."'");
        if($res == FALSE)
		{
			return false;  // no such account
		}
		else
		{
			return $res;
		}
    }
	
    function getid($acct_name=false)
	{
        $res = $this->DB->selectCell("SELECT id FROM account WHERE username='".$acct_name."'");
        if($res == FALSE)
		{
			return false;  // no such account
		}
		else
		{
			return $res;
		}
    }
	
    function gethash($str=false)
	{
        if($str)
		{
			return sha1(base64_encode(md5(utf8_encode($str)))); // Returns 40 char hash.
		}
        else 
		{
			return false;
		}
    }
	
	function getSecretQuestions()
	{
		$getsc = $this->DB->select("SELECT * FROM `site_secret_questions`");
		return $getsc;
	}
	
	function setEmail($id, $newemail)
	{
		$id = mysql_real_escape_string($id);
        $newemail = mysql_real_escape_string($newemail);
		$this->DB->query("UPDATE `account` SET `email`='$newemail' WHERE `id`='$id' LIMIT 1");
		return TRUE;
	}
	
	function setExpansion($id, $nexp)
    {
        $id = mysql_real_escape_string($id);
        $nexp = mysql_real_escape_string($nexp);
        $this->DB->query("UPDATE `account` SET `expansion`='$nexp' WHERE `id`=$id");
        return TRUE;
    }
	
	function setPassword($id, $newpass)
    {
        $id = mysql_real_escape_string($id);
        $newpass = mysql_real_escape_string($newpass);
        $row = $this->DB->selectRow("SELECT `username` FROM `account` WHERE `id`='$id' LIMIT 1");
        $pass_hash = sha_password($row['username'], $newpass);
        $this->DB->query("UPDATE `account` SET `sha_pass_hash`='$pass_hash', `v`= 0, `s`= 0 WHERE `id`='$id' LIMIT 1");
        return TRUE;
    }
	
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
					$this->DB->query("UPDATE account_extend SET secret_q1='$sq1', secret_q2='$sq2', secret_a1='$sa1', secret_a2='$sa2' WHERE account_id='$id'");
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
			$this->DB->query("UPDATE account_extend SET avatar=NULL WHERE account_id=".$id." LIMIT 1");
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
		$count = $this->DB->selectRow("SELECT * FROM account_keys WHERE id='$id'");
		if($count == FALSE) 
		{
			return false;
		}
		else
		{
			$account_key = $this->DB->selectRow("SELECT * FROM account_keys WHERE id='$id'");
			if($key == $account_key['key']) 
			{
				return true;
			}
			else 
			{
				output_message('error', 'Account Keys Error!');
				return false;
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

		$this->DB->query("DELETE FROM account_keys WHERE assign_time < ".$expire_time."");
	}

	function addOrUpdateAccountKeys($id, $key) 
	{
		global $DB;

		$current_time = time();
		$go = $DB->selectRow("SELECT * FROM account_keys WHERE id = '".$id."'");
		if($go == FALSE) //need to INSERT
		{
			$this->DB->query("INSERT INTO account_keys (`id`, `key`, `assign_time`) VALUES ('$id', '$key', '$current_time')");
		}
		else //need to UPDATE
		{              
			$this->DB->query("UPDATE `account_keys` SET `key`='$key', `assign_time`='$current_time' WHERE `id`='$id'");
		}
	}

	function removeAccountKeyForUser($id) 
	{
		global $DB;

		$count = $this->DB->selectRow("SELECT * FROM account_keys where id ='$id'");
		if($count == FALSE) 
		{
			//do nothing
		}
		else 
		{
			$this->DB->query("DELETE FROM account_keys WHERE id ='$id'");
		}
	}
}
?>