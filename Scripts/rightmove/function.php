<?php
include('config.nic.php');
$con=mysqli_connect('localhost', 'root', '', 'properties');

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
//$qry="SELECT sum(`price`),COUNT(`id`) FROM `st_listings` WHERE `zipcode`=`AB25`";
/*
$qry="SELECT DISTINCT zipcode FROM st_listings_rent";
$result=mysqli_query($con,$qry);
while ($row = mysqli_fetch_assoc($result))
{
   $zipcode=$row['zipcode'];
   if($zipcode!=""){
       $qry1="SELECT sum(m2Price) as sPrice,COUNT(id) as cId FROM st_listings_rent WHERE zipcode='".$zipcode."'";
       $result1=mysqli_query($con,$qry1);
       while ($row = mysqli_fetch_assoc($result1)){
           $price=$row['sPrice'];
           $tot=$row['cId'];
           $avg=$price/$tot;
           echo $qry2="INSERT INTO `st_zip_avg`(`zipCode`, `avg`) VALUES ('".trim($zipcode)."','".trim($avg)."')";
           mysqli_query($con, $qry2);
       }
   }
}
*/

$qry1="SELECT id,zipcode,m2Price FROM st_listings";
$result=mysqli_query($con, $qry1);
while ($row = mysqli_fetch_assoc($result)) {
    $zipcode=$row['zipcode'];
    if ($zipcode!="") {
        $qry1="SELECT avg FROM st_zip_avg WHERE zipCode='".$zipcode."' limit 1";
        if ($result1=mysqli_query($con, $qry1)) {
            while ($row1=mysqli_fetch_row($result1)) {
                $yield=(($row1[0])*100)/(int)$row['m2Price'];
                echo "<br>".$qry2="UPDATE st_listings SET yieldValue=".$yield." WHERE id=".$row['id'];
                mysqli_query($con, $qry2);
            }
            mysqli_free_result($result1);
        }
    }
}
