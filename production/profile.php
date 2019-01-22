<?php
session_start();
include('assets/config.nic.php');
if (!isset($_SESSION['user'])) {
    header("location: login.php");
} else {
    $user = $_SESSION['user'];
    $qry = "SELECT * FROM st_users WHERE email='$user'";
    $result = mysqli_query($con, $qry);
    $row=mysqli_fetch_array($result, MYSQLI_ASSOC);
    $fName=$row[user];
    $lName=$row[lName];
    $email=$row[email];
    $pass=$row[pass];
    $userName= ucwords($row[user]);
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

        <title>Profile</title>

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
                                            <h2>Profile </h2>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <center><span><?php echo $msg;?></span></center>
                                            <br />
                                            <form method="post" action="" data-parsley-validate class="form-horizontal form-label-left">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">First Name
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input value="<?php echo $fName ;?>" type="test" id="first-name" required="required" class="form-control col-md-7 col-xs-12" name="firstName">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Last Name
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input value="<?php echo $lName ;?>" type="text" id="lastName" name="lastName" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input value="<?php echo $email ;?>" type="text" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input value="<?php echo $pass ;?>" id="middle-name" class="form-control col-md-7 col-xs-12" type="Password" name="password">
                                                    </div>
                                                </div>
                                                <div class="ln_solid"></div>
                                                <div class="form-group">
                                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
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
