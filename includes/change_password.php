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
$error['current_pass'] = '';
$error['pass'] = '';
$error['pass2'] = '';
$input['current_pass'] = '';
$input['pass'] = '';
$input['pass2'] = '';

// if form has been submitted we want to process it
if (isset($_POST['submit']))
{
    // process form - if username and/or password are blank
    if ($_POST['current_pass'] == '' || $_POST['password'] == '' || $_POST['password2'] == '')
    {
        // display an error if one or both are not filled in
        // both fields needs to be filled in
        if ($_POST['current_pass'] == '') { $error['current_pass'] = 'required!';}
        if ($_POST['password'] == '') { $error['pass'] = 'required!';}
        if ($_POST['password2'] == '') { $error['pass2'] = 'required!';}
        $error['alert'] = "Please fill in required fields!";

        // get the values for username and password
        $input['current_pass'] = htmlentities($_POST['current_pass'], ENT_QUOTES);
        $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
        $input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

        include('../views/v_password.php');
    }
    else if($_POST['password'] != $_POST['password2'])
    {
        // both password fields need to match
        $error['alert'] = "Password fields must match!";

        // get the values for username and password
        $input['current_pass'] = htmlentities($_POST['current_pass'], ENT_QUOTES);
        $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
        $input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

        include('../views/v_password.php');
    }

    // otherwise we need to check if username and password are matched against the database
    else
    {
        $input['current_pass'] = $_POST['current_pass'];
        $input['pass'] = $_POST['password'];
        $input['pass2'] = $_POST['password2'];

        // check if password matches the password of the current active user. we need to make sure the user knows the current password in the database before we permit them to change their password.
        if ($check = $mysqli->prepare("SELECT password FROM members WHERE id = ?"))
        {
            // pass in the session id that we just created. so below, we bound the session id to the question mark in the previous mysqli statement.
            $check->bind_param("s", $_SESSION['id']);
            $check->execute();
            $check->bind_result($current_pass);
            $check->fetch();
            $check->close();
        }

        // after we fetch the password in the db, we need to make sure that it matches up

        if (md5($input['current_pass'] . $config['salt']) != $current_pass)
        {
            // error
            $effor['alert'] = "Your current password is incorrect!";
            $error['current_pass'] = "incorrect";
            include("../views/v_password.php");
        }
        else
        {
            // Insert data into the db. When we use the prepare statement, the data that we input is automatically "escaped", cleaned for us.
            if ($stmt = $mysqli->prepare("UPDATE members SET password = ? WHERE id = ?"))
            {
                // this first line changes the question marks above into the inputed variables
                // the first value includes "ss" because username and password are both strings
                $stmt->bind_param("ss", md5($input['pass'] . $config['salt']), $_SESSION['id']);
                $stmt->execute();
                $stmt->close();

                $error['alert'] = "Password updated successfully!";
                $input['current_pass'] = '';
                $input['pass'] = '';
                $input['pass2'] = '';

                // show form
                include('../views/v_password.php');
            }
            else
            {
                echo "ERROR: Could not prepare MySQLi statement.";
            }
        }
    }
}
else
{
    include('../views/v_password.php');
}

// close db connection
$mysqli->close();

?>
