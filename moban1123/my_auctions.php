<style type="text/css">
  .auction-content {
    padding-top:20px;
  }
  .tab-header {
    color:black;
  }
</style>

<?php
  # php logic file
  include('phpFunc.php');
  include('header.php');
  # check if user is logged in
  if (!hasLogin()) {
    $errmsg = '<a href="login.php">Login</a> to view your auctions.';?>
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
    # retrieve user items
    
    if (isset($_POST['close_auction'])) {
      $result = closeAuction($_POST['auction_id']);
      if ($result == 0) {
        $errmsg = 'Please try again. :(';
      } else {
        $successmsg ='Please click <a href="my_auctions.php">here</a> to refresh.';
      }
    }

    if ($admin) {
      $userAuctions = retrieveAllUserAuctions();
    } else {
      $userAuctions = retrieveAllAuctions($_SESSION["user"]);
    }
    if (isset($_POST['cate_button'])){
      if (isset($_POST['check_list'])) {
        if ($admin) {
        if ($_POST['check_list'] == 'All') {
          $userAuctions = retrieveAllUserAuctions();
        } else if ($_POST['check_list'] == 'Open') {
          $userAuctions = retrieveAllOpenAuctions();
        } else if ($_POST['check_list'] == 'Close') {
          $userAuctions = retrieveAllClosedAuctions();
        } else {
          echo "Error!";
        }
      } else {
        if ($_POST['check_list'] == 'All') {
          $userAuctions = retrieveAllAuctions($_SESSION["user"]);
        } else if ($_POST['check_list'] == 'Open') {
          $userAuctions = retrieveOpenAuctions($_SESSION["user"]);
        } else if ($_POST['check_list'] == 'Close') {
          $userAuctions = retrieveClosedAuctions($_SESSION["user"]);
        } else {
          echo "Error!";
        }
      }
      }
      
    }

?>

<!--product start here-->
<div class="auctions" style="padding-top:95px">
	<div class="container">
		<div class="product-main">
      <div class=" product-menu-bar">
        <div class="col-md-3 prdt-right">
          <div class="w_sidebar">
            <section  class="sky-form">
              <h1>Status</h1>
              <div class="row1 scroll-pane">
                <form name="cate" method='POST'>
                    <div class="col col-4">
                      <label class="checkbox"><input type="checkbox" name="check_list" value='All'><i></i>All</label>
                      <label class="checkbox"><input type="checkbox" name="check_list" value='Open'><i></i>Open</label>
                      <label class="checkbox"><input type="checkbox" name="check_list" value='Close'><i></i>Close</label>
                    </div>
                    <div class="col col-4">

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
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane fade in active auction-content" style="padding-top:0px !important" id="A">
                 <?php
                   $rows = mysqli_fetch_all($userAuctions, MYSQLI_ASSOC);
                   print_content_all($rows);?>
        </div>
            <!-- start: modal for create new student -->

            <!-- <div class="modal fade" id="editProduct_<?php echo $row['PRODUCT_ID']; ?>" role="dialog">
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
                            $cates = array('Tool', 'Appliance', 'Furniture', 'Book');
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
                      <?php
                      if (ableToDelete($row['PRODUCT_ID'])) { ?>
                        <button type="submit" name="delete_product" class="btn btn-success btn-round">Delete</button>
                      <?php } ?>
                     <!--  <?php
                      if (!hasAuction($row['PRODUCT_ID'])) { ?>
                        <button type="button" name="create_auction" class="btn btn-success btn-round" data-dismiss="modal" data-toggle="modal" data-target="#addAuction_<?php echo $row['PRODUCT_ID'];?>">Create an Auction</button>
                      <?php } ?> -->
                    </div>
                  </form>
                </div>
              </div>
            </div> -->

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
          <!-- <div class="col-md-4 home-grid">
            <div class="home-product-main">
              <div class="home-product-top">
                <button class="imagebtn" data-toggle="modal" data-target="#addProduct">
                  <img id = 'product-img' src="images/plus.png" alt="" class="img-responsive zoom-img">
                </button>
              </div>
            </div>
          </div> -->
          <div class="clearfix"> </div>
        </div>
  		</div>
		</div>
	</div>
</div>

<!--product end here-->

<?php
#};
function print_content_all($rows) {
  foreach($rows as $row) {
    echo '
      <div class="col-md-4 home-grid">
        <div class="home-product-main">
          <div class="home-product-top">
            <button class="imagebtn" data-toggle="modal" data-target="#auction_all_'. $row['AUCTION_ID'] .'" >
              <img id = "product-img" src="'. $row['PIC'] .'" alt="" class="img-responsive zoom-img">
            </button>
          </div>
          <div class="home-product-bottom">
            <h3 style="color:white">'. $row['TITLE'] .'</h3>
            <p> '. $row['CATE'] .' </p>';
    echo '
          </div>
        </div>
      </div>
    ';
    $highest = retrieveHighestBid($row['AUCTION_ID']);
    $highestBid = mysqli_fetch_array($highest, MYSQLI_ASSOC);
    print_modal_all($row,$highestBid);
  }
  echo '
      <div class="clearfix"></div>
  ';
}
function print_content_open($rows) {
  foreach($rows as $row) {
    echo '
      <div class="col-md-4 home-grid">
        <div class="home-product-main">
          <div class="home-product-top">
            <button class="imagebtn" data-toggle="modal" data-target="#auction_open_'. $row['AUCTION_ID'] .'" >
              <img id = "product-img" src="'. $row['PIC'] .'" alt="" class="img-responsive zoom-img">
            </button>
          </div>
          <div class="home-product-bottom">
            <h3 style="color:white">'. $row['TITLE'] .'</h3>
            <p> '. $row['CATE'] .' </p>';
    echo '
          </div>
        </div>
      </div>
    ';
    $highest = retrieveHighestBid($row['AUCTION_ID']);
    $highestBid = mysqli_fetch_array($highest, MYSQLI_ASSOC);
    print_modal_open($row,$highestBid);
  }
  echo '
      <div class="clearfix"></div>
  ';
}
function print_content_close($rows) {
  foreach($rows as $row) {
    echo '
      <div class="col-md-4 home-grid">
        <div class="home-product-main">
          <div class="home-product-top">
            <button class="imagebtn" data-toggle="modal" data-target="#auction_close_'. $row['AUCTION_ID'] .'" >
              <img id = "product-img" src="'. $row['PIC'] .'" alt="" class="img-responsive zoom-img">
            </button>
          </div>
          <div class="home-product-bottom">
            <h3 style="color:white">'. $row['TITLE'] .'</h3>
            <p> '. $row['CATE'] .' </p>';
    echo '
          </div>
        </div>
      </div>
    ';
    $highest = retrieveHighestBid($row['AUCTION_ID']);
    $highestBid = mysqli_fetch_array($highest, MYSQLI_ASSOC);
    print_modal_close($row,$highestBid);
  }
  echo '
      <div class="clearfix"></div>
  ';
}
function print_modal_all($row,$highestBid) {
  echo '
    <div class="modal fade" id="auction_all_'. $row['AUCTION_ID'] .'" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="myModalLabel">Auction Details</h4>
            </div>
            <form id="createItem" class="form-horizontal" method="POST">
              <div class="modal-body">
                <div class="form-group">
                  <label class="col-sm-4 control-label">Auction ID: </label>
                  <div class="col-sm-8 control-label">
                    <p> '. $row['AUCTION_ID'] .' <input type="hidden" name="auction_id" id="auction_id" value="'. $row['AUCTION_ID'].'" /></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Name: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['TITLE'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Description: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['DESCRIPTION'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Available: </label>
                  <div class="col-sm-8 control-label">
                  <p> '.$row['START_TIME_AVAIL'].'to
                  '. $row['END_TIME_AVAIL'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Minimum Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['MIN_PRICE'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Current Highest Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$highestBid['POINTS'].'</p>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
                <button type="submit" name="close_auction" class="btn btn-success btn-round">Close Auction</button>
              </div>
            </form>
          </div>
        </div>
      </div>
  ';
}
function print_modal_open($row,$highestBid) {
  echo '
    <div class="modal fade" id="auction_open_'. $row['AUCTION_ID'] .'" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="myModalLabel">Auction Details</h4>
            </div>
            <form id="createItem" class="form-horizontal" method="POST">
              <div class="modal-body">
                <div class="form-group">
                  <label class="col-sm-4 control-label">Auction ID: </label>
                  <div class="col-sm-8 control-label">
                    <p> '. $row['AUCTION_ID'] .' <input type="hidden" name="auction_id" id="auction_id" value="'. $row['AUCTION_ID'].'" /></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Name: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['TITLE'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Description: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['DESCRIPTION'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Available: </label>
                  <div class="col-sm-8 control-label">
                  <p> '.$row['START_TIME_AVAIL'].'to
                  '. $row['END_TIME_AVAIL'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Minimum Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['MIN_PRICE'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Current Highest Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$highestBid['POINTS'].'</p>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
                <button type="submit" name="close_auction" class="btn btn-success btn-round">Close Auction</button>
              </div>
            </form>
          </div>
        </div>
      </div>
  ';
}
function print_modal_close($row,$highestBid) {
  echo '
    <div class="modal fade" id="auction_close_'. $row['AUCTION_ID'] .'" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="myModalLabel">Auction Details</h4>
            </div>
            <form id="createItem" class="form-horizontal" method="POST">
              <div class="modal-body">
                <div class="form-group">
                  <label class="col-sm-4 control-label">Auction ID: </label>
                  <div class="col-sm-8 control-label">
                    <p> '. $row['AUCTION_ID'] .' <input type="hidden" name="auction_id" id="auction_id" value="'. $row['AUCTION_ID'].'" /></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Name: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['TITLE'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Description: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['DESCRIPTION'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Available: </label>
                  <div class="col-sm-8 control-label">
                  <p> '.$row['START_TIME_AVAIL'].'to
                  '. $row['END_TIME_AVAIL'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Minimum Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$row['MIN_PRICE'].'</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Current Highest Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> '.$highestBid['POINTS'].'</p>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
  ';
}
?>
