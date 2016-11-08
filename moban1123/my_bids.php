
<?php
# php logic file
include('phpFunc.php');
include('phpFunc2.php');
include('header.php');

# check if user is logged in
if (!hasLogin()) {
  $errmsg = '<a href="login.php">Login</a> to view your bids.';?>
  <div class="login">
    <div class="container">
      <div class="signin-main">
        <div class="alert alert-info">
          <?php echo $errmsg;?>
        </div>
      </div>
    </div>
  </div>
  <?php
} else {


  # retrieve user info
  $userInfo = retrieveUser($_SESSION['user']);
  $admin = isAdmin($_SESSION['user']);
  # retrieve user bids
  if ($admin){
    $userBids = retrieveAllUserBids();
  }
  else{
    $userBids = retrieveUserBids($_SESSION['user']);
  }


  if (isset($_POST['cate_button'])) {
    if (isset($_POST['check_list'])) {
      if ($_POST['check_list'] == 'In Progress'){
        if ($admin){
          $userBids = retrieveAllUserPendingBids();
        }
        else{
          $userBids = retrieveUserPendingBids($_SESSION['user']);
        }
      } else if ($_POST['check_list'] == 'Finished'){
        if ($admin){
          $userBids = retrieveAllUserCompletedBids();
        }
        else{
          $userBids = retrieveUserCompletedBids($_SESSION['user']);
        }
      } else if ($_POST['check_list'] == 'All') {
        if ($admin){
          $userBids = retrieveAllUserBids();
        }
        else{
          $userBids = retrieveUserBids($_SESSION['user']);
        }
      }
    }
    
  }


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

  if (isset($_POST['update_bid'])) {
    $result = updateBid($_POST['bid_id'],$_POST['bid_point']);
    if ($result == 0) {
      $errmsg = 'Please try again. :(';
    }
    else {
      $successmsg ='Please click <a href="my_products.php">here</a> to refresh.';
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
                <form method="POST">
                  <h1>All My Bids</h1>
                  <div class="row1 scroll-pane">
                    <div class="col col-4">
                      <label class="checkbox"><input type="checkbox" name="check_list" value = "All"><i></i>All</label>
                    </div>
                    <div class="col col-4">
                      <label class="checkbox"><input type="checkbox" name="check_list" value = "In Progress"><i></i>In Progress</label>
                      <label class="checkbox"><input type="checkbox" name="check_list" value = "Finished"><i></i>Finished</label>
                      <script>
                      $('input[type="checkbox"]').on('change', function() {
                        $('input[type="checkbox"]').not(this).prop('checked', false);
                      });
                      </script>
                      <button type="submit" class="btn btn-success btn-round" name="cate_button">Confirm</button>
                    </form>
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
                  <button class="imagebtn" data-toggle="modal" data-target="#editBid_<?php echo $row['BID_ID'];?>">
                    <img id = 'product-img' src="<?php echo $row['PIC'];?>" alt="" class="img-responsive zoom-img">
                  </button>
                </div>
                <div class="home-product-bottom">
                  <h3 style="color:white"><?php echo $row['TITLE'] ;?></h3>
                  <p>Bidding ID: <?php echo $row['BID_ID'] ;?></p>
                </div>
                <div class="srch">
                  <span> Points: <?php echo $row['POINTS'];?></span>
                </div>
              </div>
            </div>

            <div class="modal fade" id="editBid_<?php echo $row['BID_ID'];?>" role="dialog">
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
                          <p> <?php if ($row['STATUS']==1){echo 'Available';}
                          else {echo 'Not Available';};?>
                        </p>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">My Borrow Period: </label>
                      <div class="col-sm-8 control-label">
                        <p> <?php echo $row['BORROW_TIME'];?> to <?php echo $row['RETURN_TIME'];?></p>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-4 control-label">My Favorable Pickup Point: </label>
                      <div class="col-sm-8 control-label">
                        <p> <?php echo $row['PICKUP'];?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label">My Bidding Points: </label>
                      <div class="col-sm-8 control-label">
                        <input name="bid_point" type="text" value="<?php echo $row['POINTS'];?>" />
                      </div>
                    </div>
                    <input type="hidden" name="bid_id" value="<?php echo $row['BID_ID'];?>" />
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_bid" class="btn btn-success btn-round">Update</button>
                    <button type="submit" name="delete_bid" class="btn btn-success btn-round">Delete</button>
                    <button type="button" name="moreInfo" class="btn btn-success btn-round" data-dismiss="modal" data-toggle="modal" data-target="#more_info_<?php echo $row['BID_ID'];?>">More Product info</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="modal fade" id="more_info_<?php echo $row['BID_ID'];?>" role="dialog">
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
                      <label class="col-sm-4 control-label">Owner ID:</label>
                      <div class="col-sm-8 control-label">
                        <p> <?php echo $row['OWNER_ID'];?></p>
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
          </div>


          <?php } ;?>


          <div class="clearfix"> </div>
        </div>
      </div>
    </div>
  </div>

  <?php } ;?>



  <!--footer strat here-->
<?php
# closes the <BODY> and include scripts
include('footer.php');
?>
