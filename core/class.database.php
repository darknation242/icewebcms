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
            mysql_select_db($db_name,$this->mysql) or die(mysql_error());
    }

    public function __destruct()
    {
        @mysql_close($this->mysql) or die(mysql_error());
    }

    public function query($query)
    {
        $sql = mysql_query($query,$this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		$this->_statistics['count']++;
		//echo $query."<br />";
		return $sql;
    }

    public function select($query)
    {
        $sql = mysql_query($query,$this->mysql) or die("Couldnt Run Query: ".$query."<br />Error: ".mysql_error()."");
		$this->_statistics['count']++;
		// echo $query."<br />";
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
		// echo $query."<br />";
		if(mysql_num_rows($sql) == 0)
		{
			// echo "- Returning false<br />";
			return FALSE;
		}
		else
		{
			// echo "- Returning True<br />";
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
			// echo "- Returning false<br />";
			return FALSE;
		}
		else
		{
			// echo "- Returning True<br />";
			$row = mysql_fetch_array($sql);
			return $row['0'];
		}
    }
}
?>