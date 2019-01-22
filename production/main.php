<?php
	require_once('assets/config.nic.php');
    try {
        if (isset($_REQUEST['qry']) && !empty($_REQUEST['qry'])) {
            $action = $_REQUEST['qry'];
        }
        //print_r($_POST);
        if ($action != '') {
            echo $action();
        } else {
            echo "Main class does not exists";
        }
    } catch (Exception $e) {
        echo "Something is wrong! Please try again later!";
    }
?>