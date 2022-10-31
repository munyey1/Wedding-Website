<?php
$servername = "sci-mysql";
$dbname = "coa123wdb";
$username = "coa123wuser";
$password = "grt64dkh!@2FD";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
	die("Connection failed: ". mysqli_connect_error());
}

$query = "SELECT count(venue_id) AS count FROM venue_booking GROUP BY venue_id";
$result = mysqli_query($conn, $query);
$allDataArray = array();

if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$allDataArray[] = $row;
	}
}

echo json_encode($allDataArray);
mysqli_close($conn);
?>