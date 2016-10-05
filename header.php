<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Stuff Sharing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Book photography equipments for free!">
    <meta name="author" content="">

    <!-- start: CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/simple-sidebar.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .topbutton{margin-left:10px;}

        #pull-right{
          margin: 10px;
        }
        body {
          font-family: 'Open Sans', sans-serif;
        }
    </style>
    <!-- end: CSS -->
  </head>

  <body class="metro">
    <div id="wrapper">
      <!-- start: sidebar -->
      <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
          <li class="sidebar-brand">
            <a href="/">EasyShare</a>

          </li>        

          <?php if(isset($_SESSION['user'])) { 
            if ($_SESSION['admin']) { ?>

            <!-- start: admin menu -->
                <li><a href="admin_equipments.php">Manage Equipments</a></li>
                <li><a href="admin_bookings.php">Manage Bookings</a></li>
                <li><a href="admin_users.php">Manage Users</a></li>
                <li><a href="admin_profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            <!-- end: admin menu -->

            <?php } else { ?>

            <!-- start: users menu -->
                <li><a href="products.php">Products in auction</a></li>
                <li><a href="user_items.php">Item list</a></li>
                <li><a href="user_auctions.php">My auctions</a></li>
                <li><a href="user_bid.php">My bid</a></li>
                <li><a href="logout.php">Logout</a></li>
            <!-- end: users menu -->

                <?php } 
            } else { ?>

            <!-- start: non-users menu -->
              <li><a href="register.php">Register</a></li>
              <li><a href="login.php">Login</a></li>
            <!-- end: non-users menu -->

          <?php }?>

        </ul>
      </div>
      <!-- end: sidebar -->
      <div id="page-content-wrapper">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12"> 