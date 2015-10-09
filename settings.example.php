<?php

$settings['database']['password'] = "dbpass";
$settings['database']['username'] = "dbuser";
$settings['database']['database'] = "misgendered";
$settings['database']['server'] = "127.0.0.1";

$settings['site']['title'] = "Misgendered!";
$settings['site']['domain'] = "misgendered.tld";
$settings['site']['email'] = "hi@misgenderd.tld";
$settings['site']['signature'] = 'The Misgendered Project'; // Organisation name at bottom of letters
$settings['site']['actually_send_letters'] = false;
$settings['site']['disabled_letter_send_makes_errors'] = true; // Allows for simulation of failure/success for testing error message pages

$settings['nav'][0]['name'] = 'Home';
$settings['nav'][0]['url'] = '/home';
$settings['nav'][1]['name'] = 'About & FAQ';
$settings['nav'][1]['url'] = '/about';
$settings['nav'][2]['name'] = 'Donate';
$settings['nav'][2]['url'] = '/donate';

$settings['letters'][0]['heading'] = "I was misgendered by the staff. (unintentionally)";
$settings['letters'][0]['name'] = "misgendered";
$settings['letters'][1]['heading'] = "An intentionally transphobic incident occurred. (intentional misgendering, abuse, teasing, etc)";
$settings['letters'][1]['name'] = "tranphobia";
$settings['letters'][2]['heading'] = "Their paperwork/computer system is not inclusive. (requires binary gender options, titles, etc)";
$settings['letters'][2]['name'] = "inclusion";
$settings['letters'][3]['heading'] = "I was denied access to toilets/changing rooms/etc.";
$settings['letters'][3]['name'] = "facilities";

$settings['pc2paper']['username'] = "pc2paperuser";
$settings['pc2paper']['password'] = "pc2paperpass";
$settings['pc2paper']['endpoint'] = "http://www.pc2paper.co.uk/remote/clienttalkmulti.asp";
$settings['pc2paper']['country_code'] = 1;
$settings['pc2paper']['postage_id'] = 31;
$settings['pc2paper']['extras_id'] = ""; // Must be empty string, not unset, if unused.
$settings['pc2paper']['envelope_id'] = 1;
$settings['pc2paper']['paper_id'] = 1;

?>