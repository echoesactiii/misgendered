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

$header = $m->loadTemplate("header");
$footer = $m->loadTemplate("footer");

$headerModel = array(
	"site_title" => $settings['site']['title']
);

if($url == "home"){
	$bodyModel = $headerModel;

	$body = $m->loadTemplate("home");
	echo $body->render($bodyModel);
}