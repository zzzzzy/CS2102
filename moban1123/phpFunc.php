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

function logout() {
	$_SESSION["user"] = null;
}

function isAdmin($user){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "SELECT ADMIN FROM USERS u WHERE u.user_id= '".$user."'";
	$result = mysqli_query($mysqli, $query);
	$result_row = mysqli_fetch_all($result,MYSQLI_ASSOC)[0];
	$result_admin = $result_row['ADMIN'];
	if ($result_admin == TRUE){
		return 1;
	}
	else{
		return 0;
	}
}

################## My items ####################

function addUser($user_name, $email, $phone, $user_password, $address) {
// User: Function to add new user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$user_date_joined = date('Y-m-d H:i:s');
	$points = 500;

	$query = "INSERT INTO USERS (user_name, email, phone, password, points, address, date_joined, admin) VALUES (?,?,?,?, $points,?, ('$user_date_joined'),False)";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sssss", $user_name, $email, $phone, $user_password, $address);
	$stmt->execute();
	$stmt->close();
	$result = $mysqli->affected_rows;
	return $result;
}

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


function retrieveAllUserItems() {
// User: Function to retrieve all items of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT * FROM PRODUCTS";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveUserItemsByCategories($user,$cate) {
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT * FROM PRODUCTS p, USERS u WHERE p.owner_id = u.user_id AND u.user_id= '".$user."' AND p.cate='".$cate."'";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

function retrieveAllUserItemsByCategories($cate) {
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT * FROM PRODUCTS p WHERE p.cate='".$cate."'";
	$result = mysqli_query($mysqli, $query);
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

function addUserItem($item_title,$item_description,$item_cate,$item_pic){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$item_ownerid = $_SESSION['user'];

	$query = "INSERT INTO PRODUCTS (title, cate, description, owner_id, is_available, pic) VALUES (?,?,?,$item_ownerid,True,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("ssss", $item_title,$item_cate,$item_description,$item_pic);
	$stmt->execute();
	$stmt->close();
	$result = $mysqli->affected_rows;
	return $result;
}

# inputs are obtained from the modal
function editUserItem($item_id,$item_title,$item_description,$item_cate,$item_pic){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	if($item_pic!=''){
		$query = "UPDATE PRODUCTS SET title='".$item_title."', cate='".$item_cate."', description='".$item_description."', pic = '".$item_pic."' WHERE product_id = ".$item_id."";
	} else {
		$query = "UPDATE PRODUCTS SET title='".$item_title."', cate='".$item_cate."', description='".$item_description."' WHERE product_id = ".$item_id."";
	}

	mysqli_query($mysqli, $query);
	$result = mysqli_affected_rows($mysqli);

	return $result;
}

################## My auctions ####################
function retrieveHighestBid($auction_id){
	## highest bidding points
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	#$query =  "SELECT * FROM AUCTIONS a, PRODUCTS p, BIDS b, USERS u WHERE a.product_id = p.product_id AND p.owner_id = u.user_id AND b.auctions = a.auction_id AND a.auction_id = '".$auction_id."' AND b.product_id = p.product_id AND u.user_id= '".$user."' AND b.points >= (SELECT max(b1.points) FROM AUCTIONS a1, BIDS b1 WHERE b1.auctions = a1.auction_id AND a.auction_id = a1.auction_id)";
	$query =  "SELECT * FROM AUCTIONS a, BIDS b WHERE b.auctions = a.auction_id AND a.auction_id = '".$auction_id."' AND b.points >= (SELECT max(b1.points) FROM AUCTIONS a1, BIDS b1 WHERE b1.auctions = a1.auction_id AND a.auction_id = a1.auction_id)";
	$result = mysqli_query($mysqli,$query);
	return $result;
}

function availableItems($user){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "SELECT * FROM PRODUCTS p, USERS u WHERE p.owner_id = u.user_id AND u.user_id= '".$user."' AND p.is_available = True";
	$result = mysqli_query($mysqli,$query);
	return $result;

}

function addAuction($date_range, $pick_up, $min_price, $product_id){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$string = explode('-',$date_range);

	$starttimestamp = strtotime($string[0]);
	$start_time = date("Y-m-d H:i:s", $starttimestamp);

	$endtimestamp = strtotime($string[1]);
	$end_time = date("Y-m-d H:i:s", $endtimestamp);

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "INSERT INTO AUCTIONS (start_time_avail, end_time_avail, pick_up, min_price, product_id, status) VALUES (?,?,?,?,?,True)";

	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sssii", $start_time, $end_time, $pick_up, $min_price, $product_id);
	$stmt->execute();
	$stmt->close();
	$result = $mysqli->affected_rows;
	return $result;

}

function closeAuction($auction_id){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "UPDATE AUCTIONS SET status = False WHERE auction_id = '".$auction_id."'";

	mysqli_query($mysqli, $query);

}

function retrieveAllAuctions($user){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query =  "SELECT * FROM AUCTIONS a, PRODUCTS p, USERS u WHERE a.product_id = p.product_id AND p.owner_id = u.user_id AND u.user_id= '".$user."'";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveOpenAuctions($user){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query =  "SELECT * FROM AUCTIONS a, PRODUCTS p, USERS u WHERE a.product_id = p.product_id AND p.owner_id = u.user_id AND u.user_id= '".$user."' AND a.status = True";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveAllOpenAuctions(){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query =  "SELECT * FROM AUCTIONS a, PRODUCTS p WHERE a.product_id = p.product_id AND a.status = True";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveClosedAuctions($user){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query =  "SELECT * FROM AUCTIONS a, PRODUCTS p, USERS u WHERE a.product_id = p.product_id AND p.owner_id = u.user_id AND u.user_id= '".$user."' AND a.status = False";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveAllClosedAuctions(){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query =  "SELECT * FROM AUCTIONS a, PRODUCTS p WHERE a.product_id = p.product_id AND a.status = False";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveAllUserAuctions(){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query =  "SELECT * FROM AUCTIONS a, PRODUCTS p WHERE a.product_id = p.product_id";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

################## My Bids ####################

function retrieveBid($bid_id) {

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
//retrieve all bids of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT b.*, p.* , a.* FROM BIDS b, USERS u, PRODUCTS p, AUCTIONS a WHERE b.bidder_id = u.user_id AND b.product_id=p.product_id AND b.product_id=a.product_id AND b.auctions=a.auction_id AND u.user_id= '".$user."'";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function updateBid($bid_id,$point) {
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$bidder_id = $_SESSION['user'];
	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "SELECT * FROM BIDS WHERE bid_id = '".$bid_id."'";
	$initial_bid = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($initial_bid,MYSQLI_ASSOC);
	$initial_bid = $row['POINTS'];

	if ($initial_bid < $point){
		$query = "SELECT * FROM USERS WHERE user_id = '".$bidder_id."'";
		$initial_point = mysqli_query($mysqli,$query);
		$row = mysqli_fetch_array($initial_point,MYSQLI_ASSOC);
		$initial_point = $row['POINTS'];

		if ($initial_point >= $point) {
			$query = "UPDATE BIDS SET points='".$point."' WHERE bid_id = '".$bid_id."'";
			$result = mysqli_query($mysqli,$query);

			if ($result) {
				$current_point = $initial_point - $point;
				$query = "UPDATE USERS SET points='".$current_point."' WHERE user_id = '".$bidder_id."'";
				$result = mysqli_query($mysqli,$query);
				return $result;
			}
		}
	}
	return FALSE;
}


function deleteBid($bid_id) {

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$bidder_id = $_SESSION['user'];
	$mysqli = new mysqli($host,$username,$password,$dbname);

	$query = "SELECT * FROM BIDS WHERE bid_id = '".$bid_id."'";
	$bid = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($bid,MYSQLI_ASSOC);
	$bid = $row['POINTS'];

	$query = "DELETE FROM BIDS WHERE bid_id='".$bid_id."'";
	$result = mysqli_query($mysqli, $query);
	if ($result) {
		$query = "SELECT * FROM USERS WHERE user_id = '".$bidder_id."'";
		$initial_point = mysqli_query($mysqli,$query);
		$row = mysqli_fetch_array($initial_point,MYSQLI_ASSOC);
		$initial_point = $row['POINTS'];

		$current_point = $initial_point + $bid;
		$query = "UPDATE USERS SET points='".$current_point."' WHERE user_id = '".$bidder_id."'";
		mysqli_query($mysqli,$query);

		$affected = mysqli_affected_rows($mysqli);
		return $affected;
	}
}


function addBids($auction_id, $bid_product_id, $bid_points, $date_range, $bid_pickup){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$string = explode('-',$date_range);

	$starttimestamp = strtotime($string[0]);
	$start_time = date("Y-m-d H:i:s", $starttimestamp);

	$endtimestamp = strtotime($string[1]);
	$end_time = date("Y-m-d H:i:s", $endtimestamp);

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$bidder_id = $_SESSION['user'];
	$bid_time_created = date('Y-m-d H:i:s');

	$query = "SELECT * FROM USERS WHERE user_id = '".$bidder_id."'";
	$initial_point = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($initial_point,MYSQLI_ASSOC);
	$initial_point = $row['POINTS'];

	$query_2 = "SELECT * FROM AUCTIONS WHERE auction_id = '".$auction_id."'";
	$result = mysqli_query($mysqli,$query_2);
	$auctions_row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$auctions_start_avail_row = $auctions_row['START_TIME_AVAIL'];
	$auctions_end_avail_row = $auctions_row['END_TIME_AVAIL'];
	$auctions_min_price = $auctions_row['MIN_PRICE'];


	if ($initial_point >= $bid_points && $start_time >= $auctions_start_avail_row && $end_time <= $auctions_end_avail_row && $bid_points >= $auctions_min_price){
		$query = "INSERT INTO BIDS (auctions, bidder_id, product_id, points, time_created, borrow_time, return_time, pickup, status) VALUES (?, $bidder_id, ?, ?, ('$bid_time_created'), ?, ?, ?, 'Pending')";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("iiisss", $auction_id, $bid_product_id, $bid_points, $start_time,$end_time,$bid_pickup);
		$stmt->execute();
		$stmt->close();

		$current_point = $initial_point - $bid_points;
		$query = "UPDATE USERS SET points='".$current_point."' WHERE user_id = '".$bidder_id."'";
		mysqli_query($mysqli,$query);

		$result = $mysqli->affected_rows;
		return $result;
	} else {
		return FALSE;
	}
}
################## All Products ####################

function retrieveAvailProducts() {

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$user = $_SESSION['user'];
	$query = "SELECT a.*, p.*, u.* FROM AUCTIONS a, PRODUCTS p, USERS u WHERE a.product_id = p.product_id AND p.is_available =True AND p.owner_id = u.user_id AND u.user_id <> '".$user."'";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

function getCategories() {
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query = "SELECT DISTINCT CATE FROM PRODUCTS";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

function getProductsFromCategories($cate) {
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query = "SELECT a.*, p.*, u.* FROM AUCTIONS a, PRODUCTS p, USERS u WHERE p.cate = '$cate' AND a.product_id = p.product_id AND p.is_available =True AND p.owner_id = u.user_id";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

## Destroy each session
#session_destroy();

?>
