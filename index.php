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
		<link href="/typeahead-bs.css" rel="stylesheet" media="screen">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js"></script>
		<script src="/hogan.js"></script>
		<script src="/typeahead.min.js"></script>
		<script src="/site.js"></script>
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
			<a rel="license" href="http://creativecommons.org/licenses/by-sa/2.0/uk/deed.en_GB"><img alt="Creative Commons Licence" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/2.0/uk/88x31.png" /></a>
			<a href="https://twitter.com/misgendered" class="twitter-follow-button" data-show-count="false" data-size="large" data-dnt="true">Follow @misgendered</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			<iframe src="http://ghbtns.com/github-btn.html?user=cmantito&amp;repo=misgendered.me&amp;type=watch&amp;size=large" allowtransparency="true" frameborder="0" scrolling="0" width="90" height="30"></iframe>
			<br/>This work by <a xmlns:cc="http://creativecommons.org/ns#" href="http://misgendered.me" property="cc:attributionName" rel="cc:attributionURL">misgendered.me</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/2.0/uk/deed.en_GB">Creative Commons Attribution-ShareAlike 2.0 UK: England &amp; Wales License</a>.
		</footer>
	</bodyL
</html>