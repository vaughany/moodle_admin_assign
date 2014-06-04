# Moodle Administrator Assignment script for Moodle 2.x

A drop-in script for Moodle 2 to create a new site-wide admin role, then assign an existing Moodle user to it.

Tested against the most recent stable builds (at the time of writing) of Moodle 2.0, 2.1, 2.2 and 2.6.

Available online here: [github.com/vaughany/moodle\_admin\_assign](https://github.com/vaughany/moodle_admin_assign)

## Why?

Because sometimes you need a user with site-wide admin rights, and you can't log in to Moodle as an admin to do it.

## How?

This script talks directly to Moodle's database (it takes the required information from your Moodle's `config.php` file) and creates a new role called _Emergency Admin_ with the `moodle/site:doanything` capability, and assigns a user of your choice to that role, giving them admin site-wide admin rights. 

It has been created to get one user admin rights in an emergency: it is not a replacement for Moodle's proper way of assigning capabilities, and the role should be deleted as soon as is convenient.

## Use

You will need access to the server Moodle runs on, specifically the part where the Moodle code is stored. Typically this might be `/var/www/` on a Linux server running Apache, or `c:\inetpub\wwwroot` on a Windows server running IIS: your mileage may vary.

* Edit the `assign-admin.php` script in your favourite text editor so that the variable `$username` (on or about line 12) is the username of an existing Moodle user, e.g. 'paulvaughan', so the line will look similar to: `$username = 'paulvaughan';`
* Place the edited script into the root of your Moodle installation (the same folder as `config.php`).
* Access the script from your web browser: http://yourmoodle.co.uk/assign-admin.php
* The script will run and do its thing. Note that if you run it twice, it will fail (it's on the to-do list).
* All being well, you should see _Done_ and a note and some links to your Moodle.

If you are already logged in as the user you 'upgraded', you will need to log out and then in again for the changes to take effect.

## History

This script is mostly based on [Petr Skoda](https://moodle.org/user/profile.php?id=12863)'s 2007 script, which can be found here [moodle.org/mod/forum/discuss.php?d=66281](https://moodle.org/mod/forum/discuss.php?d=66281#p298436).  It has been updated to Moodle 2 (tested on 2.0, 2.1, 2.2 and 2.6) in June 2014 by [Paul Vaughan](https://github.com/vaughany).

## To-do

I have no plans to improve this at all, but suggestions and pull requests are always welcome.