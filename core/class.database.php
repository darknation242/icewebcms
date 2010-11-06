<?php
// Database class written by Steven Wilson for IceWeb
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
	
	function runSQL($file)
	{
		$file_content = file($url);
		foreach($file_content as $sql_line)
		{
			if(trim($sql_line) != "" && strpos($sql_line, "--") && strpos ($aquery, "#") === false)
			{
				foreach ($sql_line as $key => $aquery) 
				{
					$aquery = rtrim($aquery);
					$compare = rtrim($aquery, ";");
					if ($compare != $aquery) 
					{
						$sql_line[$key] = $compare . "|br3ak|";
					}
				}
			}
		}
		unset($key, $aquery);

		$sql_line = implode($sql_line);
		$queries = explode("|br3ak|", $sql_line);
		
		foreach($queries as $sql)
		{
			mysql_query($sql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		}
		return TRUE;
	}
}
?>