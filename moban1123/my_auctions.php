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

  # check if user is logged in
  if (!hasLogin()) {
    $errmsg = '<a href="login.php">Login</a> to view your auctions.';
  } else {
    # retrieve user info
    $userInfo = retrieveUser($_SESSION['user']);
    $admin = isAdmin($_SESSION['user']);
    # retrieve user items
    if ($admin){
      $userAuctions = retrieveAllUserAuctions($_SESSION["user"]);
      $openAuctions = retrieveAllOpenAuctions($_SESSION["user"]);
      $closeAuctions = retrieveAllClosedAuctions($_SESSION["user"]);
    }
    else{
      $userAuctions = retrieveAllAuctions($_SESSION["user"]);
      $openAuctions = retrieveOpenAuctions($_SESSION["user"]);
      $closeAuctions = retrieveClosedAuctions($_SESSION["user"]);
    }
    if (isset($_POST['close_auction'])) {
      $result = closeAuction($_POST['auction_id']);
      if ($result == 0) {
        $errmsg = 'Please try again. :(';
      } else {
        $successmsg ='Please click <a href="my_auctions.php">here</a> to refresh.';
      }
    }
  }
  include('header.php');
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
                    <label class="checkbox"><i></i><a class="tab-header" href="#A" data-toggle="tab">All</a></label>
                  </div>
                  <div class="col col-4">
                    <label class="checkbox"><i></i><a class="tab-header" href="#B" data-toggle="tab">Open</a></label>
                  </div>
                  <div class="col col-4">
                    <label class="checkbox"><i></i><a class="tab-header" href="#C" data-toggle="tab">Close</a></label>
                  </div>
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
                  print_content_all($rows);
                 ?>
            </div>
            <div class="tab-pane fade in auction-content" style="padding-top:0px !important" id="B">
             <?php
                  $rows = mysqli_fetch_all($openAuctions, MYSQLI_ASSOC);
                  print_content_open($rows);
              ?>
            </div>
            <div class="tab-pane fade in auction-content" style="padding-top:0px !important" id="C">
              <?php
                  $rows = mysqli_fetch_all($closeAuctions, MYSQLI_ASSOC);
                  print_content_close($rows);
               ?>
            </div>
        </div>
  		</div>
		</div>
	</div>
</div>

<!--product end here-->

<?php
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
