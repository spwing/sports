<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Raleway:400,300,200' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
		<link type="text/css" rel="stylesheet" href="css/style.css"/>
		
		<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}"/>
		
		<script src ="http://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
		<link href='http://fonts.googleapis.com/css?family=Port+Lligat+Sans' rel='stylesheet' type='text/css'>
		<script src="js/script.js" type="text/javascript" rel="javascript"></script>
		<title>
			The Anvil
			@yield('title')
		</title>
			@yield('head')
			
		<style>
			.nonselectable{-webkit-user-select:none;-khtml-user-drag:none;-khtml-user-select:none;-moz-user-select:none;-moz-user-select:-moz-none;-ms-user-select:none;user-select:none}
			.selectable{-webkit-user-select:auto;-khtml-user-drag:auto;-khtml-user-select:auto;-moz-user-select:auto;-ms-user-select:auto;user-select:auto}
		</style>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>
			$('html').addClass('nonselectable');
			$.fn.ready(function(){var b=$(".nonselectable"),c=$(".selectable");b.on("dragstart, selectstart",function(a){a.preventDefault()});c.on("dragstart, selectstart",function(a){a.stopPropagation()});b.find("*").andSelf().attr("unselectable","on");c.find("*").andSelf().removeAttr("unselectable")});
		</script>
	</head>
	<body>
		<header>
			<div id="head-left" class="head"><a href="/"><img id="emblem" src="emblem.png"/></a></div>
			<div id="head-center" class="head">
			<a href="/">The Anvil</a>
			</div>
			<div id="head-right" class="head">
			@if(Session::get('email'))
				@if(Session::get('email') == 'anvil@mxschool.edu')
					<a class="head" href="/newpost">New Post &middot</a>
					<a class="head" href="/users">Subscribers &middot</a>
				@endif
				<a class="rightmost head" href="/logout">Log Out</a>
			@endif
			@if(!(Session::get('email')))
				<a class="head" href="/login">Log In &middot</a>
				<a class="rightmost head" href="/signup">Sign Up</a>
			@endif
			</div>
		</header>
		@yield('content')
		<footer>Kiara Wahnschafft &copy 2015</footer>
	</body>
</html>
