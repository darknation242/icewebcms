<?php
include('../core/core.php');

// Init core
$Core = new Core();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>MangosWeb Enhanced v3 Installer</title>
	<link rel="stylesheet" href="css/main.css" type="text/css"/>
</head>
<body>
	<div id="header">					
		<h1 id="title"><center><img src="images/MangosWeb.png" /></center></h1>
	</div>
	<div class="page">
		<div class="content">				
			<div class="content-header">
			<?php
				if(isset($_GET['step']))
				{
					$step = $_GET['step'];
				}
				else
				{
					$step = 1;
				}
				echo "<h4><center>Step ".$step."</center></h4>";
				echo "</div> <!-- .content-header -->";
				if($step == 1)
				{
			?>		
					<!-- STEP 1 -->
					<div class="main-content">		
						<p>
							Welcome to the MangosWeb v3 Installer!. Before we start the installation proccess, we need to make sure your
							web server is compatible with MangosWeb. Please click continue at the bottom to begin.
						</p>						
						<div class="clear"></div>
					</div> <!-- .main-content -->
			<?php
				} ?>
		</div> <!-- .content -->
	</div>
</body>
</html>