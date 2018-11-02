<?php

//################################ KUINK START #######################################
global $KUINK_INCLUDE_PATH;
$KUINK_INCLUDE_PATH = realpath('').'/kuink-core/';

global $KUINK_BRIDGE_CFG;
global $CFG;
if (!isset($KUINK_BRIDGE_CFG))
	$KUINK_BRIDGE_CFG = new stdClass();
$KUINK_BRIDGE_CFG->loginHttps = isset($CFG->loginhttps) ? $CFG->loginhttps : '';
$KUINK_BRIDGE_CFG->wwwRoot = $CFG->wwwroot;
$KUINK_BRIDGE_CFG->dirRoot = $CFG->dirroot;
$KUINK_BRIDGE_CFG->dataRoot = $CFG->dataroot;
$KUINK_BRIDGE_CFG->appRoot = $CFG->dataroot.'/neon/'; //Legacy: Temporary for neon compatibility
$KUINK_BRIDGE_CFG->kuinkRoot = 'mod/kuink';
$KUINK_BRIDGE_CFG->theme = 'adminlte';
$KUINK_BRIDGE_CFG->bridge = 'kuink-bridge-moodle';
$KUINK_BRIDGE_CFG->uploadVirtualPrefix = ''; //Only for neon compatibility. Leave blank in a fresh install.

//######## Authentication stuff ########
global $USER;

if (!$USER->idnumber)
	$currentRole = 'Guest';
else
	$currentRole = 'Student';

//$courseContext = get_context_instance(CONTEXT_COURSE, $COURSE->id);
$courseContext = context_course::instance($COURSE->id);
//var_dump($courseContext);
if (has_capability('moodle/course:activityvisibility', $courseContext))
	$currentRole = 'Teacher';
$roles[] = $currentRole;
$isAdmin = false;

if(has_capability('moodle/site:config', $courseContext)) {
	$isAdmin = true;
	$roles[] = 'framework.admin';
}


//var_dump($KUINK_BRIDGE_CFG->auth->user);
$KUINK_BRIDGE_CFG->auth = new stdClass();

$KUINK_BRIDGE_CFG->application = $kuink->appname;
$KUINK_BRIDGE_CFG->configuration = $kuink->config;

$KUINK_BRIDGE_CFG->auth->roles = $roles;
$KUINK_BRIDGE_CFG->auth->isAdmin = $isAdmin;
$KUINK_BRIDGE_CFG->auth->currentRole = $currentRole;

$KUINK_BRIDGE_CFG->auth->user = new stdClass();
$KUINK_BRIDGE_CFG->auth->user->id = $USER->idnumber;
$KUINK_BRIDGE_CFG->auth->user->firstName = $USER->firstname;
$KUINK_BRIDGE_CFG->auth->user->lastName = $USER->lastname;
$KUINK_BRIDGE_CFG->auth->user->lang = $USER->lang;
$KUINK_BRIDGE_CFG->auth->sessionKey = sesskey();


GLOBAL $course;
//var_dump($course);
$KUINK_BRIDGE_CFG->trigger = new stdClass; //The url to set in breadcrumb after home. On other bridges this is the external point where kuink was triggered. Allow get back to that url
$KUINK_BRIDGE_CFG->trigger->url = $KUINK_BRIDGE_CFG->wwwRoot . '/course/view.php?id=' . $course->id;
$KUINK_BRIDGE_CFG->trigger->label = $course->fullname;
//Global $course; 'href' => $url . '/course/view.php?id=' . $course->id,
//################################ KUINK END #######################################
?>