<?php
error_reporting(0);
define('db_host', "localhost");
define('db_user', "root");//AppsDbase
define('db_password', "");//GTechApps@123
define('db_database', "properties");
$con=mysqli_connect(db_host, db_user, db_password, db_database);
// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
include('myActions.php');
?>