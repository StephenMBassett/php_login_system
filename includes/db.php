<?php

/* 
DB.PHP
Database Setting & Connection
*/

$server = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'login';

// connect to the database

$mysqli = new mysqli($server, $user, $pass, $db);

?>