<?php
session_start();
include('assets/config.nic.php');
if (!isset($_SESSION['user'])) {
    header("location: login.php");
} else {
    $query = "SELECT user FROM st_users where email='".$_SESSION['user']."'";
    $result = mysqli_query($con, $query);
    $row=mysqli_fetch_array($result, MYSQLI_ASSOC);
    $userName= ucwords($row[user]);
}
if (isset($_POST['submit'])) { //print_r($_POST['submit']);
    $user = $_SESSION['user'];
    $password = $_POST['pass'];
    $newPass = $_POST['newPass'];
    $confNewPass = $_POST['confNewPass'];
    $qry = "SELECT user,pass FROM st_users WHERE email='$user'";
    $result = mysqli_query($con, $qry);
    $row=mysqli_fetch_array($result, MYSQLI_ASSOC);
    echo $userName= ucwords($row[user]);
    if (!$result) {
        $msg= "The username you entered does not exist";
    } elseif ($password!= $row[pass]) {
        $msg= "You entered an incorrect password";
    }
    if ($newPass==$confNewPass) {
        $qry2 ="UPDATE st_users SET pass='$newPass' where email='$user'";
        $res1 = mysqli_query($con, $qry2);
    }
    if ($res1) {
        $msg="<font color='#169F85'>Congratulations You have successfully changed your password</font>";
    } else {
        $msg= "Passwords do not match";
    }
}

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="images/favicon.ico" type="image/ico" />

        <title>Change Password</title>

        <!-- Bootstrap -->
        <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
        <!-- iCheck -->
        <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">

        <!-- bootstrap-progressbar -->
        <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
        <!-- JQVMap -->
        <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
        <!-- bootstrap-daterangepicker -->
        <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="../build/css/custom.min.css" rel="stylesheet">

        <body class="nav-md">
            <div class="container body">
                <div class="main_container">
                    <!-- sidebar menu -->
                        <?php include('sidebar.php');?>
                    <!-- /sidebar menu -->

                    <!-- page content -->
                    <div class="right_col" role="main">
                        <div class="">
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>Change Password </h2>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <center><span><?php echo $msg;?></span></center>
                                            <br />
                                            <form method="post" action="" data-parsley-validate class="form-horizontal form-label-left">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Current Password<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="Password" id="first-name" required="required" class="form-control col-md-7 col-xs-12" name="pass">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">New Password<span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="Password" id="last-name" name="newPass" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password<span class="required">*</span></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input id="middle-name" class="form-control col-md-7 col-xs-12" type="Password" name="confNewPass">
                                                    </div>
                                                </div>
                                                <div class="ln_solid"></div>
                                                <div class="form-group">
                                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                        <button class="btn btn-primary" type="reset" name="reset">Reset</button>
                                                        <button type="submit" class="btn btn-success" name="submit">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

            <!-- jQuery -->
            <script src="../vendors/jquery/dist/jquery.min.js"></script>
            <!-- Bootstrap -->
            <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
            <!-- FastClick -->
            <script src="../vendors/fastclick/lib/fastclick.js"></script>
            <!-- Custom Theme Scripts -->
            <script src="../build/js/custom.min.js"></script>
            <!-- PNotify -->

        </body>

    </html>
