<?php
// Database class written by Steven Wilson
class Database
{

	// Queries statistics.
    var $_statistics = array(
        'time'  => 0,
        'count' => 0,
    );
    private $mysql;

    public function __construct($db_host, $db_port, $db_user, $db_pass, $db_name)
    {
        $this->mysql = @mysql_connect($db_host.":".$db_port, $db_user, $db_pass, true) or die("Cant connect to ".$db_name." Database!");
        mysql_select_db($db_name,$this->mysql) or die("Cant select database \"".$db_name." Database!\"");
		return TRUE;
    }

    public function __destruct()
    {
        @mysql_close($this->mysql) or die(mysql_error());
    }

    public function query($query)
    {
        $sql = mysql_query($query,$this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		$this->_statistics['count']++;
		return TRUE;
    }

    public function select($query)
    {
        $sql = mysql_query($query,$this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		$this->_statistics['count']++;
		$i = 1;
		if(mysql_num_rows($sql) == 0)
		{
			$result = FALSE;
		}
		else
		{
			while($row = mysql_fetch_assoc($sql))
			{
				foreach($row as $colname => $value)
				{
					$result[$i][$colname] = $value;
				}
				$i++;
			}
		}
		return $result;
    }
	
	public function selectRow($query)
    {
        $sql = mysql_query($query,$this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		$this->_statistics['count']++;
		if(mysql_num_rows($sql) == 0)
		{
			return FALSE;
		}
		else
		{
			$row = mysql_fetch_array($sql);
			return $row;
		}
    }
	
	public function selectCell($query)
    {
        $sql = mysql_query($query,$this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		$this->_statistics['count']++;
		if(mysql_num_rows($sql) == 0)
		{
			return FALSE;
		}
		else
		{
			$row = mysql_fetch_array($sql);
			return $row['0'];
		}
    }
	
	public function count($query)
    {
        $sql = mysql_query($query,$this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		$this->_statistics['count']++;
		return mysql_result($sql, 0);
    }
	
	// Run a sql file function. Not written by me.
	// $file is the path location to the sql file
	function runSQL($file)
	{
		$handle = @fopen($file, "r");
		if ($handle) 
		{
			while(!feof($handle)) 
			{
				$sql_line[] = fgets($handle);
			}
			fclose($handle);
		}
		else 
		{
			return FALSE;
		}
		foreach ($sql_line as $key => $query) 
		{
			if (trim($query) == "" || strpos ($query, "--") === 0 || strpos ($query, "#") === 0) 
			{
				unset($sql_line[$key]);
			}
		}
		unset($key, $query);

		foreach ($sql_line as $key => $query) 
		{
			$query = rtrim($query);
			$compare = rtrim($query, ";");
			if ($compare != $query) 
			{
				$sql_line[$key] = $compare . "|br3ak|";
			}
		}
		unset($key, $query);

		$sql_lines = implode($sql_line);
		$sql_line = explode("|br3ak|", $sql_lines);
		
		foreach($sql_line as $query)
		{
			if($query)
			{
				mysql_query($query, $this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
			}
		}
		return TRUE;
	}
}
?>