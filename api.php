<?php
// This client for local_wstemplate is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * XMLRPC client for Moodle 2 - local_wstemplate
 *
 * This script does not depend of any Moodle code,
 * and it can be called from a browser.
 *
 * @authorr Jerome Mouneyrac
 */

/// MOODLE ADMINISTRATION SETUP STEPS
// 1- Install the plugin
// 2- Enable web service advance feature (Admin > Advanced features)
// 3- Enable XMLRPC protocol (Admin > Plugins > Web services > Manage protocols)
// 4- Create a token for a specific user and for the service 'My service' (Admin > Plugins > Web services > Manage tokens)
// 5- Run this script directly from your browser: you should see 'Hello, FIRSTNAME'

global $CFG;
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/config.php');
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/login/lib.php');
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/webservice/lib.php');

global $KUINK_INCLUDE_PATH, $KUINK_BRIDGE_CFG, $KUINK;
$KUINK = new stdClass();
$KUINK->id = null;
$KUINK->fullname = '';
$KUINK->appname = 'framework';
$KUINK->config = '<Configuration/>';
//Turn off all error reporting to avoid garbage in response
error_reporting(0);

include ('./bridge_config.php');

//Get the function to execute
$function = isset ( $_GET ['neonfunction'] ) ? ( string ) $_GET ['neonfunction'] : '';

require_once ('./kuink-core/bootstrap/autoload.php');

//Authenticate user if token is present
if (isset($_GET['token'])) {

	$webservice = new webservice();
	$usrArray = $webservice->authenticate_user($_GET['token']);

	if (!isset($usrArray['user'])) {
		// do nothing
		require_login(null, true, $cm);
	} else {
		$user = $usrArray['user'];
		complete_user_login($user);

		\Kuink\Core\ProcessOrchestrator::registerAPI($_GET['neonfunction']);

		$_SESSION['_kuink_api_security_bypass'] = true;
	}
} else {
	require_login(null, true, $cm);
}

$kuinkCore = new Kuink\Core($KUINK_BRIDGE_CFG, null);
$kuinkCore->call($function);

?>
