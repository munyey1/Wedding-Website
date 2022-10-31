<!DOCTYPE html>
<html lang="en">
<head>
	<title>Wedding Venues</title>
	<meta charset = "utf-8" name = "viewport" content = "width=device-width, initial-scale = 1.0">
	<link href = "css/search.css" rel = "stylesheet" type = "text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="js/jquery-3.6.0.min.js"></script>
	
	<script>
	$(document).ready(function(){
		const venueCount = [];
		$.ajax({
			url: "venueCount.php",
			type: "GET",
			success: function(responseData){
				let len = responseData.length;
				for(let i = 0; i < len; i++){
					venueCount[i] = responseData[i].count;
				}
			},
			error: function(xhr, status, error){
					console.log(xhr.status + ";" + xhr.statusText);
			},
			dataType: "json"
		});
		$("#myform").submit(function(event){
			event.preventDefault();
			let venueDate = new Date($("#weddingDate").val());
			let day = venueDate.toLocaleDateString('en-GB', {weekday: 'long', day: 'numeric', month: 'long'});
			$.ajax({
				url: "searchDB.php",
				type: "GET",
				data: $("#myform").serialize(),
				success: function(responseData){
					let len = responseData.length;
					$("#dateStr").empty();
					if(len == 0){
						$("#dateStr").append("There are no venues with that search criteria.");
					}else{
						$("#dateStr").append("Here are the venue(s) on " + day + " with the search criteria.");
					}
					$("#1st").empty();
					$("#2nd").empty();
					$("#3rd").empty();					
					for(let i = 0; i < len; i ++){
						let name = responseData[i].name;
						let capacityText = "<p class = 'card-text'>Capacity: " + responseData[i].capacity + "</p>"
						let licensed = (responseData[i].licensed == 0 ? "No" : "Yes");
						let licensedText = "<p class = 'card-text'>Licensed: " + licensed + "</p>"
						let costPerPersonText = "<p class = 'card-text'>Catering Cost Per Person: £" + responseData[i].cost + "</p>"
						let weekendPriceText = "<p class = 'card-text'>Weekend Price: £" + responseData[i].weekend_price + "</p>"
						let weekdayPriceText = "<p class = 'card-text'>Weekday Price: £" + responseData[i].weekday_price + "</p>"
						if(venueDate.getDay() == 6 || venueDate.getDay() == 0){
							var totalPrice = parseInt(responseData[i].weekend_price) + parseInt(responseData[i].cost * $("#partySize").val());
						}else{
							var totalPrice = parseInt(responseData[i].weekday_price) + parseInt(responseData[i].cost * $("#partySize").val());
						}
						let totalPriceText = "<p class = 'card-text'>Total Price on " + day + ": £" + totalPrice + "</p>"
						let venueCountText = "<p class = 'card-text'>Number of Bookings: " + venueCount[responseData[i].venue_id - 1] + "</p>"
						let venueImg = "<img class = 'card-img-top' src = 'img/"+responseData[i].venue_id+".jpg'>"
						let insertedHtml = "<div class = 'm-4 p-4 card h-150'>" + venueImg + "<div class = 'card-body'><h4 class = 'card-title'>" + name + "</h4>" + capacityText + licensedText + costPerPersonText + weekdayPriceText + weekendPriceText + totalPriceText + venueCountText + "</div></div>"
						switch(i % 3){
							case 0:
								$("#1st").append(insertedHtml);
								break;
							case 1:
								$("#2nd").append(insertedHtml);
								break;
							case 2:
								$("#3rd").append(insertedHtml);
								break;
						}
					}
				},
				error: function(xhr, status, error){
					console.log(xhr.status + ";" + xhr.statusText);
				}, 
				dataType: "json"
			});
		});
	});
	</script>
</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="wedding.php">WeddingVenues</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">Search</a>
        </li>  
      </ul>
    </div>
  </div>
</nav>

<div id = 'image'>
	<div class = 'image-text'>
		<h1>Search for the best venues for you</h1>
	</div>
</div>

<div class = "container-fluid">
	<div class = "m-5">
		<div class = "row">
			<div class = "col">
				<h1>Search venues suited for you</h1>
				<p>Finding the perfect wedding venue can be a hard task. Thats why you should use WeddingVenues for the best picked venues.</p>	
				<h2 class = "mt-5 pt-5" id = "dateStr"></h2>
			</div>
			<div class = "col">
				<form id = "myform" class = "row g-3 was-validated">
					<div class = "col-md-6">
						<label for = "partySize" class = "form-label">Party Size</label>
						<input type = "number" class = "form-control" id = "partySize" name = "partySize" placeholder = "Enter Party Size" min = "0" required>
						<div class="valid-feedback"></div>
						<div class="invalid-feedback"></div>
					</div>
					<div class="col-md-6">
						<label for = "cateringGrade" class = "form-label">Catering Grade</label>
						<input type = "number" class = "form-control" id = "cateringGrade" name = "cateringGrade" placeholder = "Enter Catering Grade" min = "1" max = "5" required>
						<div class="valid-feedback"></div>
						<div class="invalid-feedback"></div>
					</div>
					<div class = "col-md-12">
						<label for "weddingDate" class = "form-label">Date:</label>
						<input type = "date" class = "form-control" id = "weddingDate" name = "weddingDate" required>
						<div class="valid-feedback"></div>
						<div class="invalid-feedback"></div>						
					</div>
					<div class = "col-12">
						<input type = "submit" class = "btn btn-primary" id = "submitButton" value = "Submit">
					</div>
				</form>
			</div>
		</div>	
	</div>
</div>

<div class = "container-fluid">
	<div class = "m-5">
		<div class = "row" id = "venues">
			<div class = "col">
				<div id = "1st"></div>
			</div>
			<div class = "col">
				<div id = "2nd"></div>
			</div>
			<div class = "col">
				<div id = "3rd"></div>
			</div>
		</div>
	</div>
</div>

<div class="p-4 bg-dark text-white text-center">
	<p>WeddingVenues</p>
</div>

</body>
</html>