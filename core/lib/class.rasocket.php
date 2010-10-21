<?php
class RA
{
	private $handle;
    private $errorstr, $errorno;
    private $auth;
    public $com;

    /**
      Class constructer.
    */
    public function __construct()
    {
        $this->handle = false;
    }

    /**
      Class destructor. Closes the connection.
      Called with unset($parent).
    */
    public function __destruct()
    {
        if($this->handle)
        {
            fclose($this->handle);
            $this->auth = FALSE;
        }
    }

    /**
      Once connected to the server, this allows you to login
      Returns 0 if it isn't connected yet.
      Returns 1 if it was successful.
      Returns 2 if it was unable to authenticate.
    */
    public function auth($user,$pass)
    {
        if(!$this->handle) return 0;

        $user = strtoupper($user);
        fwrite($this->handle, "USER ".$user."\n");
        usleep(50);
        fwrite($this->handle, "PASS ".$pass."\n");
        usleep(300);

        if (substr(trim(fgets($this->handle)),0,1) != "+")
          return 2;
        else
        {
            $this->auth = TRUE;
            return 1;
        }
    }

    /**
      Attempts to connect to console. Returns false if it was unable to connect.
      Returns true if it is successfully connected.
      @param $host the IP or the DNS name of the server
      @param $port the port on which try to connect (default 3443)
    */
    public function connect($host, $port = 3443)
    {
        if($this->handle)
          fclose($this->handle);

        $this->handle = @fsockopen($host, $port, $errorno, $errorstr, 5);

        if(!$this->handle)
          return false;
        else {
            $this->motto = trim(fgets($this->handle));
            return true;
        }
    }
	
	 /**
      Inputs a command into an active connection to MaNGOS/Trinity
      Adds the output of the console into ralog.
      Returns 0 if it's not connected
      Returns 1 if it was successful
      Returns 2 if it's not authenticated
      @param $command the command to enter on console
    */
    public function sendcommand($type, $shost, $remote, $command)
    {
		global $cfg;
		if($type == 0)
		{
			$this->connect($shost, $remote[1]);
			if(!$this->handle)
			{
				return 0;
			}
			$this->auth($remote[2], $remote[3]);
			if(!$this->auth)
			{
				return 2;
			}
			fwrite($this->handle, $command."\n");
			usleep(200);
			if($cfg->get('emulator') == "trinity")
			{
				fgets($this->handle,9);
			}
			else
			{
				fgets($this->handle,8);
			}
			$this->motto = trim(fgets($this->handle));
			return 1;
		}
		else
		{
			$client = $this->soap_handle($shost, $remote);
			try
			{
				$result = $client->executeCommand(new SoapParam($command, "command"));
				$ret = 1;
			}
			catch(Exception $e)
			{
				$ret = $e->getMessage();
			}
			return $ret;
		}
    }
	
	private function soap_handle($shost, $remote)
	{
		global $cfg, $DB;
		if($cfg->get('emulator') == 'mangos')
		{
			$client = new SoapClient(NULL,
			array(
			"location" => "http://".$shost.":".$remote[1]."/",
			"uri" => "urn:MaNGOS",
			"style" => SOAP_RPC,
			"login" => $remote[2],
			"password" => $remote[3]
			));
		}
		elseif($cfg->get('emulator') == 'trinity')
		{
			$client = new SoapClient(NULL,
			array(
			"location" => "http://".$shost.":".$remote[1]."/",
			"uri" => "urn:TC",
			"style" => SOAP_RPC,
			"login" => $remote[2],
			"password" => $remote[3]
			));
		}
		return $client;
	}
	
	function command($command)
	{
		global $user, $cfg, $DB;
		$get_remote = $DB->selectRow("SELECT * FROM realmlist WHERE id='$user[cur_selected_realm]'");
		$remote = explode(';', $get_remote['ra_info']);
		$shost = $get_remote['address'];
		if($remote[0] == 0)
		{
			// Telnet
			$result = $this->sendcommand(0, $shost, $remote, $command);
			if($result == 1)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			// Soap
			$result = $this->sendcommand(1, $shost, $remote, $command);
			if($result == 1)
			{
				return TRUE;
			}
			else
			{
				return $result;
			}
		}
	}
}
?>