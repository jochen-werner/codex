<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Codex</title>

	<!-- Bootstrap -->
	<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
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

			<div class="collapse navbar-collapse">
				<div class="pull-right">
					<form role="search" action="/{{ Config::get('codex.route_base').'/search' }}" method="GET" class="navbar-form navbar-left">
						<div class="input-group">
							<input type="search" name="q" class="form-control" placeholder="I'm looking for...">
						</div>

						<button class="btn btn-default" type="button">Search</button>
					</form>
				</div>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-md-3" id="sidebar">
				@yield('sidebar')
			</div>

			<div class="col-md-9" id="content">
				@yield('content')
			</div>
		</div>		
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="/vendor/codex/js/bootstrap.min.js"></script>
	<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
</body>
</html>