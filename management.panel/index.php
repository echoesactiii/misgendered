<?php

require "../settings.php";
require "../include/rb.php";
require "../vendor/autoload.php";

session_start();

R::setup('mysql:host='.$settings['database']['server'].'; dbname='.$settings['database']['database'],$settings['database']['username'],$settings['database']['password']);

$m = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/mustache_templates')
));

$l = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname("../index.php").'/letter_templates')
));

$bodyModel['site_title'] = $settings['site']['title'];

if($_SESSION['manager']['active'] == true && $_SESSION['manager']['ip'] == $_SERVER['REMOTE_ADDR']){
	if($_REQUEST['logout']){
		unset($_SESSION['manager']);
		header("Location: index.php");
		exit();
	}

	if($_REQUEST['resend']){
		$letter = R::load('letter', $_REQUEST['resend']);
		$newLetter = R::dispense('letter');

		$newLetter->ip = "Management";
		$newLetter->name = $letter->name;
		$newLetter->house = $letter->house;
		$newLetter->street = $letter->street;
		$newLetter->city = $letter->city;
		$newLetter->postcode = $letter->postcode;
		$newLetter->type = $letter->type;
		$newLetter->info = $letter->info;
		$newLetter->time = time();

		if($letter->house == " "){ $modelHouse = null; }
		if($letter->street == " "){ $modelStreet = null; }

		$letterModel = array(
			'org_name' => $letter->name,
			'org_house' => $modelHouse,
			'org_street' => $modelStreet,
			'org_city' => $letter->city,
			'org_postcode' => $letter->postcode,
			'letter_type' => $letter->type,
			'site_domain' => $settings['site']['domain'],
			'site_title' => $settings['site']['signature'],
			'site_email' => $settings['site']['email'],
			'letter_prefix' => $settings['reference']['prefix'],
			'other_info' => $letter->info
			//BEES ARE DELICIOUS
		);

		if($modelHouse || $modelStreet){
			$letterModel['house_or_street'] = true;
		}

		$letterView = $l->loadTemplate($letter->type);
		$letterHTML = $letterView->render($letterModel);

		$newLetter->body = strip_tags($letterHTML);

		$apiData = array(
			"cmd" => "SendLetter",
			"login" => urlencode($settings['pc2paper']['username']),
			"password" => urlencode($settings['pc2paper']['password']),
			"blnIncludeSenderAddress" => "false",
			"blnDebug" => "true",
			"strName" => urlencode($letter->name),
			"strHouseName" => urlencode($letter->house),
			"strStreet" => urlencode($letter->street),
			"strTownCity" => urlencode($letter->city),
			"strPostCode" => urlencode($letter->postcode),
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

		$newLetter->result = $apiResult;

		if(stristr($apiResult, "ERR")){
			$newLetter->sent = false;
			$letterId = R::store($newLetter);
			$bodyModel['alert_type'] = "danger";
			$bodyModel['alert'] = "Letter could not be resent. Saved with ID ".$letterId;
		}else{
			$newLetter->sent = true;
			$letterId = R::store($newLetter);
			$bodyModel['alert_type'] = "success";
			$bodyModel['alert'] = "Letter resent with ID ".$letterId;
		}
	}

	$bodyModel['username'] = $_SESSION['manager']['username'];

	$letters = R::findAll('letter', 'ORDER BY time DESC');
	foreach($letters as $ltr){
		if($ltr->sent){
			$colour = "success";
			$status = "Sent";
		}else{
			$colour = "warning";
			$status = "Unsent";
		}

		$bodyModel['letters'][] = array(
			"colour" => $colour,
			"id" => $ltr->id,
			"status" => $status,
			"ip" => $ltr->ip,
			"name" => $ltr->name,
			"house" => $ltr->house,
			"street" => $ltr->street,
			"city" => $ltr->city,
			"postcode" => $ltr->postcode,
			"type" => $ltr->type,
			"date" => date("Y-m-d H:i:s", $ltr->time),
			"result" => $ltr->result,
			"body" => $ltr->body
		);
	}

	$page = $m->loadTemplate("manage");
	echo $page->render($bodyModel);
}elseif($_POST['username'] && $_POST['password']){
	$user = R::findOne('manager', 'username = :username AND password = sha1(:password)', array(':username' => $_POST['username'], ':password' => $_POST['password']));

	if($user->id){
		$_SESSION['manager']['active'] = true;
		$_SESSION['manager']['ip'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['manager']['id'] = $user->id;
		$_SESSION['manager']['username'] = $user->username;

		header("Location: index.php");
	}else{
		$bodyModel['error_message'] = "Invalid username/password.";

		$loginPage = $m->loadTemplate("login");
		echo $loginPage->render($bodyModel);
	}
}else{
	$loginPage = $m->loadTemplate("login");
	echo $loginPage->render($bodyModel);
}