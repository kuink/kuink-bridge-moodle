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
 * Library of interface functions and constants.
 *
 * @package     mod_kuink
 * @copyright   kuink <team@kuink.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function kuink_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;            
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_kuink into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_kuink_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function kuink_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();

    $id = $DB->insert_record('kuink', $moduleinstance);

    return $id;
}

/**
 * Updates an instance of the mod_kuink in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_kuink_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function kuink_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('kuink', $moduleinstance);
}

/**
 * Removes an instance of the mod_kuink from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function kuink_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('kuink', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('kuink', array('id' => $id));

    return true;
}

/**
 * Export kuink resource contents
 *
 * @return array of file content
 */
function kuink_export_contents($cm, $baseurl) {
    global $CFG, $DB;
    require_once("$CFG->dirroot/mod/url/locallib.php");
    $contents = array();
    $context = context_module::instance($cm->id);

    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $record = $DB->get_record('kuink', array('id'=>$cm->instance));

    $content = array();
    $content['type'] = 'kuink';
    $content['name'] = $record->name;
    $content['appname'] = $record->appname;
    $content['config'] = $record->config;
    $contents[] = $content;

    return $contents;
}
