<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

function buildPage() 
{
	global $cfg, $currtmp;
	
	// Here we build the script file
	$handle = "inc/".$_POST['page_ext']."/".$_POST['page_ext'].".".$_POST['page_sub'].".php";
	if(file_exists($handle)) 
	{
		output_message('alert', 'The file you are attemting to create already exists!');
	}
	else
	{
		$key = '$';
		if($_POST['page_level'] == 1) {	
			$inserty = '';
		}elseif($_POST['page_level'] == 2) {
			$inserty = "if(".$key."user['account_level'] < 2) {\n echo \"<center><br />You do not have permision to view this page! 
			<a href='javascript: history.go(-1)'>Click Here</a> to go back</center>\";\n}\n";
		}elseif($_POST['page_level'] == 3) {
			$inserty = "if(".$key."user['account_level'] < 3) {\n echo \"<center><br />You do not have permision to view this page! 
			<a href='javascript: history.go(-1)'>Click Here</a> to go back</center>\";\n}\n";
		}elseif($_POST['page_level'] == 4) {
			$inserty = "if(".$key."user['account_level'] < 4) {\n echo \"<center><br />You do not have permision to view this page! 
			<a href='javascript: history.go(-1)'>Click Here</a> to go back</center>\";\n}\n";
		}
		$build_ext = '';
		$build_ext .= "<?php\n";
		$build_ext .= "//========================//\n";
		$build_ext .= "if(INCLUDED!==true) {\n";
		$build_ext .= "	  echo \"Not Included!\"; exit;\n";
		$build_ext .= "}\n";
		$build_ext .= "//=======================//\n";
		$build_ext .= "";
		$build_ext .= "".$inserty."";
		$build_ext .= "?>";
		@mkdir("inc/".$_POST['page_ext']."", 0700);
		$openscrpt = fopen($handle, 'w+');
        fwrite($openscrpt, $build_ext);
        fclose($openscrpt);
		
		// Build the template file
		$build_sub = '';
		$build_sub .= "<center>Congradulations on creating your new page. There is no content in it yet, so be sure to use the page editor or file manager to put your content</center>!";
		$temp_templ = explode(",", $cfg->get('templates'));
		foreach($temp_templ as $sub_tmpl) 
		{
			$load_xml = simplexml_load_file("templates/".$sub_tmpl."/template.xml");
			$sub_handle = "templates/". $load_xml->masterTemplate ."/".$_POST['page_ext']."/".$_POST['page_ext'].".".$_POST['page_sub'].".php";
			@mkdir("templates/". $load_xml->masterTemplate ."/".$_POST['page_ext']."", 0700);
			$createsub = fopen($sub_handle, 'w+');
			fwrite($createsub, $build_sub);
			fclose($createsub);
			unset($load_xml);
		}
		$output_mess = "Page Created Successfully!";
		output_message('success', $output_mess);
	}
}
?>