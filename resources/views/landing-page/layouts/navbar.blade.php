<!-- ======= Header ======= -->
<header id="header" class="header fixed-top">
	<div class="dateTimeContainer">
		<!-- Display date and time -->
		<div id="currentDateTime">
			<!-- Time will be updated dynamically by JavaScript -->
		</div>
	</div>

	<div class="container-fluid container-xl d-flex align-items-center justify-content-between">

		<a href="{{ route('home') }}" class="logo d-flex align-items-center">
			<span>PedroAID</span>
		</a>

		<nav id="navbar" class="navbar">
			<ul>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#home">Home</a></li>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#about-ordinances">City Ordinances</a></li>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#services">Services</a></li>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#reviews">Reviews</a></li>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#faq">FAQs</a></li>
			</ul>
			<i class="bi bi-list mobile-nav-toggle"></i>
		</nav><!-- .navbar -->

	</div>
</header><!-- End Header -->

<script>
    // Function to update the displayed time every second
    function updateDateTime() {
        // Get the current date and time
        var currentDate = new Date();
        
        // Define the days of the week and months
        var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        // Get the day of the week, month, day, year, hour, minute, second
        var dayOfWeek = daysOfWeek[currentDate.getDay()];
        var month = months[currentDate.getMonth()];
        var day = currentDate.getDate();
        var year = currentDate.getFullYear();
        var hour = currentDate.getHours();
        var minute = currentDate.getMinutes();
        var second = currentDate.getSeconds();
        
        // Convert hour to AM/PM format
        var ampm = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12;
        hour = hour ? hour : 12; // Handle midnight (0 hours)
        
        // Format the time
        var time = hour + ':' + (minute < 10 ? '0' : '') + minute + ':' + (second < 10 ? '0' : '') + second + ' ' + ampm;
        
        // Concatenate the day, date, and time
        var dateTime = dayOfWeek + ', ' + month + ' ' + day + ', ' + year + ' ' + time;
        
        // Update the content of the element with the current date and time
        document.getElementById('currentDateTime').textContent = "Philippine Standard Time: " + dateTime;
    }

    // Call the updateDateTime function initially to set the initial time
    updateDateTime();

    // Set up an interval to update the time every second
    setInterval(updateDateTime, 1000);
</script>