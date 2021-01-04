<?php

/*
LOGIN.PHP
Log in members
*/

// php sessions - a way to easily store information across multiple pages, and they are temporary. They will be deleted when either a session_destroy() function is called, or when the browser is closed. They work by temporarily setting a cookie on the users machine that uniquely identifies them, and allows them to interact with the server and the server will automatically save variables associated with the user.

// we will be using sessions as a way to authenticate users

// start session / load configs ... session start must be the first line of code in the file.
session_start();
include('../includes/config.php');
include('../includes/db.php');

// form defaults
$error['alert'] = '';
$error['user'] = '';
$error['pass'] = '';
$input['user'] = '';
$input['pass'] = '';

// if form has been submitted we want to process it
if (isset($_POST['submit']))
{
    // process form - if username and/or password are blank
    if ($_POST['username'] == '' || $_POST['password'] == '')
    {
        // display an error if one or both are not filled in
        // both fields needs to be filled in
        if ($_POST['username'] == '') { $error['user'] = 'required!';}
        if ($_POST['password'] == '') { $error['pass'] = 'required!';}
        $error['alert'] = "Please fill in required fields!";

        // get the values for username and password.
        // -*-SECURITY-*-  In php, you want to secure inputs, that have $_POST values, by including the htmlentities and ENT_QUOTES. You only need to do this when the inputs are shown back to the user as is the case below because we included views/v_login.php
        $input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
        $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);

        include('../views/v_login.php');
    }
    // otherwise we need to check if username and password are matched against the database
    else
    {
        $input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
        $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);

        // create db query. this creates a mysqli statement, prepares a query that checks that the username and password values that the user inputed match the username and password in the db
        if ($stmt = $mysqli->prepare("SELECT id FROM members WHERE username = ? AND password = ?"))
        {
            // this first line changes the question marks above into the inputed variables
            // the first value includes "ss" because username and password are both strings
            $stmt->bind_param("ss", $input['user'], md5($input['pass'] . $config['salt']));
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->fetch();

            if (id)
            {
                $SESSION['id'] = $id;

                // set session variable - indicates the user is now logged-in
                $_SESSION['username'] = $input['user'];

                // set session variable so that when they first login, we set it to the current time.
                $_SESSION['last_active'] = time();


                // redirect to member's page
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
    if(isset($_GET['unauthorized']))
    {
        $error['alert'] = "Please login to view that page!";
    }
    if(isset($_GET['timeout']))
    {
        $error['alert'] = "Your session has expired. Please log in again.";
    }

    // if the form hasn't been submitted, show form
    include('../views/v_login.php');
}

// close db connection
$mysqli->close();

?>
