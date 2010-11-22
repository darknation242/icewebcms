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
if($_GET['char'] && preg_match("/[a-z]/", $_GET['char']))
{
	$filter = "WHERE `name` LIKE '" . $_GET['char'] . "%'";
}
elseif($_GET['char'] == 1)
{
	$filter = "WHERE `name` REGEXP '^[^A-Za-z]'";
}
else
{
	$filter = '';
}

// Get all characters
$characters = $CDB->select("SELECT * FROM `characters` $filter ORDER BY `name` ASC LIMIT $limitvalue, $limit;");
$totalrows = $CDB->count("SELECT COUNT(*) FROM `characters`");

//===== Start of functions =====/

function deleteCharacter()
{
}
?>