<?php
/****************************************************************************/
/*    < MangosWeb is a Web-Fonted for Mangos (mangosproject.org) >          */
/*    		  Copyright (C) <2007>  <Sasha,TGM,Peec,Nafe>                   */
/*																			*/
/*  	< MangosWeb Enhanced is a Web-Fonted for mangos/trinity >  			*/
/*              Copyright (C) <2009 - 2010>  <Wilson212>                    */
/*																			*/
/*                                                                          */
/*    This program is free software: you can redistribute it and/or modify  */
/*    it under the terms of the GNU General Public License as published by  */
/*    the Free Software Foundation, either version 2 of the License, or     */
/*    (at your option) any later version.                                   */
/*                                                                          */
/*    This program is distributed in the hope that it will be useful,       */
/*    but WITHOUT ANY WARRANTY; without even the implied warranty of        */
/*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         */
/*    GNU General Public License for more details.                          */
/*                                                                          */
/*    You should have received a copy of the GNU General Public License     */
/*    along with this program.  If not, see <http://www.gnu.org/licenses/>. */
/*                                                                          */
/****************************************************************************/

// Set error reporting to everything for now
ini_set('error_reporting', E_ERROR ^ E_NOTICE ^ E_WARNING);
error_reporting(E_ERROR ^ E_NOTICE ^ E_WARNING);
ini_set('log_errors', TRUE);
ini_set('html_errors', FALSE);
ini_set('error_log', 'core/logs/error_log.txt');
ini_set('display_errors', TRUE);

// Define INCLUDED so that we can check other pages if they are included by this file
define('INCLUDED', TRUE);

// Start a variable that shows how fast page loaded.
define('TIME_START', microtime(1));
$_SERVER['REQUEST_TIME'] = time();

// Load the Core and config class
include('core/class.config.php');
$Config = new Config;
include('core/core.php');
$Core = new Core;

//Site notice cookie
if($Config->get('site_notice_enable') == 1 && !isset($_COOKIE['agreement_accepted']))
{
	include('modules/notice/notice.php');
	exit();
}

// Check if the site is installed by checking config defaults
if($Config->getDbInfo('db_username') == 'default')
{
	header('location: install/');
}

// Site functions & classes ...
include('core/common.php'); 					// Holds most of the sites functions
include('core/class.template.php');			// Sets up the template system
include('core/SDL/class.account.php'); 		// contains account related scripts and functions

// Set site globals such as realm, language etc etc
$Core->setGlobals();

// Load language file
include( 'lang/' . $GLOBALS["user_cur_lang"] . '.php' );

// === Setup the connections to other DB's - Holds DB connector classes === //
require ('core/class.database.php');
$DB = new Database(
	$Config->getDbInfo('db_host'), 
	$Config->getDbInfo('db_port'), 
	$Config->getDbInfo('db_username'), 
	$Config->getDbInfo('db_password'), 
	$Config->getDbInfo('db_name')
	);
	
// Make an array from `dbinfo` column for the selected realm..
$DB_info = $DB->selectRow("SELECT * FROM realmlist WHERE id='".$GLOBALS['cur_selected_realm']."'");
$dbinfo = explode(';', $DB_info['dbinfo']);

// DBinfo column: char_host;char_port;char_username;char_password;charDBname;world_host;world_port;world_username;world_pass;worldDBname
$Realm_DB_Info = array(
	'char_db_host' => $dbinfo['0'], // char host
	'char_db_port' => $dbinfo['1'], // char port
	'char_db_username' => $dbinfo['2'], // char user
	'char_db_password' => $dbinfo['3'], // char password
	'char_db_name' => $dbinfo['4'], //char db name
	'w_db_host' => $dbinfo['5'], // world host
	'w_db_port' => $dbinfo['6'], // world port
	'w_db_username' => $dbinfo['7'], // world user
	'w_db_password' => $dbinfo['8'], // world password
	'w_db_name' => $dbinfo['9'], // world db name
	);

// Free up memory.
unset($dbinfo, $DB_info); 

// Establish the Character DB connection
$CDB = new Database(
	$Realm_DB_Info['char_db_host'],
	$Realm_DB_Info['char_db_port'],
	$Realm_DB_Info['char_db_username'],
	$Realm_DB_Info['char_db_password'],
	$Realm_DB_Info['char_db_name']
	);

