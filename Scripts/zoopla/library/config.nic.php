<?php
error_reporting(0);
ini_set('max_execution_time', 0);
define('db_host', "gtechinfo.com");
define('db_user', "AppsDbase");//AppsDbase
define('db_password', "GTechApps@123");//GTechApps@123
define('db_database', "AppsDbase");
$con=mysqli_connect(db_host, db_user, db_password, db_database);
// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL12: " . mysqli_connect_error());
}
include('myActions.php');
define('myWebsite', "https://www.rightmove.co.uk");
