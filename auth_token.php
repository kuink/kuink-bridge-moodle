<?php
require(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/config.php');
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/login/lib.php');
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/webservice/lib.php');
global $KUINK_INCLUDE_PATH;
$KUINK_INCLUDE_PATH = '';
require_once($KUINK_INCLUDE_PATH."neon_includes.php");

//$user = authenticate_user($_GET['token']);
$webservice = new webservice();
$usrArray = $webservice->authenticate_user($_GET['token']);
if (!isset($usrArray['user'])) {
	// do nothing
} else {

	//continue login
	//var_dump($usrArray);

	$user = $usrArray['user'];
	complete_user_login($user);
	//set_moodle_cookie($user->username);
	
	\Kuink\Core\ProcessOrchestrator::registerAPI($_GET['neonfunction']);
	$contextId = \Kuink\Core\ProcessOrchestrator::getContextId();
	
	$_SESSION['_kuink_api_security_bypass'] = true; 
	
	redirect('api.php?'.$_SERVER['QUERY_STRING']."&idcontext=".$contextId);
	

	
}
