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
	$bodyModel['letter_types'] = $settings['letters'];

	$body = $m->loadTemplate("home");
	echo $body->render($bodyModel);
}elseif($url == "send-letter"){
	if(!$_POST['org_name'] || !$_POST['org_house'] || !$_POST['org_street'] || !$_POST['org_postcode'] || !$_POST['org_city'] || !$_POST['letter_type']){
		// TODO: do some erroring thing
	}

	$letter = R::dispense('letter');
	$letter->ip = $_SERVER['REMOTE_ADDR'];
	$letter->name = $_POST['org_name'];
	$letter->house = $_POST['org_house'];
	$letter->street = $_POST['org_street'];
	$letter->city = $_POST['org_city'];
	$letter->postcode = $_POST['org_postcode'];
	$letter->type = $_POST['letter_type'];
	$letter->info = $_POST['other_info'];
	$letter->body = "blah blah blah blha blah"; // TODO: come back to this.
	$letter->time = time();

	$apiData = array(
		"cmd" => "SendLetter",
		"login" => $settings['pc2paper']['username'],
		"password" => $settings['pc2paper']['password'],
		"blnIncludeSenderAddress" => "false",
		"blnDebug" => "true",
		"strName" => $_POST['org_name'],
		"strHouseName" => $_POST['org_house'],
		"strStreet" => $_POST['org_street'],
		"strTownCity" => $_POST['org_city'],
		"strPostCode" => $_POST['org_postcode'],
		"iCountry" => $settings['pc2paper']['country_code'],
		"postage" => $settings['pc2paper']['postage_id'],
		"Extras" => $settings['pc2paper']['extras_id'],
		"Paper" => $settings['pc2paper']['paper_id'],
		"Envelope" => $settings['pc2paper']['envelope_id'],
		"strBody" => "blah blah blah blah blah blah" // TODO: come back to this.
	);

	foreach($apiData as $k => $v){
		$postData .= $k."=".$v."&";
	}


	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $settings['pc2paper']['endpoint']);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$apiResult = curl_exec($curl);

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

