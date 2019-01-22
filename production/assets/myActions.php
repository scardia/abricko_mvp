<?php
function getTopTen()
{
    global $con;
    $zipcode=$_REQUEST['zipcode'];
    $qry="SELECT distinct `title`, `latitude`, `longitude`, `yieldValue`, `imgLink`, `url` FROM `st_listings_sale` WHERE `zipcode`='".$zipcode."' and `yieldValue`>0 ORDER by `yieldValue` DESC LIMIT 0,10";
    $result=mysqli_query($con, $qry);
    $data=array();
    if (mysqli_num_rows($result) >0) {
        while ($row = $result->fetch_array()) {
            $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
            $yield=strval($y);
            $yield=$yield."%";
            $title=$row['title'];//str_replace(" for sale", "", $row['title']);
            $data[] = array("label"=>$title,"y"=>$y,"url"=>$row['imgLink'],"indexLabel"=>$yield,"pUrl"=>$row['url'],"lat"=>$row['latitude'],"lang"=>$row['longitude']);
        }
    }
    return json_encode($data);
}

function getTopTen1()
{
    global $con;
    $minx=$_REQUEST['minx'];
    $miny=$_REQUEST['miny'];
    $maxx=$_REQUEST['maxx'];
    $maxy=$_REQUEST['maxy'];
    $qry="SELECT distinct `title`, `latitude`, `longitude`, `yieldValue`, `imgLink`, `url` FROM `st_listings_sale` WHERE (`latitude` BETWEEN ".$miny." AND ".$maxy.") AND (`longitude` BETWEEN ".$minx." AND ".$maxx.") and `yieldValue`>0 ORDER by `yieldValue` DESC LIMIT 0,10";
    //return $qry;
    $result=mysqli_query($con, $qry);
    $data=array();
    if (mysqli_num_rows($result) >0) {
        while ($row = $result->fetch_array()) {
            $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
            $yield=strval($y);
            $yield=$yield."%";
            $title=$row['title'];//str_replace(" for sale", "", $row['title']);
            $data[] = array("label"=>$title,"y"=>$y,"url"=>$row['imgLink'],"indexLabel"=>$yield,"pUrl"=>$row['url'],"lat"=>$row['latitude'],"lang"=>$row['longitude']);
        }
    }
    return json_encode($data);
}

function getGeoJson()
{
    global $con;
    $qry="SELECT distinct `id`,`title`, `latitude`, `longitude`, `yieldValue`, `imgLink`, `url` FROM `st_listings_sale` WHERE `yieldValue`>0 ORDER by `yieldValue`";
    //return $qry;
    $result=mysqli_query($con, $qry);
    $data=array();
    if (mysqli_num_rows($result) >0) {
        while ($row = $result->fetch_array()) {
            $y=doubleval(number_format(($row['yieldValue']), 2, '.', ''));
            $langLat='['.$row['latitude'].','.$row['longitude'].']';
            $data[] = array("type"=>"Feature","geometry"=>array("type"=>"Point","coordinates"=> array((float) $row['longitude'],(float) $row['latitude'] )),"properties"=>array("name"=>$row['title'],"id"=>$row['id'],"yield"=>$y,"image"=>$row['imgLink']));
        }
    }
    $data1=array("type"=> "FeatureCollection","features"=>$data);//'{"type": "FeatureCollection","features": '.$data1.'}';
    $data1= json_encode($data1);
    return $data1;
}

function updateZipCode()
{
    global $con;
    //$qry="SELECT sum(`price`),COUNT(`id`) FROM `st_listings` WHERE `zipcode`=`AB25`";
    $qry="SELECT DISTINCT zipcode FROM st_listings_rent";
    $result=mysqli_query($con, $qry);
    while ($row = mysqli_fetch_assoc($result)) {
        $zipcode=$row['zipcode'];
        if ($zipcode!="") {
            $qry1="SELECT sum(m2Price) as sPrice,COUNT(id) as cId FROM st_listings_rent WHERE zipcode='".$zipcode."'";
            $result1=mysqli_query($con, $qry1);
            while ($row = mysqli_fetch_assoc($result1)) {
                $price=$row['sPrice'];
                $tot=$row['cId'];
                $avg=$price/$tot;
                $qry2="INSERT INTO `st_zip_avg`(`zipCode`, `avg`) VALUES ('".trim($zipcode)."','".trim($avg)."')";
                mysqli_query($con, $qry2);
            }
        }
    }
}
function updateYieldVal()
{
    global $con;
    $qry1="SELECT id,zipcode,m2Price FROM st_listings_sale";
    $result=mysqli_query($con, $qry1);
    while ($row = mysqli_fetch_assoc($result)) {
        $zipcode=$row['zipcode'];
        if ($zipcode!="") {
            $qry1="SELECT avg FROM `st_zip_avg` WHERE zipCode='".$zipcode."' limit 1";
            if ($result1=mysqli_query($con, $qry1)) {
                while ($row1=mysqli_fetch_row($result1)) {
                    $yield=(($row1[0])*100)/(int)$row['m2Price'];
                    $qry2="UPDATE st_listings_sale SET yieldValue=".$yield." WHERE id=".$row['id'];
                    mysqli_query($con, $qry2);
                }
                mysqli_free_result($result1);
            }
        }
    }
}
function generateRandomString($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
