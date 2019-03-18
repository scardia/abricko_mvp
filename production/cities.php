<?php 

error_reporting(1);
set_time_limit(0);
ini_set('memory_limit', '-1');
define('db_host', getenv("DB_HOST"));
define('db_user', getenv("DB_USER"));//AppsDbase
define('db_password', getenv("DB_PASS"));//GTechApps@123
define('db_database', getenv("DB_DATA"));
$con=mysqli_connect(db_host, db_user, db_password, db_database);
// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$ipp = 15;

$city=$_REQUEST['city'];

$sel="SELECT * 
FROM `cities` 
WHERE city like %".$city."% ";

$order = " ORDER by `.$order.` DESC limit ".$ipp;

$qry = $sel . $order;

#echo $qry;
//echo "<script>console.log( 'Checking Query for Duplicates: " . $qry . "' );</script>";
$result=mysqli_query($con, $qry);
$data=array();
if (mysqli_num_rows($result) >0) {
    while ($row = $result->fetch_array()) {
        $data[] = $row["city"];
    }
}
header('Access-Control-Allow-Origin: *'); 
echo(json_encode(array("query"=>$qry, "results"=>$data, "count"=>$cont)));
