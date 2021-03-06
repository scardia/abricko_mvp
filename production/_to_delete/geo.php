<?php
/**  
 * Including Config file
*/
require_once 'assets/config.nic.php';

$minx=$_REQUEST['minx'];

$miny=$_REQUEST['miny'];

$maxx=$_REQUEST['maxx'];

$maxy=$_REQUEST['maxy'];

$qry="SELECT distinct `title`, `latitude`, `longitude`, `yieldValue`,`address`, `imgLink`, `url` FROM `st_listings_sale` 
    WHERE (`latitude` BETWEEN ".$miny." AND ".$maxy.") AND (`longitude` BETWEEN ".$minx." AND ".$maxx.") and `yieldValue`>0 and `yieldValue`<36 and and `bedRoom`>0";
   //return $qry;
$result=mysqli_query($con, $qry);
$data=array();
if (mysqli_num_rows($result) >0) {
    while ($row = $result->fetch_array()) {
        $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
        $langLat='['.$row['latitude'].','.$row['longitude'].']';
        $data[] = array("type"=>"Feature","geometry"=>array("type"=>"Point","coordinates"=> array((float) $row['longitude'],(float) $row['latitude'] )),
            "properties"=>array("name"=>$row['title'],"id"=>$row['id'],"yield"=>$y,"address"=>$row['address'],"image"=>$row['imgLink'], "url"=>$row['url'],));
        }
    $data=array("type"=> "FeatureCollection","features"=>$data);//'{"type": "FeatureCollection","features": '.$data1.'}';
}
echo(json_encode($data));
