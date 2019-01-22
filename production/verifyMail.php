<?php include('assets/config.nic.php');
if (isset($_GET['email']) && !empty($_GET['email']) and isset($_GET['hash']) && !empty($_GET['hash'])) {
    // Verify data
    $email = $_GET['email']; // Set email variable
    $hash = $_GET['hash']; // Set hash variable

    $query="SELECT email, hash, verifyMail FROM st_users WHERE email='".$email."' AND hash='".$hash."' AND verifyMail=0";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 1) {
        // We have a match, activate the account
        $qry1="UPDATE st_users SET verifyMail='1' WHERE email='".$email."' AND hash='".$hash."' AND verifyMail='0'";
        $res1 = mysqli_query($con, $qry1);
        $msg="Your account has been activated, you can now <a href='.'>login here ...</a>";
        $hMsg="Congratulations....";
    //sleep(10);
        //header("location: .");
    } else {
        // No match -> invalid url or account has already been activated.
        $hMsg="Oops...";
        $msg="The url is either invalid or you already have activated your account";
    }
} else {
    // Invalid approach
    $hMsg="Oops...";
    $msg="Invalid approach, please use the link that has been send to your email";
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

    <title>Abricko | Verify Email </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center">
              <h1 class="error-number"><?php echo $hMsg;?></h1>
              <?php echo $msg;?>
              </p>
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  </body>
</html>
