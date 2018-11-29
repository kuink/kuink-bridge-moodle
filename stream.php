<?php
global $CFG;
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/config.php');
require_once($CFG->libdir.'/ddllib.php');
require_once('lib.php');
require_once('locallib.php');
require_once($CFG->libdir.'/weblib.php');
require_once($CFG->libdir.'/filelib.php');

//################################ KUINK START #######################################
global $KUINK_INCLUDE_PATH, $KUINK_BRIDGE_CFG, $KUINK;
$KUINK = new stdClass();
$KUINK->id = null;
$KUINK->fullname = '';
$KUINK->appname = 'framework';
$KUINK->config = '<Configuration/>';

include ('./bridge_config.php');

$type = $_GET ['type'];
$guid = $_GET ['guid'];

require_once ('./kuink-core/bootstrap/autoload.php');
$kuinkCore = new \Kuink\Core($KUINK_BRIDGE_CFG, null);
$kuinkCore->stream($type, $guid);
//################################ KUINK END #######################################