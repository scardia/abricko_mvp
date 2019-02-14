<?php
include('assets/config.nic.php');
$msg="";
/*error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");*/
if (isset($_POST['submit'])) {
    if (($_POST['fname'])!="") {
        $hash = generateRandomString();
        $fName = trim($_POST['fname']);
        $email = trim($_POST['email']);
        $password = trim($_POST['pass']);
        $lName = trim($_POST['lname']);
        $qry = "insert ignore into st_users(user,email,pass,lname,hash) values('".$fName."','".$email."','".$password."','".$lName."','".$hash."')";
        $result = mysqli_query($con, $qry);
        if ($result) {
            $to = $email;
            //$from = 'test@gtechinfo.com';
            $from = 'Abricko Verify Mail <mail@Abricko.com>';
            $headers = "From: " . strip_tags($_POST['req-email']) . "\r\n";
            $headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $subject ="[Abricko.com] Please verify your email address.";
            $message = "<html><body><center>Almost done, <b>".$fName." </b> ! To complete your Abricko sign up, we just need to verify your email address: ".$email."</center><br></br>";
            $message =$message.'<center><a style="background:#0366d6;border-radius:5px;border:1px solid #0366d6;box-sizing:border-box;color:#ffffff;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:10px 20px;text-decoration:none"
                      href="http://app.gtechinfo.com/abricko/production/verifyMail.php?email='.$email.'&hash='.$hash.'" target="_blank">Verify email address</a></center>'."<br></br>";
            $message =$message.'<center><p style="color:#586069!important;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif;font-size:12px!important;font-weight:normal;line-height:1.5;margin:0 0 15px;padding:0;text-align:left">
    	               You’re receiving this email because you recently created a new Abricko account or added a new email address. If this wasn’t you, please ignore this email </p></center></body></html>';

            $ans = mail($to, $subject, $message, $headers);
            if (!$ans) {
                $msg= "Error";
            } else {
                $msg= "<h1>Mail sent Successfully!</h1>";
            }
            $smsg = "User Created Successfully.";
        //$msg="<h1>Mail sent Successfully!</h1>";
        //header("location: login.php");
        } else {
            $msg ="User Registration Failed";
            //echo "<script type='text/javascript'>alert('$fmsg');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Sign Up </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<style>
.login100-form-btn:hover{
	border-radius: 25px;
	background-color: white;
	border: 2px solid #36BBAD;;
	color: #36BBAD;
	cursor: pointer;
}
</style>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" action="" method="post">
					<span class="login100-form-title p-b-26">
						Welcome
					</span>
					<span class="login100-form-title p-b-30">
						<i class="zmdi zmdi-font"></i>
					</span>

					<div class="wrap-input100 validate-input" >
						<input class="input100" type="text" name="fname" placeholder="First Name">
					</div>

					<div class="wrap-input100 ">
						<input class="input100" type="text" name="lname" placeholder="Last Name">
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is: a@b.c">
						<input class="input100" type="text" name="email" placeholder="Email">
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass" placeholder="Password">
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" name="submit">
								Submit
							</button>
						</div>
					</div>
                    <div class="text-center p-t-10">
                        <span class="txt1">
                            <?php echo $msg;?>
                        </span>
                    </div>
					<div class="text-center p-t-30">
						<a class="txt1" href="">
						</a>
						Already have an account?
						<a class="txt2" href="login.php">
							SignIn Here
						</a>
					</div>
 				</form>
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>
