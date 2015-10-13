<?php

require "settings.php";
require "include/rb.php";
require "vendor/autoload.php";

session_start();

R::setup('mysql:host='.$settings['database']['server'].'; dbname='.$settings['database']['database'],$settings['database']['username'],$settings['database']['password']);

$homePageName = preg_replace("/^\//", '', $settings['pages']['home']);
$donatePageName = preg_replace("/^\//", '', $settings['pages']['donate']);

$url = preg_replace("/^\//", '', $_SERVER['REQUEST_URI']);
if(!$url){ $url = $homePageName; }

$m = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/mustache_templates')
));

$l = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/letter_templates')
));

$bodyModel = array(
	"site_title" => $settings['site']['title'],
	"site_domain" => $settings['site']['domain'],
	"navigation" => $settings['nav']
);

if($url == $homePageName){ // HOME PAGE
	$bodyModel['letter_types'] = $settings['letters'];
	$bodyModel['place_api_key'] = $settings['google_places']['api_key'];
	$bodyModel['recaptcha_site_key'] = $settings['recaptcha']['site_key'];

	if($_SESSION['home_error']){
		$bodyModel['error_message'] = $_SESSION['home_error'];
		unset($_SESSION['home_error']);
	}

	$body = $m->loadTemplate("home");
	echo $body->render($bodyModel);
}elseif($url == "send-letter"){ // SEND LETTER
	$_SESSION = array();

	if(!$_POST['org_name'] || !$_POST['org_postcode'] || !$_POST['org_city'] || !$_POST['letter_type']){
		$_SESSION['home_error'] = "One or more required fields were not filled out. Organisation name, city, postcode and the type of letter are required.";
		header("Location: ".$settings['pages']['home']);
		exit();
	}

	if(!$_POST['g-recaptcha-response']){
		$_SESSION['home_error'] = "You must confirm that you are, in fact, a human.";
		header("Location: ".$settings['pages']['home']);
		exit();
	}

	$recaptcha = new \ReCaptcha\ReCaptcha($settings['recaptcha']['secret_key']);
	$captchaResponse = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	if(!$captchaResponse->isSuccess()){
		$_SESSION['home_error'] = "The captcha was not correctly completed.";
		header("Location: ".$settings['pages']['home']);
		exit();
	}

	$existingLetters = R::findAll('letter', 'postcode = :postcode AND sent = true ORDER BY time DESC', array(':postcode' => $_POST['org_postcode']));
	foreach($existingLetters as $existing){
		if(time() < $existing->time + 2592000){
			if(
				(
					($_POST['org_house'] && $existing->house == $_POST['org_house']) ||
					(!$_POST['org_house'] && $_POST['org_street'] && $existing->street == $_POST['org_street']) ||
					(!$_POST['org_house'] && !$_POST['org_street'] && $existing->name == $_POST['org_name'])
				) && !$_POST['other_info'] && $_POST['letter_type'] == $existing->type
			){
				header("Location: ".$settings['pages']['limiter']);
				exit();
			}
		}
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
		'letter_prefix' => $settings['reference']['prefix'],
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
		$letter->sent = false;
		$letterId = R::store($letter);

		mail($settings['site']['error_email'], "Letter ".$letterId." could not be sent.", "Letter ".$letterId." has been stored in the database, but could not be sent. The upstream error message was: ".$apiResult, "From: ".$settings['site']['title']." <".$settings['site']['error_email'].">");

		$_SESSION['letter_error'] = $letterId;

		header("Location: ".$settings['pages']['error']);
		exit();
	}else{
		$_SESSION['success_message'] = $settings['messages']['letter_sent'];
		$letter->sent = true;
		R::store($letter);
		header("Location: ".$settings['pages']['success']);
		exit();
	}

	R::store($letter);
}elseif($url == "process-donation"){
	if(!$_POST['stripe_token'] || !$_POST['stripe_email'] || !$_POST['donation_amount']){
		$_SESSION['home_error'] = "Donation checkout incomplete.";
		header("Location: ".$settings['pages']['home']);
		exit();
	}

	\Stripe\Stripe::setApiKey($settings['stripe']['secret_key']);

	$customer = \Stripe\Customer::create(array(
		'email' => $_POST['stripe_email'],
		'card'  => $_POST['stripe_token']
	));

	$charge = \Stripe\Charge::create(array(
		'customer' => $customer->id,
		'amount'   => $_POST['donation_amount'] * 100,
		'currency' => 'gbp'
	));

	if(!$charge->id){
		$_SESSION['home_error'] = "We're sorry, but there was a problem processing your donation.";
		header("Location: ".$settings['pages']['home']);
		exit();
	}

	header("Location: ".$settings['pages']['thanks']);
	exit();
}elseif(preg_match("/^".$settings['examples']['prefix']."\/(.+)$/", $url, $matches)){
	$letterModel = array(
		'org_name' => $settings['examples']['name'],
		'org_house' => $settings['examples']['house'],
		'org_street' => $settings['examples']['street'],
		'org_city' => $settings['examples']['city'],
		'org_postcode' => $settings['examples']['postcode'],
		'letter_type' => $matches[1],
		'site_domain' => $settings['site']['domain'],
		'site_title' => $settings['site']['signature'],
		'site_email' => $settings['site']['email'],
		'letter_prefix' => $settings['reference']['prefix'],
		'house_or_street' => "true"
	);

	try{
		$letterView = $l->loadTemplate($matches[1]);
		$letterHTML = $letterView->render($letterModel);
	}catch (Exception $e){
		header("Location: /404");
		exit();
	}

	$bodyModel['letter_content'] = $letterHTML;

	$body = $m->loadTemplate('letter');
	echo $body->render($bodyModel);

}elseif(preg_match("/^".$settings['examples']['other_info_prefix']."\/(.+)$/", $url, $matches)){
	$letterModel = array(
		'org_name' => $settings['examples']['name'],
		'org_house' => $settings['examples']['house'],
		'org_street' => $settings['examples']['street'],
		'org_city' => $settings['examples']['city'],
		'org_postcode' => $settings['examples']['postcode'],
		'letter_type' => $matches[1],
		'site_domain' => $settings['site']['domain'],
		'site_title' => $settings['site']['signature'],
		'site_email' => $settings['site']['email'],
		'letter_prefix' => $settings['reference']['prefix'],
		'other_info' => $settings['examples']['other_info'],
		'house_or_street' => "true"
	);

	try{
		$letterView = $l->loadTemplate($matches[1]);
		$letterHTML = $letterView->render($letterModel);
	}catch (Exception $e){
		header("Location: /404");
		exit();
	}

	$bodyModel['letter_content'] = $letterHTML;

	$body = $m->loadTemplate('letter');
	echo $body->render($bodyModel);

}else{ // NOT THE HOMEPAGE
	if(preg_match("/^".$settings['reference']['prefix']."\/(.+)$/", $url, $matches)){
		$url = $matches[1];
		$bodyModel['navigation'] = $settings['reference']['nav'];
	}

	if($url == $donatePageName){
		$bodyModel['donate_page'] = true;
		$bodyModel['comm_multi'] = $settings['pricing']['commission_percentage'];
		$bodyModel['comm_add'] = $settings['pricing']['commission_value'];
		$bodyModel['letter_cost'] = $settings['pricing']['letter_cost'];
		$bodyModel['stripe_key'] = $settings['stripe']['publishable_key'];
	}

	$page = R::findOne('page', 'url = :url', array(':url' => $url));

	if(!$page){
		header("HTTP/1.1 404 Page Not Found");
		$page = R::findOne('page', 'url = :url', array(':url' => '404'));
	}

	$bodyModel['header'] = $page->header;
	$bodyModel['subheader'] = $page->subheader;
	$bodyModel['btn_size'] = $page->btnSize;
	$bodyModel['btn_type'] = $page->btnType;
	$bodyModel['btn_url'] = $page->btnUrl;
	$bodyModel['btn_text'] = $page->btnText;
	$bodyModel['footer'] = $settings['site']['footer'];

	$listItems = R::find('pagelistitems', 'url = :url ORDER BY item ASC', array(':url' => $url));
	$lCount = 0;
	$rCount = 0;

	foreach($listItems as $listItem){
		if($listItem->side == "left"){
			$leftList[$lCount]['title'] = $listItem->title;
			$leftList[$lCount]['body'] = $listItem->body;
			$lCount++;
		}elseif($listItem->side == "right"){
			$rightList[$rCount]['title'] = $listItem->title;
			$rightList[$rCount]['body'] = $listItem->body;
			$rCount++;
		}
	}

	$bodyModel['section_left'] = $leftList;
	$bodyModel['section_right'] = $rightList;

	if($_SESSION['success_message']){
		$bodyModel['success_message'] = $_SESSION['success_message'];
		unset($_SESSION['success_message']);
	}

	$body = $m->loadTemplate('page');
	echo $body->render($bodyModel);
}

