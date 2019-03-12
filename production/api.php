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
$minx=$_REQUEST['minx'];
$miny=$_REQUEST['miny'];
$maxx=$_REQUEST['maxx'];
$maxy=$_REQUEST['maxy'];
$minyield=$_REQUEST['minyield'];
$maxyield=$_REQUEST['maxyield'];
$minbedrooms=$_REQUEST['minbedrooms'];
$maxbedrooms=$_REQUEST['maxbedrooms'];
$minprice=$_REQUEST['minprice'];
$maxprice=$_REQUEST['maxprice'];
$page = $_REQUEST['page'];
#$qry="SELECT distinct `title`, `latitude`, `longitude`, `calc_yield` as `yieldValue`, `imgLink`, `url` FROM `v_st_listings_sale` WHERE (`latitude` BETWEEN ".$miny." AND ".$maxy.") AND (`longitude` BETWEEN ".$minx." AND ".$maxx.") and `calc_yield`>0 and `calc_yield`<36 and `bedRoom`>0 ORDER by `calc_yield` DESC LIMIT 0,30";
$sel="SELECT `title`, `latitude`, `longitude`, `zipcode`, `bedRoom`, `yieldValue`, `imgLink`, `url`, `price`, `zipcode`, `average`
FROM `st_listings_sale_stuff` 
WHERE ";

$conds = array();
$conds[] = "`yieldValue`>0 and `yieldValue`<36 ";
$conds[] = "`bedRoom`>0";
if ($minx){
    $conds[] = " (`latitude` BETWEEN ".$miny." AND ".$maxy.") AND (`longitude` BETWEEN ".$minx." AND ".$maxx.")";
}
if ($minyield)
    $conds[] = " (`yieldValue` BETWEEN " . $minyield . " AND ". $maxyield .") ";
if ($minbedrooms)
    $conds[] = " (`bedRoom` BETWEEN " . $minbedrooms . " AND ". $maxbedrooms .") ";
if ($minprice)
    $conds[] = " (`price` BETWEEN " . $minprice . " AND ". $maxprice .") ";
if ($page)
    $page = ($page-1)*50;
else 
    $page = 0;
$order = " ORDER by `yieldValue` DESC limit ".$page.",50";

$qry = $sel . implode(" and ", $conds) . $order;
#echo $qry;
//echo "<script>console.log( 'Checking Query for Duplicates: " . $qry . "' );</script>";
$result=mysqli_query($con, $qry);
$data=array();
if (mysqli_num_rows($result) >0) {
    while ($row = $result->fetch_array()) {
        $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
        $yield=strval($y);
        $yieldp=$yield."%";
        $title=$row['title'];//str_replace(" for sale", "", $row['title']);
        $data[] = array(
            "label"=>$title,
            "y"=>$y,
            "url"=>$row['imgLink'],
            "indexLabel"=>$yieldp,
            "yield"=> $yield, 
            "url"=>$row['url'],
            "img"=>$row['imgLink'],
            "price"=>$row['price'],
            "zipcode"=>$row['zipcode'],
            "average"=>$row['average'],
            
            "lat"=>$row['latitude'],
            "lang"=>$row['longitude'],
            "bedRooms"=>$row['bedRoom']
        );
    }
}
header('Access-Control-Allow-Origin: *'); 
echo(json_encode(array("query"=>$qry, "results"=>$data)));
