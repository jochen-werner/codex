<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Codex</title>

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="/vendor/codex/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/vendor/codex/css/paper.min.css">
	<link rel="stylesheet" type="text/css" href="/vendor/codex/css/codex.css">
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="/codex">Codex</a>
			</div>
		</div>
	</nav>

	<div class="container">
		@yield('content')
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="/vendor/codex/js/bootstrap.min.js"></script>
</body>
</html>