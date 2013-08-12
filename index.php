<?php
	$page = preg_replace("/\//", '', preg_replace("/\/pages\//", '', $_SERVER['REQUEST_URI']));
	if(!$page){ $page = "home"; }
	$content = @file_get_contents("pages/".$page.".inc");
	if(!$content){
		header("HTTP/1.0 404 Not Found");
		$content = file_get_contents("pages/404.inc");
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>you.misgendered.me</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="/style.css" rel="stylesheet" media="screen">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
			    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
			    </button>

				<a href="/pages/home" class="navbar-brand">you.misgendered.me</a>

			    <div class="nav-collapse collapse navbar-responsive-collapse">
					<ul class="nav navbar-nav">
				        <li><a href="/pages/home">Home</a></li>
				        <li><a href="/pages/about">About</a></li>
				        <li><a href="/pages/helpout">Help out!</a></li>
			    	</ul>
			    </div>
		    </div>
	    </div>
	    <?php
	    	if($page == "home"){
	    		echo file_get_contents("pages/home-hero.inc");
	    	}
	    ?>
		<div>
		<?php
			echo $content;
		?>
		</div>
		<footer class="bs-footer">
			Four loko pop-up consectetur actually. Banksy hella yr, 90's Schlitz literally umami. Delectus Marfa quinoa before they sold out.
		</footer>
	</bodyL
</html>