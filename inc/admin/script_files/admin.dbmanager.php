<?php
//========================//
if(INCLUDED!==true) {
	echo "Not Included!"; exit;
}
//=======================//

function run_sql($url)
{
	global $DB;
	$file_content = file($url);
	foreach($file_content as $sql_line)
	{
		if(trim($sql_line) != "" && strpos($sql_line, "--") === false)
		{
			//echo $sql_line . '<br>';
			$DB->query($sql_line);
			return TRUE;
		}
	}
}

function update_database()
{
	global $DB;
	$update = $_POST['sql_file'];
	$url = "install/sql/updates/update_". $update .".sql";
}
?>