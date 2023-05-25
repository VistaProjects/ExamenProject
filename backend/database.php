<?php

// Connect to local database
$serverName = 'localhost';
$userName = 'root';
$userPassword = '';
$dbName = 'duzenco';

// This is the root folder of the project
// Example: http://localhost/examenproject/backend/index.php?item=cards
$base_link = '/examenproject/backend/';
 
$conn = mysqli_connect($serverName, $userName, $userPassword, $dbName);
