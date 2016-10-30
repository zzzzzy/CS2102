<?php
	# php logic file
include('phpFunc.php');

if(isset($_POST['Username'])) {

	# User trying to sign up.
	#print_r($_POST['Pwd']);
	$result = addUser($_POST['Username'], $_POST['Email'], $_POST['Phone'], $_POST['Pwd'], $_POST['Address']);

	if($result==0) {
		$errMes = 'Please try again.';}
	else {
 		$_SESSION["signup"] = $result;}

}
  # html <HEAD>, starts <BODY> and top menu of page
include('header.php');
?>


<!DOCTYPE HTML>
<html>
<head>
<title>Sign up</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery-1.11.0.min.js"></script>
<!-- for date range picker -->
<!-- Include Required Prerequisites -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />

<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
<!-- Custom Theme files -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Shoplist Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--Google Fonts-->
<link href='https://fonts.googleapis.com/css?family=Hind:400,500,300,600,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
<!-- //end-smoth-scrolling -->
<!-- the jScrollPane script -->
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
		<script type="text/javascript" id="sourcecode">
			$(function()
			{
				$('.scroll-pane').jScrollPane();
			});
		</script>
<!-- //the jScrollPane script -->
<script src="js/simpleCart.min.js"> </script>
<script src="js/bootstrap.min.js"></script>
</head>
<body>

<!--sign in start here-->
<div class="signin">
	<div class="container">
		<div class="signin-main">
			<h1>Sign up</h1>
<!-- 			<h2>Informations</h2>
 -->			<form action="" method="POST">
				<?php if(isset($_SESSION["signup"])) { ?>
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>Signup Successfully</h4><!-- Message -->
					<?php echo 'Click <a href="login.php">here to login</a>'; ?>
				</div>
				<?php

				} else { ?>
				<h2>Informations</h2>
				<input type="text" placeholder="Username" id="Username" name="Username" required>
				<input type="text" class="no-margin" placeholder="Email" id="Email" name="Email" required>
				<input type="text" placeholder="Phone" id="Phone" name="Phone" required>
				<input type="text" class="no-margin" placeholder="Address" id="Address" name="Address" required>
				<input type="password" placeholder="Password" id="Pwd" name="Pwd" required>
				<input type="password" class="no-margin" placeholder="Confirm Password" required>
				<span class="checkbox1">
				 <label class="checkbox"><input type="checkbox" name="" checked=""><i> </i>i agree terms of use and privacy</label>
			   </span>
				<input type="submit" value="Submit" name = "addUser" href="login.php">
				<?php }?>

			</form>

			<?php if(isset($errMes)) {?>
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>Login Failed</h4><!-- Message -->
					<?php echo $errMes; ?>
				</div>
			<?php }?>
		</div>
	</div>
</div>
<!--sign in end here
<!--footer strat here-->
<div class="footer">
</div>
<!--footer end here-->
</body>
</html>
