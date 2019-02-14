<?php
// Start the session
session_start();
$city=$_REQUEST['city'];
include('assets/config.nic.php');
if (isset($_POST['submit'])) { //print_r($_POST['submit']);
    $email = trim($_POST['user']);
    $password = trim($_POST['pass']);
    $qry = "Select * from st_users where email='".$email."' and pass='".$password."' and verifyMail=1";
    //echo $qry;
    $result = mysqli_query($con, $qry);
    $row=mysqli_fetch_array($result, MYSQLI_ASSOC);
    //If username and password exist in our database then create a session.Otherwise echo error.
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user'] = $email; // Initializing Session
        $_SESSION['id']=$row[id];
        header("location: .?city=".$city); // Redirecting To Other Page
    } else {
        $error = "Incorrect username or password / Verify Your Email Address";
        //echo "<script type='text/javascript'>alert('$error');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Abricko Login</title>
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
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
   color: red;
   opacity: 1; /* Firefox */
}
</style>
<body style="background-image:url(images/bgimage.png); background-position: center;
  background-repeat: no-repeat;
  background-size: cover;">

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="post" action="">
					<span class="login100-form-title p-b-08">
						Welcome
					</span>
					<span class="login100-form-title p-b-08">
						to
					</span>
					<span class="login100-form-title p-b-48">
						<span style="border: 1px solid;border-radius: 5px;padding: 3px;background-color: #36BBAD;color: white;font-size: 24px;">ABRICKO</span>
					</span>

					<div class="wrap-input100">
						<input class="input100" type="text" name="user" placeholder="E-Mail Address">
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass" onclick="myFunction()">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass" placeholder="Password" id="Pass">
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" name="submit">
								Login
							</button>
						</div>
					</div>
                    <div class="text-center p-t-10">
                        <span class="txt1">
                            <?php echo $error;?>
                        </span>
                    </div>
					<div class="text-center p-t-50">
						<a class="txt1" href="Password.php">
							Forgot Password
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
	<script>
		function myFunction() {
			var x = document.getElementById("Pass");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>

</body>
</html>