// Establish the World DB connection	
$WDB = new Database(
	$Realm_DB_Info['w_db_host'],
	$Realm_DB_Info['w_db_port'],
	$Realm_DB_Info['w_db_username'],
	$Realm_DB_Info['w_db_password'],
	$Realm_DB_Info['w_db_name']
	);
	
// Free up memory
unset($Realm_DB_Info);

// === Load auth system === //
$Account = new Account();
$user = $Account->user;
$user['cur_selected_realm'] = $GLOBALS['cur_selected_realm'];


// === Sets up the template system. === //
$Template = new Template;

// Lets get the template information
$Template = $Template->loadTemplateXML();
if($Template == FALSE)
{
	echo "Fetal Error: Template XML Not Found!";
	die();
}

// === Start of page loading === //

	// Start off by checking if the requested page is a module or not
	if(!isset($_GET['p']) && isset($_GET['module']))
	{
		// Scan the directory for installed modules to prevent XSS
		$Modulelist = scandir("modules/");
		if(in_array($_GET['module'], $Modulelist))
		{
			include("modules/".$_GET['module']."/index.php");
		}
		else
		{
			echo "No Module of that name found!";
		}
	}

	// If page is not a module, then lets load our template pages.
	else
	{
		// if empty page, then load default component(frontpage)
		$ext = (isset($_GET['p']) ? $_GET['p'] : (string)$Config->get('default_component'));
		
		// If url looks like so: ?p=account/login (This is a avalid url)
		if(strpos($ext, '/') !== FALSE) 
		{
			list($ext, $sub) = explode('/', $ext);
		}
		else
		{
			// If empty sub page, load page.index.php
			$sub = (isset($_GET['sub']) ? $_GET['sub'] : 'index');
		}
		$script_file = 'inc/' . $ext . '/' . $ext . '.' . $sub . '.php';
		$template_file = $Template['script_path'] . '/' . $ext . '/' . $ext . '.' . $sub . '.php';

		// === Start Loading of the Page files === //

		// If the requested page is the admin Panel, then we load the admin template
		if($ext == 'admin') 
		{
			if(file_exists('inc/admin/body_functions.php')) 
			{
				include('inc/admin/body_functions.php');
			}
			@include('inc/admin/script_files/admin.' . $sub .'.php');
			ob_start();
				include('inc/admin/body_header.php');
			ob_end_flush();
			ob_start();
				include('inc/admin/template_files/admin.' . $sub .'.php');
			ob_end_flush();
			
			// Set our time end, so we can see how fast the page loaded.
			define('TIME_END', microtime(1));
			define('PAGE_LOAD_TIME', TIME_END - TIME_START);
			include('inc/admin/body_footer.php');
		}

		// Else, if requested page isnt the admin panel, then load the template
		else
		{	
			// Start Loading Of Script Files
			@include($script_file);

			// If a body functions file exists, include it.
			if(file_exists($Template['script_path'] . '/body_functions.php')) 
			{
				include ($Template['script_path'] . '/body_functions.php');
			}
			ob_start();
				include ($Template['script_path'] . '/body_header.php');
			ob_end_flush();
			
			// === Start the loading of the template cache === //
			
			// Lets check to see if the page is flagged to cache or not. defined in scriptfile of each page
			if(defined('CACHE_FILE'))
			{
				$CacheFile = CACHE_FILE;
			}
			else # Not defined
			{
				$CacheFile = FALSE;
			}
			
			// Check if admin has enabled caching, and CACHE_FILE is enabled
			if($Config->get('enable_cache') && $CacheFile == TRUE)
			{
				// If file is cached
				if($Core->isCached($Template['number']."_".$ext.".".$sub))
				{
					$Contents = $Core->getCache($Template['number']."_".$ext.".".$sub);
					echo $Contents;
				}
				// If not cached, then get contents of the page and cache them.
				else
				{
					ob_start();
						include($template_file);
					$Contents = ob_get_flush();
					$Core->writeCache($Template['number']."_".$ext.".".$sub, $Contents);
				}
				unset($Contents);
			}
			else
			{
				ob_start();
					include($template_file);
				ob_end_flush();
			}
			
			// === End cache system, Load the footer === //

			// Set our time end, so we can see how fast the page loaded.
			define('TIME_END', microtime(1));
			define('PAGE_LOAD_TIME', TIME_END - TIME_START);
			include ($Template['script_path'] . '/body_footer.php');
		}
	}
//=== END Of Page Loading ===//

// Close all DB Connections
$DB->__destruct();
$CDB->__destruct();
$WDB->__destruct();
?>