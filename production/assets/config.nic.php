<?php
error_reporting(1);
define('db_host', getenv("DB_HOST"));
define('db_user', getenv("DB_USER"));//AppsDbase
define('db_password', getenv("DB_PASS"));//GTechApps@123
define('db_database', getenv("DB_DATA"));
$con=mysqli_connect(db_host, db_user, db_password, db_database);
// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
include('myActions.php');
?>