<?php
//========================//
if(INCLUDED !== TRUE) 
{
	echo "Not Included!"; 
	exit;
}
$pathway_info[] = array('title' => 'Shop', 'link' => '');
// ==================== //

define("CACHE_FILE", FALSE);

// Lets check to see the user is logged in
if($user['id'] <= 0)
{
    redirect('?p=account&sub=login',1);
}

// Include the paypal class
include('core/SDL/class.rasocket.php');
$RA = new RA;
?>