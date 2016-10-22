<?php
  # php logic file
include('phpFunc.php');
$userInfo = retrieveUser($_SESSION['user']);
$userItems = retrieveUserItems($_SESSION["user"]);

include('header.php');

if (isset($_POST['add_product'])){
	$filename = $_FILES['item_pic']['name'];
	$upload_directory = "uploads/";
	$TargetPath=time().$filename;
	if(move_uploaded_file($_FILES['item_pic']['tmp_name'], $upload_directory.$TargetPath)){
		$result = addUserItem($_POST['item_title'],$_POST['item_description'],$_POST['item_cate'],$upload_directory.$TargetPath);
		if ($result == 0) {
			$errmsg = 'Please try again. :(';
		} else {
			$successmsg ='Please click <a href="my_products.php">here</a> to refresh.';
		}
	}
}

if (isset($_POST['create_auction'])){
	$result = addAuction($_POST['daterange'],$_POST['pick_up'],$_POST['min_price'],$_POST['item_id']);
	if ($result == 0) {
		$errmsg = 'Please try again. :(';
	} else {
		$successmsg ='Please click <a href="my_products.php">here</a> to refresh.';
	}
}


if (isset($_POST['delete_product'])) {
	$result = deleteUserItem($_POST['item_id']);
	if ($result == 0) {
		$errmsg = 'Please try again. :(';
	} else {
		$successmsg ='Please click <a href="my_products.php">here</a> to refresh.';
	}
}

if (isset($_POST['update_product'])) {
	if($_FILES['item_pic']['size']!=0){
		$filename = $_FILES['item_pic']['name'];
		$upload_directory = "uploads/";
		$TargetPath=time().$filename;
		if(move_uploaded_file($_FILES['item_pic']['tmp_name'], $upload_directory.$TargetPath)){
			$result = editUserItem($_POST['item_id'],$_POST['item_title'],$_POST['item_description'],$_POST['item_cate'],$upload_directory.$TargetPath);
			if ($result == 0) {
				$errmsg = 'Please try again. :(';
			} else {
				$successmsg ='Please click <a href="my_products.php">here</a> to refresh.';
			}
		}
	}else{
		$result = editUserItem($_POST['item_id'],$_POST['item_title'],$_POST['item_description'],$_POST['item_cate'],'');
		if ($result == 0) {
			$errmsg = 'Please try again. :(';
		} else {
			$successmsg ='Please click <a href="my_products.php">here</a> to refresh.';
		}
	}
}

