
<?php
  # php logic file
  include('phpFunc.php');
  include('header.php');

  # check if user is logged in
  if (!hasLogin()) {
    $errmsg = '<a href="login.php">Login</a> to view your bids.';
  } else {
    # retrieve user info
    $userInfo = retrieveUser($_SESSION['user']);
    # retrieve user bids
    $userBids = retrieveUserBids($_SESSION['user']);
    $rows = mysqli_fetch_all($userBids,MYSQLI_ASSOC);

    if (isset($_POST['delete_bid'])) {
    	$result = deleteBid($_POST['bid_id']);
    	if ($result == 0) {
    		$errmsg = 'Please try again. :(';
    	}
    	else {
    		$successmsg ='Please click <a href="my_bids.php">here</a> to refresh.';
    	}
    }

    #NO FUNCTION FOR UPDATING BID
    if (isset($_POST['change_bid'])) {
    	$result = updataBID($_POST['daterange'],$_POST['pickup_point'],$_POST['bid_point']);
    	if ($result == 0) {
    		$errmsg = 'Please try again. :(';
    	} 
    	else {
    		$successmsg ='Please click <a href="my_products.php">here</a> to refresh.';
    	}
    }
}
?>

	<!--product start here-->
<div class="product">
	<div class="container">
		<div class="product-main">
			  <div class=" product-menu-bar">
			    	<div class="col-md-3 prdt-right">
							<div class="w_sidebar">
								<section  class="sky-form">
									<h1>All My Bids</h1>
									<div class="row1 scroll-pane">
										<div class="col col-4">
											<label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>All</label>
										</div>
										<div class="col col-4">
											<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>In Progress</label>
											<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Finished</label>
										</div>
									</div>
								</section>
							</div>
						</div>
			  </div>
			  <div class="col-md-9 product-block">

			  	<?php
					foreach ($rows as $row) {?>
					<div class="col-md-4 home-grid">
						<div class="home-product-main">
							<div class="home-product-top">
								<button class="imagebtn" data-toggle="modal" data-target="#addItem">
									<img id = 'product-img' src="images/h6.jpg" alt="" class="img-responsive zoom-img">
								</button>
							</div>
							<div class="home-product-bottom">
								<h3 style="color:white"><?php echo $row['TITLE'] ;?></h3>
								<p>Bidding ID: <?php echo $row['BID_ID'] ;?></p>
							</div>
							<div class="srch">
								<span>25 pts</span>
							</div>
						</div>
					</div>
					<?php } ;?>


			      <div class="clearfix"> </div>
			  </div>
		</div>
	</div>
</div>


     <!--product end here-->

     <!-- start: modal for updata bid -->
  <?php
  foreach ($rows as $row) {?>
  <div class="modal fade" id="addItem" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="bidDetails">Bids Details</h4>
            </div>
            <form id="createItem" class="form-horizontal" method="POST">
              <div class="modal-body">
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Name: </label>
                  <div class="col-sm-8 control-label">
	                  <p><?php echo $row['TITLE'];?></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Bid ID: </label>
                  <div class="col-sm-8 control-label">
	                  <p> <?php echo $row['BID_ID'];?></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Status: </label>
                  <div class="col-sm-8 control-label">
	                  <p> <?php echo $row['STATUS'];?></p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">My Borrow Period: </label>
                  <div class="col-sm-8 control-label">
	                  <p> <?php echo $row['BORROW_TIME'];?> to <?php echo $row['RETURN_TIME'];?></p>
                  </div>
                </div>

                <div class="form-group">
					<label class="col-sm-4">Change Borrow Period</label>
					<div class="col-sm-8">
						<input type="text" name="daterange" />
						<script type="text/javascript">
						$('input[name="daterange"]').daterangepicker();
						</script>
					</div>
				</div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">My Favorable Pickup Point: </label>
                  <div class="col-sm-8 control-label">
	                  <input name="pickup_point" type="text" value="<?php echo $row['BPICKUP'];?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">My Bidding Points: </label>
                  <div class="col-sm-8 control-label">
	                  <input name="bid_point" type="text" value="<?php echo $row['BPOINTS'];?>" />
                  </div>
                </div> 
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
                <button type="submit" name="change_bid" class="btn btn-success btn-round">Update</button>
                <button type="submit" name="delete_bid" class="btn btn-success btn-round">Delete</button>
                <button type="button" name="moreInfo" class="btn btn-success btn-round" data-dismiss="modal" data-toggle="modal" data-target="#more_info">More Product info</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php } ;?>

      <!-- end: modal for create update bids -->
      <!--start: modal fro more Product info -->

      <?php
      foreach ($rows as $row) {?>
            <div class="modal fade" id="more_info" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" id="myModalLabel">Product Details</h4>
								</div>
								<form id="addAuction" class="form-horizontal" method="POST">
									<div class="modal-body">
										<div class="form-group">
											<label class="col-sm-4 control-label">Product Name</label>
											<div class="col-sm-8 control-label">
												<p> <?php echo $row['TITLE'];?> </p>
											</div>
										
										</div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Product ID: </label>
                                            <div class="col-sm-8 control-label">
	                                            <p> <?php echo $row['PRODUCT_ID'];?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
											<label class="col-sm-4 control-label">Owner</label>
											<div class="col-sm-8 control-label">
												<p> <?php echo $row['USER_NAME'];?> (ID: <?php echo $row['USER_ID'];?>)</p>
											</div>
										</div>
                                        <div class="form-group">
											<label class="col-sm-4 control-label">Minimum Bidding Points</label>
											<div class="col-sm-8 control-label">
												<p> <?php echo $row['MIN_PRICE'];?></p>
											</div>
										</div>
                                        <div class="form-group">
											<label class="col-sm-4 control-label">Available Time</label>
											<div class="col-sm-8 control-label">
												<p> <?php echo $row['START_TIME_AVAIL'];?> to <?php echo $row['END_TIME_AVAIL'];?></p>
											</div>
                                        </div>
                                        <div class="form-group">
											<label class="col-sm-4 control-label">Owner's Favorable Pickup Point</label>
											<div class="col-sm-8 control-label">
												<p> <?php echo $row['PICK_UP'];?></p>
											</div>
                                        </div>
                                        <div class="form-group">
											<label class="col-sm-4 control-label">Product Description</label>
											<div class="col-sm-8 control-label">
												<p> <?php echo $row['DESCRIPTION'];?></p>
											</div> 
										</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-default btn-round" data-dismiss="modal">Close</button>
										<button type="button" name="moreInfo" class="btn btn-success btn-round" data-dismiss="modal" data-toggle="modal" data-target="#addItem">Back</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php } ;?>
				</div>  


<!--footer strat here-->
	<?php
# closes the <BODY> and include scripts
	include('footer.php');
	?>
