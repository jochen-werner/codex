<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Codex</title>

	<!-- Bootstrap -->
	<link href="//fonts.googleapis.com/css?family=RobotoDraft" rel="stylesheet" type="text/css">
	<link href="//fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
	<link href="//fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet" type="text/css">

	<link rel="stylesheet" type="text/css" href="/vendor/codex/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/vendor/codex/css/paper.min.css">
	<link rel="stylesheet" type="text/css" href="/vendor/codex/css/codex.css">
</head>
<body>
	<div id="wrapper">
		<div id="sidebar-wrapper">
			<h3 class="sidebar-brand">Codex</h3>

			@yield('sidebar')
		</div>

		<div id="page-wrapper">
			<div class="container-fluid">

				@yield('before_content')

				<div class="row">
					<div class="col-lg-12 page-content">
						@yield('content')
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="/vendor/codex/js/bootstrap.min.js"></script>
	<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>

	<script>
	    $("#menu-toggle").click(function(e) {
	        e.preventDefault();
	        $("#wrapper").toggleClass("toggled");
	    });
    </script>
</body>
</html>