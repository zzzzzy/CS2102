<?php
	# php logic file
include('phpFunc.php');

if(isset($_POST['User'])) {
		# User trying to log in.
	$result = login($_POST['User'], $_POST['Pwd']);

	if(!$result) {
		$errMsg = 'Invalid credentials. Please try again.';
	}else{
		$_SESSION["user"] = $result;
	}
}
  # html <HEAD>, starts <BODY> and top menu of page
include('header.php');
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Login</title>
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


<!--log in start here-->
<div class="login">
	<div class="container">
		<form class="login-main" action="" method="POST">
			<?php 
			if(isset($_SESSION["user"])) {
				# User has already successfully logged in
				# Display Alert
			?>
		<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Login Succesfully!</h4><!-- Message -->
			<?php echo 'Click <a href="my_products.php">here to view your items</a>'; ?>
		</div>
		<?php

	} else {
		?>
		<h1>Login</h1>
		  <div class="col-md-6 login-left">
			<h2>Existing User</h2>
			<form>
				<input type="text" placeholder="Email" id="Email" name="User" required>
				<input type="password" placeholder="Password" id="Password" name="Pwd" required>
				<input type="submit" value="Login">
			</form>
			<?php if(isset($errMsg)) {?>
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>Login Failed</h4><!-- Message -->
					<?php echo $errMsg; ?>
				</div>
			<?php }?>
		  </div>
		  <div class="col-md-6 login-right">
		  	 <h3>New User? Create an Account</h3>
		  	 <?php $_SESSION["signup"] = null; ?>
		     <a href="signup.php" class="login-btn">Create an Account </a>
		  </div>
		  <div class="clearfix"> </div>
		  <?php }?>
		</form>
	</div>
</div>
<!--log in end here-->
<!--footer strat here-->
<div class="footer">

</div>
<!--footer end here-->
</body>
</html>


<?php
  # closes the <BODY> and include scripts
	include('footer.php');
?>