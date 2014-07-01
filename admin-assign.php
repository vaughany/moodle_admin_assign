<?php

/**
 * Script to assign an existing user admin rights.
 * Based on a 2007 (Moodle 1.8) script by Petr Skoda: https://moodle.org/mod/forum/discuss.php?d=66281#p298436
 * Updated to be compatible with Moodle 2.x (tested on 2.6) June 2014 by Paul Vaughan, github.com/vaughany.
 * This script available from: github.com/vaughany/moodle_admin_assign
 */

// Replace your_username inside the quotes with the username of the user you want to have admin rights.
// Note: This user must already exist in the Moodle database.
$username = 'your_username';

// Okay, that's it, don't change anything below this line. :)


// Get your Moodle installation's config.
require 'config.php';
$release = substr($CFG->release, 0, 3);
echo 'Script started: '.date('c', time()).".<br>\n";
echo 'Config found: Moodle '.$release." detected.<br>\n";


// The username of the user we are going to upgrade (set on line 12 or so).
if ($username == 'your_username' || $username == '') {
    die('Error: You need to specify a username of an existing user in this script. Please edit the script and run again.');
}
// Query the database with that username.
$user = $DB->get_record('user', array('username' => $username));
// Stop if it couldn't find that user.
if (!$user) {
    die("Error: No user found with the username '$username'. Please edit the script and run again.<br>\n");
}
echo "User '$user->firstname $user->lastname' with username '$username' found.<br>\n";

    // Debugging.
    //echo '<pre>'; var_dump($CFG); echo '</pre>';


// Get the system context and set the context string for different Moodle versions.
// Moodle 2.0 and 2.1 require the older method of getting the context.
if ($release == '2.0' || $release == '2.1') {
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
} else {
    $systemcontext = context_system::instance();
}
// Convert to just the ID.
$systemcontext = $systemcontext->id;
echo "System context acquired.<br>\n";


// Query the database for a role with the appropriate shortname.
$emergencyrole = $DB->get_record('role', array('shortname' => 'emergencyadmin'));


// If one doesn't exist, create it.
if (!$emergencyrole) {
    // Create the new, temporary role with admin rights, with warning disclaimer.
    //$adminroleid = create_role('Emergency Admin', 'emergencyadmin', 'WARNING: This is a temporary role, giving site-wide admin permissions to whoever is assigned to it. It was created manually by an external script to assist users who have been locked out of their Moodle installation and should be deleted as soon as you are finished with it.', 'moodle/site:config');
    //$adminroleid = create_role('Emergency Admin', 'emergencyadmin', 'WARNING: This is a temporary role, giving site-wide admin permissions to whoever is assigned to it. It was created manually by an external script to assist users who have been locked out of their Moodle installation and should be deleted as soon as you are finished with it.', 'system');
    $adminroleid = create_role('Emergency Admin', 'emergencyadmin', 'WARNING: This is a temporary role, giving site-wide admin permissions to whoever is assigned to it. It was created manually by an external script to assist users who have been locked out of their Moodle installation and should be deleted as soon as you are finished with it.');
    echo "Created new role.<br>\n";

    // Assigns the 'moodle/site:config' capability to the new role.
    $res = $DB->insert_record('role_capabilities', array('contextid' => $systemcontext, 'roleid' => $adminroleid, 'capability' => 'moodle/site:config', 'permission' => 1, 'timemodified' => time()));
    if (!$res) {
        die('Adding the capability into the roles table failed.');
    }
    echo "Capability assigned to the new role.<br>\n";
    
} else {
    // Assign the ID from the initial query.
    $adminroleid = $emergencyrole->id;
    echo "Existing role found with the same name.<br>\n";
}


// Assign the user to the new role.
$res = role_assign($adminroleid, $user->id, $systemcontext);
echo "User assigned to role.<br>\n";

echo "<p>Done. User $user->firstname $user->lastname ($username) has been given admin rights to the Moodle site located at <a href=\"$CFG->wwwroot\">$CFG->wwwroot</a>. Please remove the 'Emergency Admin' role as soon as you are able.</p>\n";

echo '<ul><li><a href="'.$CFG->wwwroot.'">Moodle front page</a></li>';
echo '<li><a href="'.$CFG->wwwroot.'/admin/">Admin (notifications) page</a></li>';
echo '<li><a href="'.$CFG->wwwroot.'/admin/roles/manage.php">Manage roles page</a></li></ul>';

exit;
