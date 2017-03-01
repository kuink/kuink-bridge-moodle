<?php
require(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/config.php');
require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/login/lib.php');

$user = authenticate_user_login('guest', 'guest');

complete_user_login($user);
set_moodle_cookie($user->username);
$urltogo = urldecode($_GET['url']);

redirect($urltogo);