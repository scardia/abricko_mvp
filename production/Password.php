<?php
include('assets/config.nic.php');
$msg='';
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $code = generateRandomString();
    $query = "SELECT user,email,pass FROM st_users where email='".$email."'";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) == 1) {
        $qry2 = "update st_users set pass='".$code."' where email ='".$email."'";
        $res1 = mysqli_query($con, $qry2);
        $to = $email;
        $from = 'test@gtechinfo.com>';
        $subject ="Password changed confirmation";
        $message = "You have successfully changed your password:\n\nYour new Password is: '".$code."'";
        $headers = "From: ".$from;
        $ans = @mail($to, $subject, $message, $headers);
        $msg = "Password Changed Successfully.<br>Check Your Email to get new password!";
    }
    //}
    else {
        $msg = "Your provided email is not matched with the registered email!";
    }
    echo "<script type='text/javascript'>document.getElementById('msg').innerHTML('New');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Forgot Password</title>
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
				<form class="login100-form validate-form" method="post" action="">
					<span class="login100-form-title p-b-26">
						Forgot Your Password ?
					</span>
					<span class="login100-form-title" style="font-size:17px;";>
						Don't worry we'll get you set back up
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is: a@b.c">
						<input class="input100" type="text" name="email" style="margin-top:50px;" placeholder="Email">
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" name="submit">
								Submit
							</button>
						</div>
					</div>
					<div class="text-center">
						<p id="msg">

						</p>
					</div>
					<div class="text-center p-t-50">
						<a class="txt1" href="index.php">
							Sign In
						</a>
						Or
						<a class="txt2" href="Signup.php">
							Create Account
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
