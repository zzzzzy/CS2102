<?php
  # php logic file
include('phpFunc.php');
include('phpFunc2.php');
# check if user is logged in
if(!hasLogin()) {
  $errmsg = '<a href="login.php">Login</a> to view the products.';
} else {




}

if (isset($_POST['bid_item'])) {
  $result = addBids($_POST['auction_id'], $_POST['item_id'], $_POST['bidding_points'], $_POST['daterange'],$_POST['bid_pickup_point']);
  if ($result == 0) {
    $errmsg = 'Please try again. :(';
  } else {
    $successmsg ='Please click <a href="user_items.php">here</a> to refresh.';
  }
}
# retrieve all products
$allProducts = retrieveAvailProducts();

if (isset($_POST['check_list'])){
  if ($_POST['check_list'] != 'All') {
    $allProducts = getProductsFromCategories($_POST['check_list']);
  }
}

if (isset($_POST['search_text'])){
  if ($_POST['search_text'] != '') {
    $allProducts = search($_POST['search_text']);
  }
}
  # html <HEAD>, starts <BODY> and top menu of page
include('header.php');
?>

  <!-- start: content -->
  <!-- start: Product Categories -->
  <div class="product">
  	<div class="container">
  		<div class="product-main">
  			  <div class=" product-menu-bar">
  			    	<div class="col-md-3 prdt-right">
  							<div class="w_sidebar">
  								<section  class="sky-form">
  									<h1>Categories</h1>
  									<div class="row1 scroll-pane">
                      <form method="post">
  										<div class="col col-4">
  											<label class="checkbox"><input type="checkbox" name="check_list" value = 'All'><i></i>All</label>
  										</div>
  										<div class="col col-4">
                        <?php
                        $allCategories = getCategories();
                        $rows = mysqli_fetch_all($allCategories,MYSQLI_ASSOC);
                        foreach($rows as $row) { ?>
    											<label class="checkbox"><input type="checkbox" name="check_list" value = "<?php echo $row['CATE']; ?>"><i></i><?php echo $row['CATE']; ?></label>
                        <?php } ;?>
                      <script>
                      $('input[type="checkbox"]').on('change', function() {
                        $('input[type="checkbox"]').not(this).prop('checked', false);
                      });
                      </script>
  										</div>
                      <button type="submit" class="btn btn-success btn-round">Confirm</button>
                    </form>
  									</div>
  								</section>
  							</div>
  						</div>
  			  </div>
          <!-- end: Product Categories -->
          <div class="search">
            <div class="search-text">
                <form method="POST">
                  <input class="serch" name = 'search_text' type="text" value="Search" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}"/>
                </form>
            </div>
          </div>
    <div class="col-md-9 product-block">

    <?php

    $rows = mysqli_fetch_all($allProducts,MYSQLI_ASSOC);
    foreach($rows as $row) { ?>

      <div class="col-md-4 home-grid">
        <div class="home-product-main">
           <div class="home-product-top">
              <button class="imagebtn" data-toggle="modal" data-target="#viewProduct_<?php echo $row['PRODUCT_ID']; ?>">
                <img id = 'product-img' src="<?php echo $row['PIC'];?>" alt="" class="img-responsive zoom-img">
              </button>
           </div>
          <div class="home-product-bottom">
              <h3 style="color:white"><?php echo $row['TITLE'];?></h3>
              <p><?php echo $row['CATE'];?></p>
          </div>
          <div class="srch">
            <span>from <?php echo $row['MIN_PRICE'];?></span>
          </div>
        </div>
        </div>
        <!-- start: modal for create new student -->
    <div class="modal fade" id="viewProduct_<?php echo $row['PRODUCT_ID']; ?>" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Create a Bidding</h4>
              </div>
              <form id="createItem" class="form-horizontal" method="POST">
                <input type="hidden" name="item_id" id="item_id" value="<?php echo $row['PRODUCT_ID'];?>" />
                <input type="hidden" name=" auction_id" id="auction_id" value="<?php echo $row['AUCTION_ID'];?>" />
                <div class="modal-body">
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Product Name</label>
                    <div class="col-sm-8 control-label">
                      <p><?php echo $row['TITLE'];?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Product Description</label>
                    <div class="col-sm-8 control-label">
                      <p><?php echo $row['DESCRIPTION'];?> </p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Owner</label>
                    <div class="col-sm-8 control-label">
                      <p><?php echo $row['USER_NAME'];?> </p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Min Bidding Points</label>
                    <div class="col-sm-8 control-label">
                      <p> <?php echo $row['MIN_PRICE'];?> </p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Available Time</label>
                    <div class="col-sm-8 control-label">
                      <p> <?php echo $row['START_TIME_AVAIL'];?> - <?php echo $row['START_TIME_AVAIL'];?> </p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Favorable Pickup Point</label>
                    <div class="col-sm-8 control-label">
                      <p> <?php echo $row['PICK_UP'];?> </p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Borrow - Return Time</label>
                    <div class="col-sm-8">
                      <input type="text" name="daterange" value=<?php echo $row['START_TIME_AVAIL'];?> - <?php echo $row['START_TIME_AVAIL'];?> />
                      <script type="text/javascript">
                          $('input[name="daterange"]').daterangepicker();
                      </script>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Pickup Point </label>
                    <div class="col-sm-8">
                      <input name="bid_pickup_point" required="true" class="form-control"></input>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">Bidding Points </label>
                    <div class="col-sm-8">
                        <input name="bidding_points" required="true" class="form-control"></input>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="bid_item" class="btn btn-success btn-round">Confirm</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- end: modal for create new student -->
<!--product end here-->
<?php } ;?>


</div>
<div class="clearfix"> </div>
  </div>

</div>
</div>


      <!-- end: content -->



      <?php
  # closes the <BODY> and include scripts
      include('footer.php');
      ?>
