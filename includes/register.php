<?php

/*
REGISTER.PHP
Register New Members
*/

// start session / load configs
session_start();
include('../includes/config.php');
include('../includes/db.php');

// check that user is logged in
if (!isset($_SESSION['username']))
{
    header("Location: login.php");
}

// check for inactivity after the user logs in and comes to the member's page
if(time() > $_SESSION['last_active'] + $config['session_timeout'])
{
    // log out user
    session_destroy();
    header("Location: login.php?timeout");
}
else{

    // otherwise, update the session variable with the current time
    $_SESSION['last_active'] = time();
}

// form defaults
$error['alert'] = '';
$error['user'] = '';
$error['pass'] = '';
$error['pass2'] = '';
$input['user'] = '';
$input['pass'] = '';
$input['pass2'] = '';

// if form has been submitted we want to process it
if (isset($_POST['submit']))
{
    // process form - if username and/or password are blank
    if ($_POST['username'] == '' || $_POST['password'] == '' || $_POST['password2'] == '')
    {
        // display an error if one or both are not filled in
        // both fields needs to be filled in
        if ($_POST['username'] == '') { $error['user'] = 'required!';}
        if ($_POST['password'] == '') { $error['pass'] = 'required!';}
        if ($_POST['password2'] == '') { $error['pass2'] = 'required!';}
        $error['alert'] = "Please fill in required fields!";

        // get the values for username and password
        $input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
        $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
        $input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

        include('../views/v_register.php');
    }
    else if($_POST['password'] != $_POST['password2'])
    {
        // both password fields need to match
        $error['alert'] = "Password fields must match!";

        // get the values for username and password
        $input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
        $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
        $input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

        include('../views/v_register.php');
    }

    // otherwise we need to check if username and password are matched against the database
    else
    {
        $input['user'] = $_POST['username'];
        $input['pass'] = $_POST['password'];
        $input['pass2'] = $_POST['password2'];

        // Insert data into the db. When we use the prepare statement, the data that we input is automatically "escaped", cleaned for us.
        if ($stmt = $mysqli->prepare("INSERT members (username, password) VALUES (?, ?)"))
        {
            // this first line changes the question marks above into the inputed variables
            // the first value includes "ss" because username and password are both strings
            $stmt->bind_param("ss", $input['user'], md5($input['pass'] . $config['salt']));
            $stmt->execute();
            $stmt->close();

            $error['alert'] = "Member added successfully!";
            $input['user'] = '';
            $input['pass'] = '';
            $input['pass2'] = '';
            include('../views/v_register.php');

            if ($stmt->num_rows > 0)
            {
                // set session variable - indicates the user is now logged-in
                $_SESSION['username'] = $input['user'];

                header("Location: members.php");
            }
            else{
                // username/password incorrect
                $error['alert'] = "Username or password is incorrect!";
                include('../views/v_login.php');
            }
        }
        else
        {
            echo "ERROR: Could not prepare MySQLi statement.";
        }

    }
}
else
{
    include('../views/v_register.php');
}

// close db connection
$mysqli->close();

?>
