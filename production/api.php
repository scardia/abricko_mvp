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

$ipp = 25;

$id=$_REQUEST['id'];

$city = $_REQUEST['city'];
$ptype = $_REQUEST['ptype'];

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

$order_field = $_REQUEST['order'];
$order_dir = "ASC";
if ($order_field == "yieldValue") {
    $order_dir = "DESC";
}

$sel="SELECT distinct(*) 
FROM `st_listings_sale_stuff` 
WHERE ";

$cnt="SELECT count(*) as count 
FROM `st_listings_sale_stuff` 
WHERE ";

$conds = array();
if($id) {
    $conds[] = "id = ".$id;
    $qry = $sel . implode(" and ", $conds);
    $cont = 1;
} else {

    $conds[] = "`yieldValue`>0 and `yieldValue`<36 ";
    $conds[] = "`bedRoom`>0";
    if ($minx){
        $conds[] = " (`latitude` BETWEEN ".$miny." AND ".$maxy.") AND (`longitude` BETWEEN ".$minx." AND ".$maxx.")";
    }
    if ($city) {
        $conds[] = " ( city like \"%".$city."%\" or zipcode like \"%".$city."%\" ) ";
    }
    if ($ptype){
        $conds[] = " title like \"%".$ptype."%\" ";
    }
    if ($minyield || $maxyield)
        $conds[] = " (`yieldValue` BETWEEN " . $minyield . " AND ". $maxyield .") ";
    if ($minbedrooms || $maxbedrooms)
        $conds[] = " (`bedRoom` BETWEEN " . $minbedrooms . " AND ". $maxbedrooms .") ";
    if ($minprice || $maxprice)
        $conds[] = " (`price` BETWEEN " . $minprice . " AND ". $maxprice .") ";
    if ($page)
        $page = ($page-1)*$ipp;
    else 
        $page = 0;
    $order = " ORDER by ".$order_field." ".$order_dir." limit ".$page.",".$ipp;

    $qry = $sel . implode(" and ", $conds) . $order;
    $cqry = $cnt . implode(" and ", $conds);
    $cntr=mysqli_query($con, $cqry);
    if (mysqli_num_rows($cntr) > 0) {
        while ($row = $cntr->fetch_array()) {
            $cont = $row["count"];
        }
    }

}
$qry = str_replace("\n","",$qry);
#echo $qry;
//echo "<script>console.log( 'Checking Query for Duplicates: " . $qry . "' );</script>";
$result=mysqli_query($con, $qry);
$data=array();
if (mysqli_num_rows($result) >0) {
    while ($row = $result->fetch_array()) {
        $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
        $yield=strval($y);
        $yieldp=$yield."%";
        $row["label"] = $row['title'];//str_replace(" for sale", "", $row['title']);
        $row["indexLabel"] = $yieldp;
        $row["yield"] = $yield;
        $row["y"] = $y;
        $data[] = $row;
    }
}
header('Access-Control-Allow-Origin: *'); 
echo(json_encode(array("query"=>$qry, "results"=>$data, "count"=>$cont)));
