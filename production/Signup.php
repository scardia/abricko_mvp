<?php
include('assets/config.nic.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

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
        if ($email!='') {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			$id = getRecordByID("id", "st_users", "where email='".$email."'");
			if ($id=="") {
								$curDate=date("Y-m-d");
				$qry = "insert ignore into st_users(user,email,pass,lname,hash,validityStart) values('".$fName."','".$email."','".$password."','".$lName."','".$hash."','".$curDate."')";
				$result = mysqli_query($con, $qry);
                $msg = "Account Created Successfully.";
                //From email address and name

                $message = "<html><body>Welcome to Abricko   <b>".$fName." </b> ! <br >Thank you for joining our growing community of over 2000 smart real
                    estate professionals who are using data every day to make better decisions.<br></br>";
                    $message =$message.''."<br></br> This is  your account details:<br> Username: ".$email."<br> Password: ".$password."<br>";
                    $message =$message.'<p style="color:#586069!important;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif;font-size:12px!important;font-weight:normal;line-height:1.5;margin:0 0 15px;padding:0;text-align:left">
                    You can always log in to your account on www.abricko.com and change your password when you like.</p></></body></html>';
                
                $mail = new PHPMailer;
				
		//Enable SMTP debugging. 
		$mail->SMTPDebug = 3; 	
				
		$mail->isSMTP();    

		//Set SMTP host name  

		$mail->Host = "smtp.abricko.com";
		//Set this to true if SMTP host requires authentication to send email
		$mail->SMTPAuth = true;                          
		//Provide username and password     
		$mail->Username = "AKIAI3ZOJ5VTR4C6Y45Q";                 
		$mail->Password = "BJey8kTGnXPUAMCfF75ee+zRhGqIK2Rq3Q+t/jBh36AH";                           
		//If SMTP requires TLS encryption then set it
		$mail->SMTPSecure = "tls";                           
		//Set TCP port to connect to 
		$mail->Port = 465;  
				
                $mail->From = "contact@abricko.com";
                $mail->FromName = "Abricko";

                //To address and name
                //$mail->addAddress($email);
                $mail->addAddress("test@gtechinfo.com"); //Recipient name is optional
                //$mail->addAddress("dsrp001@gmail.com");
                //Address to which recipient will reply
                $mail->addReplyTo("contact@abricko.com", "Reply");

                //CC and BCC
                //$mail->addCC("cc@example.com");
                //$mail->addBCC("bcc@example.com");

                //Send HTML or Plain Text email
                $mail->isHTML(true);

                $mail->Subject = "[Abricko.com] Welcome to Abricko.";
                $mail->Body = $message;
                $mail->AltBody = "This is the plain text version of the email content";
                $to = $email;
                $mail->isHTML(true);
                if(!$mail->send()){
                    //$msg = "Mailer Error: " . $mail->ErrorInfo;
                } 
                else{
                    $msg = "Message has been sent successfully";
                }
			}else{
				$msg ="Email already Existed,Please Register with new Email";
			}
			//$msg="<h1>Mail sent Successfully!</h1>";
			//header("location: login.php");
		}else{
			$msg ="Please Provide Correct Email for Registration ";
		}
        } else {
            $msg ="Please Provide Email for Registration ";
            //echo "<script type='text/javascript'>alert('$fmsg');</script>";
        }
    }else{
		$msg ="Please Provide Name for Registration ";
	}
}

function getRecordByID($fld, $tbl, $wh)
{
    global $con;
    $qry = 'select '.$fld.' from '.$tbl.' '.$wh;
    //$result = mysql_query($qry);
    $result = mysqli_query($con, $qry);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        return $row[0];
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
						<span class="btn-show-pass" onclick="myFunction()">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass" placeholder="Password" id="Pass">
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
