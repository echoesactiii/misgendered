<?php

require "settings.php";
require "include/rb.php";
require "vendor/autoload.php";

R::setup('mysql:host='.$settings['database']['server'].'; dbname='.$settings['database']['database'],$settings['database']['username'],$settings['database']['password']);

$url = preg_replace("/\//", '', $_SERVER['REQUEST_URI']);
if(!$url){ $url = "home"; }

$m = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/mustache_templates')
));

$bodyModel = array(
	"site_title" => $settings['site']['title'],
	"navigation" => $settings['nav']
);

if($url == "home"){
	$body = $m->loadTemplate("home");
	echo $body->render($bodyModel);
}else{
	$body = $m->loadTemplate('page');
	echo $body->render($bodyModel);
}

