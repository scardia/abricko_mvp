<?php
    require_once('assets/config.nic.php');
    try {
        if (isset($_REQUEST['qry']) && !empty($_REQUEST['qry'])) {
            $action = $_REQUEST['qry'];
        }
        //print_r($_POST);
        if ($action != '') {
            header('Access-Control-Allow-Origin: *'); 
            echo $action();
        } else {
            header('Access-Control-Allow-Origin: *'); 
            echo "Main class does not exists";
        }
    } catch (Exception $e) {
        header('Access-Control-Allow-Origin: *'); 
        echo "Something is wrong! Please try again later!";
    }
?>