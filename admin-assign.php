<?php

/**
 * Script to assign an existing user admin rights.
 * Based on a 2007 (Moodle 1.8) script by Petr Skoda: https://moodle.org/mod/forum/discuss.php?d=66281#p298436
 * Updated to be compatible with Moodle 2.x (tested on 2.6) June 2014 by Paul Vaughan, github.com/vaughany.
 * This script available from: github.com/vaughany/moodle_admin_assign
 */

// Replace your_username inside the quotes with the username of the user you want to have admin rights.
// Note: This user must already exist in the Moodle database. 
$username = 'paulvaughan';

// Okay, that's it, don't change anything below this line. :)

// Get your Moodle installation's config.
require 'config.php';

// Get the system context.
$systemcontext = context_system::instance();

// Query the database for a role with the appropriate shortname.
$emergencyrole = $DB->get_record('role', array('shortname' => 'emergencyadmin'));

// If one doesn't exist, create it.
if (!$emergencyrole) {
	// Create the new, temporary role with admin rights, with warning disclaimer.
	$adminroleid = create_role('Emergency Admin', 'emergencyadmin', 'WARNING: This is a temporary role, giving site-wide admin permissions to whoever is assigned to it. It was created manually by an external script to assist users who have been locked out of their Moodle installation and should be deleted as soon as you are finished with it. ', 'moodle/site:doanything');
} else {
	// Assign the ID from the initial query.
	$adminroleid = $emergencyrole->id;
}

// Gives the new role the defaults for the archetype set in $adminroleid.
reset_role_capabilities($adminroleid);

// The username of the user we are going to upgrade (set on line 12 or so).
$user = $DB->get_record('user', array('username' => $username));

// Assign the user to the new role.
role_assign($adminroleid, $user->id, $systemcontext->id);

echo '<p>Done. User '.$user->firstname.' '.$user->lastname.' has been given admin rights.</p>';
echo '<ul><li><a href="'.$CFG->wwwroot.'">Moodle front page</a></li>';
echo '<li><a href="'.$CFG->wwwroot.'/admin/">Admin page (Notifications)</a></li>';
echo '<li><a href="'.$CFG->wwwroot.'/admin/roles/manage.php">Manage roles page</a></li></ul>';

exit;
