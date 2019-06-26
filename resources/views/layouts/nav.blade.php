<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
	<a class="navbar-brand" href="{{ url('/') }}">
		<div><img src="/img/LALS_Logo.svg"> | {{ env('APP_NAME_SHORT', 'LALS') }}</div>
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbar">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
			</li>

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown-visit-us" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Visit Us</a>
				<div class="dropdown-menu" aria-labelledby="dropdown-visit-us">
					<a class="dropdown-item" href="/public-rides">Public Rides</a>
					<a class="dropdown-item" href="/visiting-railroaders">Visiting Railroaders</a>
				</div>
			</li>

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown-about-us" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">About Us</a>
				<div class="dropdown-menu" aria-labelledby="dropdown-about-us">
					<a class="dropdown-item" href="/the-trains">The Trains</a>
					<a class="dropdown-item" href="/the-railroad">The Railroad</a>
					<a class="dropdown-item" href="/historical-exhibits">Historical Exhibits</a>
				</div>
			</li>

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown-media" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Media</a>
				<div class="dropdown-menu" aria-labelledby="dropdown-media">
					<a class="dropdown-item" href="https://www.youtube.com/channel/UCmPX1BKzSJJ7vhcKoBySrDg/featured" target="_blank">Videos</a>
					<a class="dropdown-item" href="https://www.facebook.com/LALSRM/photos_stream?tab=photos_albums" target="_blank">Pictures</a>
				</div>
			</li>

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown-events" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Events</a>
				<div class="dropdown-menu" aria-labelledby="dropdown-events">
					<a class="dropdown-item" href="/calendar">Calendar of Events</a>
					<a class="dropdown-item" href="">Spring Meet</a>
					<a class="dropdown-item" href="">Fall Meet</a>
					<a class="dropdown-item" href="">Ghost Train</a>
				</div>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/membership">Membership</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/contact-us">Contact Us</a>
			</li>
		</ul>

		<ul class="navbar-nav ml-auto">
			@if (Auth::guest())
				<li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
				<li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
			@else
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown-account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->getFullName() }}</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-account">

						<a class="dropdown-item" href="{{ route('profile', Auth::user()) }}">My Dashboard</a>
						<a class="dropdown-item" href="">Member Forum</a>
						<a class="dropdown-item" href="/calendar">Calendar of Events</a>
						<a class="dropdown-item" href="">Newsletter</a>
						<a class="dropdown-item" href="">Surveys</a>
						<a class="dropdown-item" href="/members/invoices">Invoices</a>
						<a class="dropdown-item" href="">Manage Volunteer Hours</a>
						@hasanyrole('super admin|web admin|blog admin|forum admin|membership admin|financial admin')
						<hr>
						<a class="dropdown-item" href="">Admin Dashboard</a>
						@endhasanyrole
						@hasanyrole('super admin|web admin')
						<a class="dropdown-item" href="">Edit Web Pages</a>
						<a class="dropdown-item" href="">Manage Surveys</a>
						<a class="dropdown-item" href="">Manage Email Campaigns</a>
						<a class="dropdown-item" href="">Manage Newsletters</a>
						<a class="dropdown-item" href="">Manage Calendar of Events</a>
						<a class="dropdown-item" href="">Manage Fundraising Campaigns</a>
						@endhasanyrole
						@hasanyrole('super admin|blog admin')
						<a class="dropdown-item" href="">Manage Blog</a>
						@endhasanyrole
						@hasanyrole('super admin|forum admin')
						<a class="dropdown-item" href="">Manage Forum</a>
						@endhasanyrole
						@hasanyrole('super admin|membership admin')
						<a class="dropdown-item" href="">Manage User Accounts</a>
						<a class="dropdown-item" href="">Manage User Permissions/Roles</a>
						<a class="dropdown-item" href="">Manage User Profiles</a>
						<a class="dropdown-item" href="">Manage User Blog Posts</a>
						<a class="dropdown-item" href="">Manage User Volunteer Hours</a>
						<a class="dropdown-item" href="">Approve Probationary Memberships</a>
						<a class="dropdown-item" href="">Approve Full Membership</a>
						@endhasanyrole
						@hasanyrole('super admin|financial admin')
						<a class="dropdown-item" href="">Manage Membership Dues</a>
						<a class="dropdown-item" href="">Manage Shed Rental Dues</a>
						<a class="dropdown-item" href="">Manage User Invoices</a>
						<a class="dropdown-item" href="">Manage Payment Processing</a>
						<a class="dropdown-item" href="">Manually Enter Cash / Checks</a>
						@endhasanyrole

						<hr>
						<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST"
							  style="display: none;">
							{{ csrf_field() }}
						</form>
					</div>
				</li>
			@endif
		</ul>
	</div>
</nav>

