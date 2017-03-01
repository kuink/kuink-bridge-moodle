<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of kuink
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package   mod_kuink
 * @copyright 2010 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG;
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/config.php');
require_once($CFG->libdir.'/ddllib.php');
require_once('lib.php');
require_once('locallib.php');
require_once($CFG->libdir.'/weblib.php');
require_once($CFG->libdir.'/filelib.php');


//for all dates, set utc timezone. jmpatricio
date_default_timezone_set('UTC');

//$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$id = $_GET['id']; // course_module ID, or
$n  = optional_param('k', 0, PARAM_INT);  // kuink instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('kuink', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $kuink  = $DB->get_record('kuink', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $kuink  = $DB->get_record('kuink', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $kuink->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('kuink', $kuink->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, 'kuink', 'view', "view.php?id=$cm->id", $kuink->name, $cm->id);

/// Print the page header

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');

//pmt. added to make a clear output when generating rss for example
$templ = isset($_GET['template']) ? (string)$_GET['template'] : '';

// Output starts here
//if ($templ != "none") //pmt.added
//{
//    echo $OUTPUT->header();
//}


//################################ KUINK START #######################################
global $KUINK_INCLUDE_PATH;
$KUINK_INCLUDE_PATH = realpath('').'/kuink-core/';

global $KUINK_BRIDGE_CFG;

include ('./bridge_config.php');

//Force HTTPS
if (!empty($CFG->loginhttps))
  if (!isset($_SERVER['HTTPS'])) {
    $PAGE->set_url('/mod/kuink/view.php?'.$_SERVER['QUERY_STRING']);
    //die();
    $PAGE->verify_https_required();
  }

include ('./kuink-core/view.php');

//################################ KUINK END #######################################