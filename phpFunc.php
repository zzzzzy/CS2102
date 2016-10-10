<?php
	// Session start
session_start();

	// Set time zone for dates

date_default_timezone_set('Asia/Singapore');

################ DB connection #################

// Connecting to mysql
if(!isset($_SESSION['conn'])) {
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$_SESSION['conn'] = new mysqli($host,$username,$password,$dbname);
			# Not sure what these thing does...
	if(mysqli_connect_errno()) {
		exit('Connect failed: '.mysqli_connect_error());
	}
	
}

// Closing the connection to database
function close_connection() {
	$_SESSION['conn']->close();
}


function login($user, $pwd) {
// Function to authenticate this user
// Prepare statement to match user and password from the database

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

    $query = "SELECT user_id FROM USERS WHERE email =? AND password =?";
   	$stmt = $mysqli->prepare($query);
	if($stmt == FALSE) { die($mysqli->error); }
	$stmt->bind_param("ss", $user, $pwd);

	$stmt->execute();
	$stmt->bind_result($result);
	$stmt->fetch();

	if($result === 1){
		$_SESSION["admin"] = true;
	}else{
		$_SESSION["admin"] = false;
	}

	return $result;
}

function hasLogin() {
// Check if user has already logged in.
	if(isset($_SESSION["user"])) {
		return true;
	} else {
		return false;
	}
}


################## User ####################

function retrieveUser($user) {
// User: Function to retrieve current user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "SELECT * FROM USERS WHERE user_id ='".$user."'";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

function retrieveUserItems($user) {
// User: Function to retrieve all items of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT * FROM PRODUCTS p, USERS u WHERE p.owner_id = u.user_id AND u.user_id= '".$user."'";
	$result = mysqli_query($mysqli,$query);

	return $result;
}


function deleteUserItem($item_id) {
		// Admin: Function to delete the camera

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "DELETE FROM PRODUCTS WHERE product_id=".$item_id."";
	mysqli_query($mysqli, $query);
	$result = mysqli_affected_rows($mysqli);

	return $result;
}

function addUserItem($item_title,$item_description,$item_availability){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$item_ownerid = $_SESSION['user'];

	
	if($item_availability === "true"){
		$query = "INSERT INTO PRODUCTS (title, description, owner_id, is_available) VALUES (?,?,$item_ownerid,True)";
	} else {
		$query = "INSERT INTO PRODUCTS (title, description, owner_id, is_available) VALUES (?,?,$item_ownerid,False)";
	}

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ss", $item_title, $item_description);
	$stmt->execute();
	$stmt->close();
	$result = $mysqli->affected_rows;
	return $result;
}

## Destroy each session
#session_destroy(); 

################## My Bids ####################

function retrieveBid($bid_id) {
// User: Function to retrieve current user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "SELECT * FROM BIDS WHERE bid_id ='".$bid_id."'";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

function retrieveUserBids($user) {
// User: Function to retrieve all items of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT * FROM BIDS b, USERS u WHERE b.bidder_id = u.user_id AND u.user_id= '".$user."'";
	$result = mysqli_query($mysqli,$query);

	return $result;
}


function deleteBid($bid_id) {
		// Admin: Function to delete the camera

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "DELETE FROM BIDS WHERE bid_id=".$bid_id."";
	mysqli_query($mysqli, $query);
	$result = mysqli_affected_rows($mysqli);

	return $result;
}

function addBids($auction_id, $bid_product_id, $bid_points, $bid_borrow_time,$bid_return_time,$bid_pickup){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$bid_bidderid = $_SESSION['user'];
	$bid_time_created = date('Y-m-d H:i:s');

	$query = "INSERT INTO BIDS (auctions, bidder_id, product_id, points, time_created, borrow_time, return_time, pickup) VALUES (?, $bid_bidderid, ?, ?, $bid_time_created, ?, ?, ?)";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("iidsss", $auction_id, $bid_product_id, $bid_points, $bid_borrow_time,$bid_return_time,$bid_pickup);
	$stmt->execute();
	$stmt->close();
	$result = $mysqli->affected_rows;
	return $result;
}

################## All Products ####################

function retrieveAvailProducts() {
// User: Function to retrieve current user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "SELECT a.*, p.title FROM AUCTIONS a, PRODUCT p WHERE a.product_id = p.product_id and status ='open' ";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

?>