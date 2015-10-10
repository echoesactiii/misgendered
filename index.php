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

$l = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/letter_templates')
));

$bodyModel = array(
	"site_title" => $settings['site']['title'],
	"navigation" => $settings['nav']
);

if($url == "home"){
	$bodyModel['letter_types'] = $settings['letters'];
	$bodyModel['place_api_key'] = $settings['google_places']['api_key'];

	$body = $m->loadTemplate("home");
	echo $body->render($bodyModel);
}elseif($url == "send-letter"){
	if(!$_POST['org_name'] || !$_POST['org_postcode'] || !$_POST['org_city'] || !$_POST['letter_type']){
		// TODO: do some erroring thing
	}

	$letterModel = array(
		'org_name' => $_POST['org_name'],
		'org_house' => $_POST['org_house'],
		'org_street' => $_POST['org_street'],
		'org_city' => $_POST['org_city'],
		'org_postcode' => $_POST['org_postcode'],
		'letter_type' => $_POST['letter_type'],
		'site_domain' => $settings['site']['domain'],
		'site_title' => $settings['site']['signature'],
		'site_email' => $settings['site']['email'],
		'other_info' => $_POST['other_info'],
		//BEES ARE DELICIOUS
	);


	if($_POST['org_house'] || $_POST['org_street']){
		$letterModel['house_or_street'] = true;
	}

	$letterView = $l->loadTemplate($_POST['letter_type']);
	$letterHTML = $letterView->render($letterModel);

	$letterValidation = array(
		'org_house' => $_POST['org_house'],
		'org_street' => $_POST['org_street']
	);

	if(!$letterValidation['org_house']) { //if this is blank
		$letterValidation['org_house'] = " ";
	}

	if(!$letterValidation['org_street']) { //if this is blank
		$letterValidation['org_street'] = " ";
	}

	$letter = R::dispense('letter');
	$letter->ip = $_SERVER['REMOTE_ADDR'];
	$letter->name = $_POST['org_name'];
	$letter->house = $letterValidation['org_house'];
	$letter->street = $letterValidation['org_street'];
	$letter->city = $_POST['org_city'];
	$letter->postcode = $_POST['org_postcode'];
	$letter->type = $_POST['letter_type'];
	$letter->info = $_POST['other_info'];
	$letter->body = strip_tags($letterHTML);
	$letter->time = time();



	$apiData = array(
		"cmd" => "SendLetter",
		"login" => urlencode($settings['pc2paper']['username']),
		"password" => urlencode($settings['pc2paper']['password']),
		"blnIncludeSenderAddress" => "false",
		"blnDebug" => "true",
		"strName" => urlencode($_POST['org_name']),
		"strHouseName" => urlencode($letterValidation['org_house']),
		"strStreet" => urlencode($letterValidation['org_street']),
		"strTownCity" => urlencode($_POST['org_city']),
		"strPostCode" => urlencode($_POST['org_postcode']),
		"iCountry" => urlencode($settings['pc2paper']['country_code']),
		"postage" => urlencode($settings['pc2paper']['postage_id']),
		"Extras" => urlencode($settings['pc2paper']['extras_id']),
		"Paper" => urlencode($settings['pc2paper']['paper_id']),
		"Envelope" => urlencode($settings['pc2paper']['envelope_id']),
		"strBody" => urlencode($letterHTML)
	);


	foreach($apiData as $k => $v){
		$postData .= $k."=".$v."&";
	}

	if($settings['site']['actually_send_letters']){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $settings['pc2paper']['endpoint']);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$apiResult = curl_exec($curl);

	}else{
		if($settings['site']['disabled_letter_send_makes_errors']){
			$apiResult = "ERR-Local: Letter sending is disabled.";
		}else{
			$apiResult = "OK-Local: Letter sending is disabled.";
		}
	}

	$letter->result = $apiResult;

	if(stristr($apiResult, "ERR")){
		// display some encouraging message to user about how we'll ensure it's sent
		// send us an email to look into it
		// maybe offer user to put in email address so we can update them.
		$letter->sent = false;
	}else{
		// display a page asking user please donate - their letter was sent.
		$letter->sent = true;
	}

	R::store($letter);
}else{
	$body = $m->loadTemplate('page');
	echo $body->render($bodyModel);
}

