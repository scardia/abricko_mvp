<?php
$rootDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
echo $rootDir;
$myUrls=readMyFile( $rootDir."myRegionUrl.txt");
echo $myUrls;
function readMyFile($myFile)
{
    if (file_exists($myFile)==true) {
        $fh = fopen($myFile, 'r');
        if (filesize($myFile)>0) {
            $theData = fread($fh, filesize($myFile));
        }
        fclose($fh);
        return $theData;
    }
}
?>