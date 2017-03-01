<?php

//################################ KUINK START #######################################
global $KUINK_INCLUDE_PATH;
$KUINK_INCLUDE_PATH = realpath('').'/kuink-core/';

global $KUINK_BRIDGE_CFG;
global $CFG;
$KUINK_BRIDGE_CFG->loginhttps = $CFG->loginhttps;
$KUINK_BRIDGE_CFG->wwwroot = $CFG->wwwroot;
$KUINK_BRIDGE_CFG->dirroot = $CFG->dirroot;
$KUINK_BRIDGE_CFG->dataroot = $CFG->dataroot;
$KUINK_BRIDGE_CFG->kuinkroot = 'mod/kuink/';
$KUINK_BRIDGE_CFG->theme = 'default';

//######## Authentication stuff ########
global $USER;
if (!$USER->idnumber)
	$currentRole = 'Guest';
else
	$currentRole = 'Student';

$coursecontext = get_context_instance(CONTEXT_COURSE, $COURSE->id);
if (has_capability('moodle/course:activityvisibility', $coursecontext))
	$currentRole = 'Teacher';
$roles[] = $currentRole;
$isAdmin = false;

if(has_capability('moodle/site:config', $coursecontext)) {
	$isAdmin = true;
	$roles[] = 'framework.admin';
}

$KUINK_BRIDGE_CFG->application = $kuink->appname;
$KUINK_BRIDGE_CFG->configuration = $kuink->config;

$KUINK_BRIDGE_CFG->auth->roles = $roles;
$KUINK_BRIDGE_CFG->auth->isAdmin = $isAdmin;
$KUINK_BRIDGE_CFG->auth->currentRole = $currentRole;

$KUINK_BRIDGE_CFG->auth->user->id = $USER->idnumber;
$KUINK_BRIDGE_CFG->auth->user->firstName = $USER->firstname;
$KUINK_BRIDGE_CFG->auth->user->lastName = $USER->lastname;
$KUINK_BRIDGE_CFG->auth->user->lang = $USER->lang;
$KUINK_BRIDGE_CFG->auth->sessionKey = sesskey();

//################################ KUINK END #######################################
?>