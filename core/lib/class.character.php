<?php
// Character class for MangosWebSDL written by Steven Wilson, aka Wilson212
class Character
{
	public function adjustLevel($id, $mod)
	{
		global $CDB;
		$lvl = $CDB->selectRow("SELECT `level` FROM characters WHERE `guid`='$id'");
		if($lvl == FALSE)
		{
			return FALSE;
		}
		else
		{
			$newlvl = $lvl['level'] + $mod;
			$CDB->query("UPDATE `characters` SET `level`='$newlvl' WHERE `guid`='$id'");
			return TRUE;
		}
	}
	
	public function adjustMoney($id, $mod)
	{
		global $CDB;
		$m = $CDB->selectRow("SELECT `money` FROM characters WHERE `guid`='$id'");
		if($lvl == FALSE)
		{
			return FALSE;
		}
		else
		{
			$newmoney = $m['money'] + $mod;
			$CDB->query("UPDATE `characters` SET `money`='$newmoney' WHERE `guid`='$id'");
			return TRUE;
		}
	}
	
	public function getAccountId($guid)
    {
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $row = $CDB->selectRow("SELECT `account` FROM `characters` WHERE `guid` = '$guid' LIMIT 1");
		if($row == FALSE)
		{
			return FALSE;
		}
		else
		{
			return $row['account'];
		}
    }
	
	public function getLevel($guid) 
	{
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $row = $CDB->selectRow("SELECT `level` FROM `characters` WHERE `guid` = '$guid' LIMIT 1");
		if($row == FALSE)
		{
			return FALSE;
		}
		else
		{
			return $row['level'];
		}
    }
	
	public function getClass($guid) 
	{
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $row = $CDB->selectRow("SELECT `class` FROM `characters` WHERE `guid` = '$guid' LIMIT 1");
		if($row == FALSE)
		{
			return FALSE;
		}
		else
		{
			return $row['class'];
		}
    }
	
	public function getRace($guid) 
	{
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $row = $CDB->selectRow("SELECT `race` FROM `characters` WHERE `guid` = '$guid' LIMIT 1");
		if($row == FALSE)
		{
			return FALSE;
		}
		else
		{
			return $row['race'];
		}
    }
	
	public function getGender($guid) 
	{
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $row = $CDB->selectRow("SELECT `gender` FROM `characters` WHERE `guid` = '$guid' LIMIT 1");
		if($row == FALSE)
		{
			return FALSE;
		}
		else
		{
			return $row['gender'];
		}
    }
	
	// Returns 1 = Ally, 0 = horde
	public function getFaction($guid)
    {
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $ally = array("1", "3", "4", "7", "11");
        $row = $CDB->selectRow("SELECT `faction` FROM `characters` WHERE `guid` = '$guid' LIMIT 1");
		if($row == FALSE)
		{
			return FALSE;
		}
		else
		{
			if(in_array($row['faction'], $ally))
			{
				return 1;
			} 
			else 
			{
				return 0;
			}
		}
    }
	
	public function getMoney($guid) 
	{
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $row = $CDB->selectRow("SELECT `money` FROM `characters` WHERE `guid` = '$guid' LIMIT 1");
		if($row == FALSE)
		{
			return FALSE;
		}
		else
		{
			return $row['money'];
		}
    }
	
	 public function isOnline($guid)
    {
		global $CDB;
		$guid = mysql_real_escape_string($guid);
        $row = $CDB->select("SELECT COUNT(*) AS `count` FROM `characters` WHERE `guid` = '$guid' AND `online` = '1'");
        if($row['count'] > 0) 
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
    }
	
	// ==== SET FUNCTIONS ==== //
	
	public function setName($guid, $newname)
	{
		global $CDB;
		$guid = mysql_real_escape_string($guid);
		$newname = mysql_real_escape_string(strtolower($newname));
        $newname = ucfirst($newname);
		$send = $CDB->query("UPDATE `characters` SET `name`='$newname' WHERE `guid`='$guid'");
		return TRUE;
	}
	
	public function setAccountId($guid, $accountId)
    {
		global $CDB;
        $guid = mysql_real_escape_string($guid);
        $acct = mysql_real_escape_string($accountId);
        $CDB->query("UPDATE `characters` SET `account` = '$acct' WHERE `guid` = '$guid' LIMIT 1");
        return true;
    }
	
	public function setMoney($id, $newmoney)
	{
		global $CDB;
		$m = $CDB->selectRow("SELECT `money` FROM characters WHERE `guid`='$id'");
		if($lvl == FALSE)
		{
			return FALSE;
		}
		else
		{
			$CDB->query("UPDATE `characters` SET `money`='$newmoney' WHERE `guid`='$id'");
			return TRUE;
		}
	}
	
	public function setLevel($id, $newlvl)
	{
		global $CDB;
		$lvl = $CDB->selectRow("SELECT `level` FROM characters WHERE `guid`='$id'");
		if($lvl == FALSE)
		{
			return FALSE;
		}
		else
		{
			$CDB->query("UPDATE `characters` SET `level`='$newlvl' WHERE `guid`='$id'");
			return TRUE;
		}
	}
}
?>