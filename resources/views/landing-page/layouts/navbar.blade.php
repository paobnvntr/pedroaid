<header id="header" class="header fixed-top">
	<div class="dateTimeContainer">
		<div id="currentDateTime">
		</div>
	</div>

	<div class="container-fluid container-xl d-flex align-items-center justify-content-between">
		<a href="{{ route('home') }}" class="logo d-flex align-items-center">
			<span>PedroAID</span>
		</a>

		<nav id="navbar" class="navbar">
			<ul>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#home">Home</a></li>
				<!-- <li><a class="nav-link scrollto" href="{{ route('home') }}#about-ordinances">City Ordinances</a></li> -->
				<li><a class="nav-link scrollto" href="{{ route('home') }}#services">Services</a></li>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#reviews">Reviews</a></li>
				<li><a class="nav-link scrollto" href="{{ route('home') }}#faq">FAQs</a></li>
			</ul>
			<i class="bi bi-list mobile-nav-toggle"></i>
		</nav>
	</div>
</header>

<script>
    function updateDateTime() {
        var currentDate = new Date();
        var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var dayOfWeek = daysOfWeek[currentDate.getDay()];
        var month = months[currentDate.getMonth()];
        var day = currentDate.getDate();
        var year = currentDate.getFullYear();
        var hour = currentDate.getHours();
        var minute = currentDate.getMinutes();
        var second = currentDate.getSeconds();

        var ampm = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12;
        hour = hour ? hour : 12;
        
        var time = hour + ':' + (minute < 10 ? '0' : '') + minute + ':' + (second < 10 ? '0' : '') + second + ' ' + ampm;
        
        var dateTime = dayOfWeek + ', ' + month + ' ' + day + ', ' + year + ' ' + time;
        
        document.getElementById('currentDateTime').textContent = "Philippine Standard Time: " + dateTime;
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>