<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="font/css/all.css" rel="stylesheet"> <!--load all styles -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">

		<title>Hello, world!</title>
		<script type="text/javascript" src="js/custom.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <section class="hero-area bg-1 text-center overly">
        <!-- Container Start -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Header Contetnt -->
                    <div id="dtp-picker-4" data-event-prefix=""
                        class="dtp-picker dtp-lang-en  with-search single-search  initialised">
                        <form class="dtp-picker-form otkit"><input type="hidden" name="timezoneOffset"
                                title="timezoneOffset" value="330">
                            <div class="dtp-picker-selectors-container">
                                <div class="party-size-picker dtp-picker-selector select-native unselected-on-init people">
																	<a class="select-label dtp-picker-selector-link selected-person" tabindex="-1"> 0 Person</a>
                                  <select id="person-select" name="person-select" aria-label="party size" onchange="changeSelectedPerson()">
	                                  <?php
																			for ($i = 1; $i < 4; $i++) {
																				print '<option value="' . $i . '">' . $i . ' Person</option>';
																			}
																		?>
																	</select>
                                </div>
                                <div class="date-picker dtp-picker-selector"> <a
                                        class="dtp-picker-selector-link date-label dtp-picker-label">Jul 26, 2019</a>
                                    <input type="hidden" name="submit_datepicker" id="submit_datepicker"
                                        value="2019-07-26" aria-label="date"></div>
                                <div class="time-picker dtp-picker-selector select-native unselected-on-init time"> <a
                                        class="select-label dtp-picker-selector-link" tabindex="-1">7:00 PM</a> </div>
                            </div>
                            <input type="submit" value="Let's go" class="button dtp-picker-button">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container End -->
    </section>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>