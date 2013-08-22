<?php

require_once 'include/rb.php';
include("settings.php");
R::setup('mysql:host='.$dbHost.'; dbname='.$dbName,$dbUser,$dbPassword);

$payload = @json_decode($_REQUEST['json']);

if(!$payload){
	header("HTTP/1.0 400 Bad Request");
	header("Content-type: application/json");
	echo json_encode(array("error" => "Please pass valid json into 'json' parameter"));
	exit();
}

header("Content-type: application/json");

if($payload->action == "complete"){
	$results = R::find('organisation', 'name LIKE ?',
		array(
			"%".$payload->term."%"
		));

	$resultArr = R::exportAll($results);
	$i = 0;
	foreach($resultArr as $result){
		$typeaheadResults[$i]['value'] = $result['name'];
		$typeaheadResults[$i]['tokens'] = explode(' ', $result['name']);
		$typeaheadResults[$i]['address'] = $result['address'];
		$typeaheadResults[$i]['city'] = $result['city'];
		$typeaheadResults[$i]['postcode'] = $result['postcode'];
		$i++;
	}

	echo json_encode($typeaheadResults);

	exit();
}elseif($payload->action == "send"){
	$org = R::dispense('organisation');
	$org->name = $payload->name;
	$org->address = $payload->address;
	$org->city = $payload->city;
	$org->postcode = $payload->postcode;
	R::store($org);

	echo json_encode(array("success" => true));

	exit();
}