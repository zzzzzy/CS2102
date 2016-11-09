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
                      <label class="checkbox"><input type="checkbox" name="check_list" value = "Open"><i></i>Open</label>
                      <label class="checkbox"><input type="checkbox" name="check_list" value = "Close"><i></i>Closed</label>
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
          $rows = mysqli_fetch_all($userAuctions,MYSQLI_ASSOC);
          foreach ($rows as $row) {
          ?>
            <div class="col-md-4 home-grid">
              <div class="home-product-main">
                <div class="home-product-top">
                  <button class="imagebtn" data-toggle="modal" data-target="#auction_all_<?php echo $row['AUCTION_ID'];?>">
                    <img id = 'product-img' src="<?php echo $row['PIC'];?>" alt="" class="img-responsive zoom-img">
                  </button>
                </div>
                <div class="home-product-bottom">
                  <h3 style="color:white"><?php echo $row['TITLE'] ;?></h3>
                  <p>Auction ID: <?php echo $row['AUCTION_ID'] ;?></p>
                </div>
                </div>
              </div>
            </div>

             <div class="modal fade" id="auction_all_<?php echo $row['AUCTION_ID'];?>" role="dialog">
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
                    <p> <?php echo $row['AUCTION_ID'];?></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Name: </label>
                  <div class="col-sm-8 control-label">
                    <p> <?php echo $row['TITLE'];?></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Description: </label>
                  <div class="col-sm-8 control-label">
                    <p> <?php echo $row['DESCRIPTION'];?></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Product Available: </label>
                  <div class="col-sm-8 control-label">
                  <p> <?php echo $row['START_TIME_AVAIL'];?> to
                  <?php echo $row['END_TIME_AVAIL'];?></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Minimum Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> <?php echo $row['MIN_PRICE'];?></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Current Highest Bid: </label>
                  <div class="col-sm-8 control-label">
                    <p> <?php $highest = retrieveHighestBid($row['AUCTION_ID']);
                        $highestBid = mysqli_fetch_array($highest, MYSQLI_ASSOC);
                        echo $highestBid['POINTS'];?></p>
                  </div>
                </div>
                <div class="form-group">
                        <label class="col-sm-4 control-label">Status: </label>
                        <div class="col-sm-8 control-label">
                          <p> <?php if ($row['STATUS']==1){echo 'Open';}
                          else {echo 'Closed';};?>
                        </p>
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







