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

<!-- start: content -->
<form class="form-signin" action="" method="POST">
	<?php 
	if(isset($_SESSION["user"])) {
				# User has already successfully logged in
				# Display Alert
		?>
		<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Login Succesfully!</h4><!-- Message -->
			<?php echo 'Click <a href="my_products.php">here to start adding your items</a>'; ?>
		</div>
		<?php

	}
	else {
		?>
		<h2 class="form-inline">Please sign in</h2>
		<div class="form-group">
			<input type="text" class="input-block-level form-control" placeholder="Email" id="Email" name="User" required>
		</div>
		<div class="form-group">
			<input type="password" class="input-block-level form-control" placeholder="Password" id="Password" name="Pwd" required>
		</div>
		<button class="btn btn-large btn-primary" type="submit">Sign in</button>
		<?php if(isset($errMsg)) {?>
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Login Failed</h4><!-- Message -->
			<?php echo $errMsg; ?>
		</div>
		<?php }?>
		<?php } ?>
	</form>


	<!-- end: content --> 

	<?php
  # closes the <BODY> and include scripts
	include('footer.php');
	?>