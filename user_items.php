<?php
  # php logic file
include('phpFunc.php');

# check if user is logged in
if(!hasLogin()) {
  $errmsg = '<a href="login.php">Login</a> to view your bookings.';
} else {

  # retrieve user info
  $userInfo = retrieveUser($_SESSION['user']);
  # retrieve user items
  $userItems = retrieveUserItems($_SESSION["user"]);

  # delete items
  if (isset($_POST['delete_item'])) {
    $result = deleteUserItem($_POST['item_id']); 
    if ($result == 0) {
      $errmsg = 'Please try again. :(';
    } else {
      $successmsg ='Please click <a href="user_items.php">here</a> to refresh.';
    }
  } 
  if (isset($_POST['add_item'])){
    $result = addUserItem($_POST['item_title'],$_POST['item_description'],$_POST['item_availability']);
    if ($result == 0) {
      $errmsg = 'Please try again. :(';
    } else {
      $successmsg ='Please click <a href="user_items.php">here</a> to refresh.';
    }
  }

  
}

  # html <HEAD>, starts <BODY> and top menu of page
include('header.php');
?>

<!-- start: top button & header -->

<?php  

if(isset($userInfo)) {
  #echo mysqli_fetch_array($userInfo,MYSQLI_ASSOC)['USER_NAME'];
  $user = mysqli_fetch_array($userInfo,MYSQLI_ASSOC);
  ?>

    <h2>Items by <?php echo $user['USER_NAME'] ;?></h2>

    <?php 
  };?>
  <?php if (isset($errmsg)){ ;?>
  <div class="alert alert-info" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>Error: </h4><!-- Message -->
    <?php echo $errmsg; ?>
  </div>
  <?php } else if(isset($successmsg)) { ;?>
  <div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>Successful! </h4><!-- Message -->
    <?php echo $successmsg; ?>
  </div>
  <?php }

  ;?>
  <!-- end: top button & header -->

  <!-- start: content -->
  
  <p>All Items</p>  
  <table class="table table-striped table-hover">
    <tr>
      <th>Item ID</th>
      <th>Item Title</th>
      <th>Item Description</th>
      <th>Item Availability</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
    
    <?php

    $rows = mysqli_fetch_all($userItems,MYSQLI_ASSOC);

    foreach($rows as $row) { ?>
      
      <form class='form-horizontal' action="" method="POST">
        <tr>
          <td><?php echo $row['PRODUCT_ID'] ;?><input type="hidden" name="item_id" id="item_id" value="<?php echo $row['PRODUCT_ID'];?>" /></td>
          <td><?php echo $row['TITLE'];?></td>
          <td><?php echo $row['DESCRIPTION'] ;?></td>
          <td><?php echo $row['IS_AVAILABLE'] ;?></td>
          <td><button type="submit" name="edit_item" class="btn btn-sm btn-error btn-round">Edit</button></td>
          <td><button type="submit" name="delete_item" class="btn btn-sm btn-error btn-round">Delete</button></td>
        </tr>
      </form>
    <?php } ;?>

  </table>

  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addItem">Add</button>
      <!-- end: content -->

      <!-- start: modal for create new student -->
  <div class="modal fade" id="addItem" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="myModalLabel">Add Item</h4>
            </div>
            <form id="createItem" class="form-horizontal" method="POST">
              <div class="modal-body">
                <div class="form-group">
                  <label class="col-sm-4 control-label">Title: </label>
                  <div class="col-sm-8">
                    <input name="item_title" required="true" class="form-control"></input>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">Description: </label>
                  <div class="col-sm-8">
                    <input name="item_description" required="true" class="form-control"></input>
                  </div>
                </div>    
                <div class="form-group">
                  <label class="col-sm-4 control-label">Availability: </label>
                  <div class="col-sm-8">
                    <select style='width:80px;' name="item_availability">        
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                  </div>
                </div>                                     
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round" data-dismiss="modal">Cancel</button>
                <button type="submit" name="add_item" class="btn btn-success btn-round">Add Item</button>
              </div>
            </form> 
          </div>
        </div>
      </div>

      <!-- end: modal for create new student -->

      <?php
  # closes the <BODY> and include scripts
      include('footer.php');
      ?>