if (isset($_POST['cate_button'])){
  if ($_POST['check_list'] != 'All') {
    $userItems = retrieveUserItemsByCategories($_SESSION["user"],$_POST['check_list']);
  } else {
		$userItems = retrieveUserItems($_SESSION["user"]);
	}
}
?>
	<!--header end here-->


	<!--product start here-->
	<div class="product">
		<div class="container">
			<div class="product-main">
				<div class=" product-menu-bar">
					<div class="col-md-3 prdt-right">
						<div class="w_sidebar">
							<section  class="sky-form">
								<h1>Categories</h1>
								<div class="row1 scroll-pane">
									<form name="cate" method='POST'>
										<div class="col col-4">
											<label class="checkbox"><input type="checkbox" name="check_list" value='All'><i></i>All</label>
										</div>
										<div class="col col-4">
											<?php
											$allCategories = getCategories();
											$cat_rows = mysqli_fetch_all($allCategories,MYSQLI_ASSOC);
											foreach($cat_rows as $cat_row) { ?>
												<label class="checkbox"><input type="checkbox" name="check_list" value = "<?php echo $cat_row['CATE']; ?>"><i></i><?php echo $cat_row['CATE']; ?></label>
											<?php } ;?>
											<script>
												$('input[type="checkbox"]').on('change', function() {
													$('input[type="checkbox"]').not(this).prop('checked', false);
												});
											</script>
										</div>
										<button type="submit" class="btn btn-success btn-round" name="cate_button">Confirm</button>
									</form>
								</div>
							</section>
						</div>
					</div>
				</div>
				<div class="col-md-9 product-block">

					<?php
					$userItems = retrieveUserItems($_SESSION["user"]);
					$rows = mysqli_fetch_all($userItems,MYSQLI_ASSOC);
					foreach ($rows as $row) {?>
						<div class="col-md-4 home-grid">
							<div class="home-product-main">
								<div class="home-product-top">
									<button class="imagebtn" data-toggle="modal" data-target="#editProduct_<?php echo $row['PRODUCT_ID'];?>">
										<img id = 'product-img' src="<?php echo $row['PIC'];?>" alt="" class="img-responsive zoom-img" width="233px" height="233px">
									</button>
								</div>
								<div class="home-product-bottom">
									<h3 style="color:white"><?php echo $row['TITLE'] ;?></h3>
									<p><?php echo $row['CATE'] ;?></p>
								</div>
							</div>
						</div>
						<!-- start: modal for create new student -->

						<div class="modal fade" id="editProduct_<?php echo $row['PRODUCT_ID']; ?>" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<h4 class="modal-title" id="myModalLabel">Product Details</h4>
									</div>
									<form id="editProduct" class="form-horizontal" method="POST" enctype="multipart/form-data">
										<div class="modal-body">
											<div class="form-group">
												<label class="col-sm-4 control-label">Product Name</label>
												<div class="col-sm-8 control-label">
													<input name="item_title" type="text" value="<?php echo $row['TITLE'];?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Product Description</label>
												<div class="col-sm-8 control-label">
													<input name="item_description" type="text" value="<?php echo $row['DESCRIPTION'];?>" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Category</label>
												<div class="col-sm-8 control-label">
													<select style='width:100px;' name="item_cate">
														<?php
														$cates = array('Stationery','Electronics','Vehicle','Luxury','Clothes','Furniture');
														foreach ($cates as $cate) {
															if ($row['CATE']==$cate) { ?>
																<option selected value="<?php echo $cate;?>"><?php echo $cate;?></option>
														  <?php }
															else { ?>
																<option value="<?php echo $cate;?>"><?php echo $cate;?></option>
														 <?php }
													  } ?>
														<script>
														$('select').on('change', function (e) {
															var optionSelected = $("option:selected", this);
															var valueSelected = this.value;
														});
														</script>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Picture</label>
												<div class="col-sm-8 control-label">
													<input type="file" name="item_pic"/>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Status</label>
												<div class="col-sm-8 control-label">
													<p><?php echo $row['IS_AVAILABLE'];?></p>
												</div>
											</div>
											<input type="hidden" name="item_id" id="item_id" value="<?php echo $row['PRODUCT_ID'];?>" />

										</div>

										<div class="modal-footer">
											<button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
											<button type="submit" name="update_product" class="btn btn-success btn-round">Update</button>
											<button type="submit" name="delete_product" class="btn btn-success btn-round">Delete</button>
											<button type="button" name="create_auction" class="btn btn-success btn-round" data-dismiss="modal" data-toggle="modal" data-target="#addAuction_<?php echo $row['PRODUCT_ID'];?>">Create an Auction</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div class="modal fade" id="addAuction_<?php echo $row['PRODUCT_ID']; ?>" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<h4 class="modal-title" id="myModalLabel">Create an Auction</h4>
									</div>
									<form id="addAuction" class="form-horizontal" method="POST">
										<div class="modal-body">
											<div class="form-group">
												<label class="col-sm-4 control-label">Product Name</label>
												<div class="col-sm-8 control-label">
													<p><?php echo $row['TITLE'];?> </p>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Min Bidding Points</label>
												<div class="col-sm-8">
													<input name="min_price" required="true" class="form-control"></input>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4">Available Time</label>
												<div class="col-sm-8">
													<input type="text" name="daterange" />
													<script type="text/javascript">
													$('input[name="daterange"]').daterangepicker();
													</script>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Favorable Pickup Point</label>
												<div class="col-sm-8">
													<input name="pick_up" required="true" class="form-control"></input>
												</div>
											</div>
											<input type="hidden" name="item_id" value="<?php echo $row['PRODUCT_ID'];?>" />

											<div class="modal-footer">
												<button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
												<button type="submit" name="create_auction" class="btn btn-success btn-round">Confirm</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

					<?php } ;?>
					<div class="col-md-4 home-grid">
						<div class="home-product-main">
							<div class="home-product-top">
								<button class="imagebtn" data-toggle="modal" data-target="#addProduct">
									<img id = 'product-img' src="images/plus.png" alt="" class="img-responsive zoom-img">
								</button>
							</div>

						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
	</div>
	<!--product end here-->



	<!-- end: modal for create new student -->


	<div class="modal fade" id="addProduct" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Product Details</h4>
				</div>
				<form id="addProduct" class="form-horizontal" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Product Name</label>
							<div class="col-sm-8 control-label">
								<input type="text" name="item_title" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Product Description</label>
							<div class="col-sm-8 control-label">
								<input type="text" name="item_description" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Category</label>
							<div class="col-sm-8 control-label">
								<select style='width:100px;' name="item_cate">
									<option value="Stationery">Stationery</option>
									<option value="Electronics">Electronics</option>
									<option value="Vehicle">Vehicle</option>
									<option value="Luxury">Luxury</option>
									<option value="Clothes">Clothes</option>
									<option value="Furniture">Furniture</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Picture</label>
							<div class="col-sm-8 control-label">
								<input type="file" name="item_pic"/>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
						<button type="submit" name="add_product" class="btn btn-success btn-round">Confirm</button>

					</div>
				</form>
			</div>
		</div>
	</div>
	<!--footer strat here-->
	<?php
# closes the <BODY> and include scripts
	include('footer.php');
	?>
