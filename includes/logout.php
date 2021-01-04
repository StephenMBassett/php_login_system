<?php

/*
LOGOUT.PHP
Log out member
*/

// start session
session_start();
session_destroy();

// display view to user
include("../views/v_loggedout.php");

?>
