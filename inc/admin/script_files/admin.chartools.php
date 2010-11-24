<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

include('core/SDL/class.character.php');
include('core/SDL/class.zone.php');
$Character = new Character;
$Zone = new Zone;

//====== Pagination Code ======/
$limit = 50; // Sets how many results shown per page	
if(!isset($_GET['page']) || (!is_numeric($_GET['page'])))
{
    $page = 1;
} 
else 
{
	$page = $_GET['page'];
}
$limitvalue = $page * $limit - ($limit);	// Ex: (2 * 25) - 25 = 25 <- data starts at 25

//===== Filter ==========// 
if($_GET['sort'] && preg_match("/[a-z]/", $_GET['sort']))
{
	$filter = "WHERE `name` LIKE '" . $_GET['sort'] . "%'";
}
elseif($_GET['sort'] == 1)
{
	$filter = "WHERE `name` REGEXP '^[^A-Za-z]'";
}
else
{
	$filter = '';
}

// Get all characters
$characters = $CDB->select("SELECT * FROM `characters` $filter ORDER BY `name` ASC LIMIT $limitvalue, $limit;");
$totalrows = $CDB->count("SELECT COUNT(*) FROM `characters` $filter");

//===== Start of functions =====/

function deleteCharacter()
{
}

function updateChar()
{
	global $Character;
	if($Character->isOnline($_GET['id']) == FALSE)
	{
		if($Character->setLevel($_GET['id'], $_POST['level'])  == TRUE)
		{
			if($Character->setXp($_GET['id'], $_POST['xp'])  == TRUE)
			{
				if($Character->setMoney($_GET['id'], $_POST['money'])  == TRUE)
				{
					output_message('success', 'Character updated successfully. Redirecting...
					<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
				}
				else
				{
					output_message('error', 'Cannot adjust the characters Money. Redirecting...
					<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
				}
			}
			else
			{
				output_message('error', 'Cannot set the characters Xp! Redirecting...
				<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
			}
		}
		else
		{
			output_message('error', 'Cannot set the characters Name! Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
		}
	}
	else
	{
		output_message('warning', 'The character is currently online. Cannot make adjustments! Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}				
}

function flagRename()
{
	global $Character;
	if($Character->setRename($_GET['id']) == TRUE)
	{
		output_message('success', 'Character Rename flag set. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
	else
	{
		output_message('warning', 'Character already has the Rename flag set. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
	
}

function flagCustomize()
{
	global $Character;
	if($Character->setCustomize($_GET['id']) == TRUE)
	{
		output_message('success', 'Character Re-Customize flag set. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
	else
	{
		output_message('warning', 'Character already has the Re-Customize flag set. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
}

function flagTalentReset()
{
	global $Character;
	if($Character->setResetTalents($_GET['id']) == TRUE)
	{
		output_message('success', 'Character Talent Reset flag set. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
	else
	{
		output_message('warning', 'Character already has the Talent Reset flag set. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
}

function resetFlags()
{
	global $Character;
	if($Character->resetAtLogin($_GET['id']) == TRUE)
	{
		output_message('success', 'Character Flags Reset. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
	else
	{
		output_message('error', 'Unable to reset flags. Redirecting...
			<meta http-equiv=refresh content="3;url=?p=admin&sub=chartools&id='.$_GET['id'].'">');
	}
}
?>