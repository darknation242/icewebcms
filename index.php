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
ini_set('log_errors',TRUE);
ini_set('html_errors',FALSE);
ini_set('error_log','core/logs/error_log.txt');
ini_set('display_errors',TRUE);

// Define INCLUDED so that we can check other pages if they are included by this file
define( 'INCLUDED', true ) ;

// Start a variable that shows how fast page loaded.
$time_start = microtime( 1 ) ;
$_SERVER['REQUEST_TIME'] = time() ;

// Load the Core and config class
include('core/core.php');
include('core/class.config.php');
$Core = new Core;
$cfg = new Config;

//Site notice cookie
if($cfg->get('site_notice_enable') == 1 && ! isset($_COOKIE['agreement_accepted']))
{
	include( 'modules/notice/notice.php' ) ;
	exit() ;
}

// Check if the site is installed by checking config defaults
if($cfg->getDbInfo('db_username') == 'default')
{
	header('location: install/');
}

// Fill in the config with the proper directory info if the directory info is wrong
$getsitehref = ''.dirname( $_SERVER["SCRIPT_NAME"] ).'/';
if($getsitehref == "//") 
{ 
	$tmp_sitehref = "/"; 
}
else
{ 
	$tmp_sitehref = $getsitehref; 
}
$getbasehref = 'http://'.$_SERVER["HTTP_HOST"].''.$tmp_sitehref.'';
if($cfg->get('site_base_href') !== $getbasehref)
{
	$cfg->set('site_base_href',''.$getbasehref.'');
	$cfg->set('site_href',''.$tmp_sitehref.'');
	$cfg->Save();
}

// Site functions & classes ...
include ( 'core/common.php' ); 					// Holds most of the sites functions
include ( 'core/class.template.php' );			// Sets up the template system
include ( 'core/SDL/class.account.php' ); 		// contains account related scripts and functions

// Super-Global variables.
$GLOBALS['users_online'] = array();
$GLOBALS['guests_online'] = 0;
$GLOBALS['user_cur_lang'] = '';
$GLOBALS['messages'] = '';		// For server messages
$GLOBALS['redirect'] = '';		// For the redirect function, uses <meta> tags
$GLOBALS['cur_selected_realm'] = '';


// === Load the languages and set users language === //
$languages = explode(",", $cfg->get('available_lang'));
if(isset($_COOKIE['Language'])) 
{
	$GLOBALS['user_cur_lang'] = (string)$_COOKIE['Language'];
}
else
{
	$GLOBALS['user_cur_lang'] = (string)$cfg->get('default_lang');
	setcookie("Language", $GLOBALS['user_cur_lang'], time() + (3600 * 24 * 365));
}

include( 'lang/' . $GLOBALS["user_cur_lang"] . '.php' );


// === Finds out what realm we are viewing. Sets cookie if need be. === //
if(isset($_COOKIE['cur_selected_realm'])) 
{
	$GLOBALS['cur_selected_realm'] = (int)$_COOKIE['cur_selected_realm'];
}
else
{
	$GLOBALS['cur_selected_realm'] = (int)$cfg->get('default_realm_id');
	setcookie("cur_selected_realm", $GLOBALS['cur_selected_realm'], time() + (3600 * 24 * 365));
}


// === Setup the connections to other DB's - Holds DB connector classes === //
require ( 'core/class.database.php' );
$DB = new Database(
	$cfg->getDbInfo('db_host'), 
	$cfg->getDbInfo('db_port'), 
	$cfg->getDbInfo('db_username'), 
	$cfg->getDbInfo('db_password'), 
	$cfg->getDbInfo('db_name')
	);
	
// Make an array from `dbinfo` column for the selected realm..
$mangos_info = $DB->selectRow("SELECT * FROM realmlist WHERE id='".$GLOBALS['cur_selected_realm']."'");
$dbinfo_mangos = explode( ';', $mangos_info['dbinfo'] ) ;

//DBinfo column:  username;password;port;host;WorldDBname;CharDBname
$mangos = array(
	'db_host' => $dbinfo_mangos['3'],
	'db_port' => $dbinfo_mangos['2'], //port
	'db_username' => $dbinfo_mangos['0'], //world user
	'db_password' => $dbinfo_mangos['1'], //world password
	'db_name' => $dbinfo_mangos['4'], //world db name
	'db_char' => $dbinfo_mangos['5'], //character db name
	'db_encoding' => 'utf8', // don't change
	) ;

// Free up memory.
unset( $dbinfo_mangos, $mangos_info ) ; 

$CDB = new Database(
	$mangos['db_host'],
	$mangos['db_port'],
	$mangos['db_username'],
	$mangos['db_password'],
	$mangos['db_char']
	);
	
$WDB = new Database(
	$mangos['db_host'],
	$mangos['db_port'],
	$mangos['db_username'],
	$mangos['db_password'],
	$mangos['db_name']
	);

unset($mangos);
$realms = $DB->select("SELECT * FROM realmlist ORDER BY `id` ASC");

// === Load auth system === //
$Account = new Account($DB, $cfg);
$user = $Account->user;
$user['cur_selected_realm'] = (int)$_COOKIE['cur_selected_realm'];


// === Sets up the template system. === //
$tmpl = new Template;
$template = $tmpl->Init();
$currtmp = $template['path'];
$master_tmp = "templates/".$template['script'];


// === Start of page loading === //
$ext = ( isset( $_GET['p'] ) ? $_GET['p'] : ( string )$cfg->get('default_component') ) ;
if ( strpos( $ext, '/' ) !== false ) 
{
	list( $ext, $sub ) = explode( '/', $ext ) ;
}
else
{
	$sub = ( isset( $_GET['sub'] ) ? $_GET['sub'] : 'index' ) ;
}
	$script_file = 'inc/' . $ext . '/' . $ext . '.' . $sub . '.php' ;
	$template_file = '' . $master_tmp . '/' . $ext . '/' . $ext . '.' . $sub . '.php' ;

// Start Loading of Template Files

// If the requested page is the admin Panel
if( $ext == 'admin') 
{
	if(file_exists('inc/admin/body_functions.php')) 
	{
		include ('inc/admin/body_functions.php');
	}
	@include('inc/admin/script_files/admin.' . $sub .'.php');
	ob_start();
		include('inc/admin/body_header.php');
	ob_end_flush();
	ob_start();
		include('inc/admin/template_files/admin.' . $sub .'.php');
	ob_end_flush();
	
	// Set our time end, so we can see how fast the page loaded.
	$time_end = microtime( 1 ) ;
	$exec_time = $time_end - $time_start ;
	include('inc/admin/body_footer.php');
}
else
{
// Else, it requested page isnt the admin panel		
// Start Loading Of Script Files
	@include ( $script_file ) ;

	// If a body functions file exists, include it.
	if(file_exists(''. $master_tmp . '/body_functions.php')) 
	{
		include ( ''. $master_tmp . '/body_functions.php' );
	}
	ob_start() ;
		include ( '' . $master_tmp . '/body_header.php' );
	ob_end_flush() ;
	ob_start() ;
		include ( $template_file ) ;
	ob_end_flush() ;

	// Set our time end, so we can see how fast the page loaded.
	$time_end = microtime( 1 ) ;
	$exec_time = $time_end - $time_start ;
	include ( '' . $master_tmp . '/body_footer.php' ) ;
}

// Close all DB Connections
$DB->__destruct();
$CDB->__destruct();
$WDB->__destruct();
?>