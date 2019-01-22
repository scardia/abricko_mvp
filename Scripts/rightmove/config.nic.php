<?php
set_time_limit(0);
define('myAgent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.4');//'NetSurf/1.0 (Linux; i686)');
define('proxyFile', "proxy.txt");
//define('proxyUserName', "US281476");
//define('proxyPassword', "CHbaSejLUS");
define('db_host', "localhost");
define('db_user', "AppsDbase");//AppsDbase
define('db_password', "GTechApps@123");//GTechApps@123
define('db_database', "AppsDbase");
$con=mysqli_connect(db_host, db_user, db_password, db_database);
// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
define('myWebsite', "https://www.rightmove.co.uk");
