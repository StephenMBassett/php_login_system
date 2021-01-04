<?php

/*   -*-SECURITY-*-
In php, you want to secure inputs, that have $_POST values, by including the htmlentities and ENT_QUOTES. You only need to do this when the inputs are shown back to the user as is the case of the login.php file, line 38...because we included views/v_login.php  If we don't show the user the inputs, then it's not necessary to include htmlentities and ENT_QUOTES. Additionally, when we use the prepare statement, the data that we input is automatically "escaped", cleaned for us...as seen in the register.php file, line 69 where we inputed data into the db using the prepare statement.
*/

/*
CONFIG.PHP
Configuration Settings
*/

// user authentication
// adding a salt to our password makes them more secure
$config['salt'] = 'jK7d?3';
$config['session_timeout'] = 500; // 500 seconds

// error reporting
mysqli_report(MYSQLI_REPORT_ERROR);

?>
