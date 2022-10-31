<?php
$servername = "sci-mysql";
$dbname = "coa123wdb";
$username = "coa123wuser";
$password = "grt64dkh!@2FD";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
	die("Connection failed: ". mysqli_connect_error());
}

$party_size = $_GET["partySize"];
$catering_grade = $_GET["cateringGrade"];
$date = $_GET["weddingDate"];

$query = "SELECT venue.*, catering.cost FROM venue, catering WHERE venue.venue_id = catering.venue_id and venue.capacity >= $party_size and catering.grade = $catering_grade and venue.venue_id NOT IN (SELECT venue.venue_id FROM venue JOIN venue_booking ON venue.venue_id = venue_booking.venue_id and venue_booking.booking_date = '$date')";
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