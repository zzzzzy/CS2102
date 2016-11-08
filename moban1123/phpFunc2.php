<?php
function search($string){
	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);

	$user = $_SESSION['user'];
	$query = "SELECT a.*, p.*, u.* FROM AUCTIONS a, PRODUCTS p, USERS u WHERE a.product_id = p.product_id AND p.is_available =True AND p.owner_id = u.user_id AND u.user_id <> '".$user."' AND p.title LIKE '%".$string."%'";
	$result = mysqli_query($mysqli, $query);
	return $result;
}

function retrieveAllUserBids() {
//retrieve all bids of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT b.*, p.* , a.* FROM BIDS b, PRODUCTS p, AUCTIONS a WHERE b.product_id=p.product_id AND b.product_id=a.product_id AND b.auctions=a.auction_id";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveUserPendingBids($user) {
//retrieve all bids of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT b.*, p.* , a.* FROM BIDS b, USERS u, PRODUCTS p, AUCTIONS a WHERE b.bidder_id = u.user_id AND b.product_id=p.product_id AND b.product_id=a.product_id AND b.auctions=a.auction_id AND u.user_id= '".$user."' AND b.status='Pending'";
	$result = mysqli_query($mysqli,$query);
	return $result;
}

function retrieveUserCompletedBids($user) {
//retrieve all bids of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT b.*, p.* , a.* FROM BIDS b, USERS u, PRODUCTS p, AUCTIONS a WHERE b.bidder_id = u.user_id AND b.product_id=p.product_id AND b.product_id=a.product_id AND b.auctions=a.auction_id AND u.user_id= '".$user."' AND b.status <>'Pending'";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveAllUserPendingBids() {
//retrieve all bids of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT b.*, p.* , a.* FROM BIDS b, PRODUCTS p, AUCTIONS a WHERE b.product_id=p.product_id AND b.product_id=a.product_id AND b.auctions=a.auction_id AND b.status='Pending'";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

function retrieveAllUserCompletedBids() {
//retrieve all bids of the user

	$host = "localhost";
	$username = "root";
	$password = "";
	$dbname = "cs2102";

	$mysqli = new mysqli($host,$username,$password,$dbname);
	$query =  "SELECT b.*, p.* , a.* FROM BIDS b, PRODUCTS p, AUCTIONS a WHERE b.product_id=p.product_id AND b.product_id=a.product_id AND b.auctions=a.auction_id AND b.status <>'Pending'";
	$result = mysqli_query($mysqli,$query);

	return $result;
}

?>
