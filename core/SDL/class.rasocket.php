<?php
// RA class for MangosWebSDL. Originally from TrinMangSDK
// Re-written and added SOAP functions by Steven Wilson (Wilson212)

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
        $this->handle = FALSE;
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
    public function auth($user, $pass)
    {
        if(!$this->handle)
		{
			return 0;
		}

        $user = strtoupper($user);
        fwrite($this->handle, "USER ".$user."\n");
        usleep(50);
        fwrite($this->handle, "PASS ".$pass."\n");
        usleep(300);

        if(substr(trim(fgets($this->handle)), 0, 1) != "+")
		{
			return 2;
		}
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
		{
			fclose($this->handle);
		}
        $this->handle = @fsockopen($host, $port, $errorno, $errorstr, 5);
        if(!$this->handle)
		{
			return FALSE;
		}
        else 
		{
            $this->consoleReturn = trim(fgets($this->handle));
            return TRUE;
        }
    }
	
	 /**
      Inputs a command into an active connection to MaNGOS/Trinity
      Adds the output of the console into ralog.
      Returns 0 if it's not connected
      Returns 1 if it the command was sent successfully
      Returns 2 if it's not authenticated
      @param $command the command to enter on console
    */
    public function executeCommand($type, $shost, $remote, $command)
    {
		global $Config;
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
			$this->consoleReturn = trim(fgets($this->handle));
			return 1;
		}
		else
		{
			$client = $this->soapHandle($shost, $remote);
			try
			{
				$result = $client->executeCommand(new SoapParam($command, "command"));
				$this->consoleReturn = $result;
			}
			catch(Exception $e)
			{
				$this->consoleReturn = $e->getMessage();
			}
			return 1;
		}
    }
	
	private function soapHandle($shost, $remote)
	{
		global $Config, $DB;
		if($Config->get('emulator') == 'mangos')
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
		elseif($Config->get('emulator') == 'trinity')
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
	
	/*
		Main sending function for the site
		This function gets the RA info for the realm.
		and executes the command.
		send( Command, realm ID )
		returns 1 if unable to connect
		return 2 if unauthorized
		returns console return upon success
	*/
	function send($command, $realm)
	{
		global $user, $Config, $DB;
		$get_remote = $DB->selectRow("SELECT * FROM `realmlist` WHERE id='".$realm."'");
		$remote = explode(';', $get_remote['ra_info']);
		$shost = $get_remote['address'];
		if($remote[0] == 0 || $remote[0] == 1)
		{
			$result = $this->executeCommand($remote[0], $shost, $remote, $command);
			if($result != 1)
			{
				if($result == 0)
				{
					return 1;
				}
				elseif($result == 2)
				{
					return 2;
				}
			}
			else
			{
				return $this->consoleReturn;
			}
		}
	}
}
?>