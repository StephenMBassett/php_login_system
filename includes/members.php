<?php

/*
MEMBERS.PHP
Password protected area for members only
*/

// start session
session_start();
// include any imported files
include("config.php");

// check that user is logged in
if (!isset($_SESSION['username']))
{
    header("Location: login.php?unauthorized");
}

// check for inactivity after the user logs in and comes to the member's page
if(time() > $_SESSION['last_active'] + $config['session_timeout'])
{
    // log out user
    session_destroy();
    header("Location: login.php?timeout");
}
else
{

    // otherwise, update the session variable with the current time
    $_SESSION['last_active'] = time();
}

// display view
include("../views/v_members.php");

?>
