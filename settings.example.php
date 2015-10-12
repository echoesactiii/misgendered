<?php

////// Parameters for connecting to the MySQL database. //////
$settings['database']['password'] = "dbpass";
$settings['database']['username'] = "dbuser";
$settings['database']['database'] = "database";
$settings['database']['server'] = "127.0.0.1";

////// These are the settings for the site itself. //////
// This is the title as displayed on site pages.
$settings['site']['title'] = "Misgendered Project";
// This is the domain the site is hosted on - it gets used
// to print reference URLs in the letters.
$settings['site']['domain'] = "misgendered.yourdomain";
// This is the contact email address. It is used as the FROM
// header in error reports, and printed in the signature of letters.
$settings['site']['email'] = "your@email.com";
// This is the email address automated error reports should be sent to.
$settings['site']['error_email'] = "your@email.com";
// This is the name of the site as it is printed in the signature of letters.
$settings['site']['signature'] = 'The Misgendered Project';
// Setting actually_send_letters to false prevents the script from calling
// the pc2paper API so that you can test it without actually sending letters.
// disabled_letter_send_makes_errors is only used if acutally_send_letters is
// false - if disabled_letter_send_makes_errors is true, then attempts
// to generate a letter will result in a generic pc2paper API error being
// simulated. If it is false, then a generic pc2paper API success will be
// generated, allowing testing of error pages.
$settings['site']['actually_send_letters'] = false;
$settings['site']['disabled_letter_send_makes_errors'] = false;
// This is the "url" field of entries in the database that are pulled if
// a page cannot be found.
$settings['site']['404_page'] = "404";

////// This section controls the navigation for the users of the site //////
// This should be an array of names and URLs that are shown at
// the top of each page that end-users see on the site.
$settings['nav'][0]['name'] = 'Home';
$settings['nav'][0]['url'] = '/home';
$settings['nav'][1]['name'] = 'About & FAQ';
$settings['nav'][1]['url'] = '/about';
$settings['nav'][2]['name'] = 'Donate';
$settings['nav'][2]['url'] = '/donate';

////// This section controls the navigation for the reference pages of the site //////
// This is the URL prefix for the reference pages of the site.
// It is used to compile the links that are printed into the letters.
$settings['reference']['prefix'] = "letter";
// This should be an array of names and URLs that are shown at
// the top of each page that people who were referred by the link
// in a letter see on the site.
$settings['reference']['nav'][0]['name'] = "What is this?";
$settings['reference']['nav'][0]['url'] = "/letter/who-are-we";
$settings['reference']['nav'][1]['name'] = "About misgendering";
$settings['reference']['nav'][1]['url'] = "/letter/misgendered";
$settings['reference']['nav'][2]['name'] = "About transphobia";
$settings['reference']['nav'][2]['url'] = "/letter/transphobia";
$settings['reference']['nav'][3]['name'] = "About inclusion";
$settings['reference']['nav'][3]['url'] = "/letter/inclusion";
$settings['reference']['nav'][4]['name'] = "About facilities";
$settings['reference']['nav'][4]['url'] = "/letter/facilities";

////// This section contains the settings for example letter generation //////
// prefix is the URL prefix (followed by the letter type) at which
// example letters can be accessed. other_info_prefix is the same,
// except that letters generated will have the "other_info" field
// filled in as well.
$settings['examples']['prefix'] = "example";
$settings['examples']['other_info_prefix'] = "extended-example";
// These are the simulated form inputs that are used in example
// letter generation.
$settings['examples']['name'] = "The Bees Workshop";
$settings['examples']['house'] = "48";
$settings['examples']['street'] = "Swan Road";
$settings['examples']['city'] = "London";
$settings['examples']['postcode'] = "N34 63ZQ";
$settings['examples']['other_info'] = "I went to the store, and a clerk told me that I couldn't purchase a women's product becuase I 'looked like a man'. This happened at 12:30 on Saturday the third of September, the clerk was named Ronald.";

////// These control the URLs that can be redirected to based on conditions //////
// This should point to your home page - whatever you set this
// to will be the path to your hompeage (in addition to /).
$settings['pages']['home'] = '/home';
// This is the page a user should be sent to after their letter
// has been set.
$settings['pages']['success'] = '/donate';
// This is the page a user should be sent to if the pc2paper API
// returns an error.
$settings['pages']['error'] = '/error';
// This is the page a user should be sent if the rate-limiter has
// been triggered bt their attempt to send a letter.
$settings['pages']['limiter'] = "/already-sent";
// Please see the 'Conditional Pages' section of the README for information
// on creating these pages. With the exception of the homepage, these do NOT
// create the page content for you, and operate only as redirects.

// The message that is passed through to the 'success' redirect if a letter
// was sent successfully.
$settings['messages']['letter_sent'] = "Your letter has been queued for sending. We would appreciate it if you would consider donating to cover the cost of your letter, and perhaps to cover the cost of some for those who may not afford it.";

////// This is the configuration for your letter types/templates //////
// This should be an array of friendly names (headings) for your letter
// types, and the name of the template. The mustache template for your
// letter should be the same as the letter name, and the letter name is
// also used to generate URLs referring to that letter as an example or
// as a reference page. This will auto-populate the drop-down on the
// homepage.
$settings['letters'][0]['heading'] = "I was misgendered by the staff. (unintentionally)";
$settings['letters'][0]['name'] = "misgendered";
$settings['letters'][1]['heading'] = "An intentionally transphobic incident occurred. (intentional misgendering, abuse, teasing, etc)";
$settings['letters'][1]['name'] = "transphobia";
$settings['letters'][2]['heading'] = "Their paperwork/computer system is not inclusive. (requires binary gender options, titles, etc)";
$settings['letters'][2]['name'] = "inclusion";
$settings['letters'][3]['heading'] = "I was denied access to toilets/changing rooms/etc.";
$settings['letters'][3]['name'] = "facilities";

///// These are the settings for the pc2paper API. //////
// Please see their API documentation for further information.
$settings['pc2paper']['username'] = "pc2paperuser";
$settings['pc2paper']['password'] = "pc2paperpass";
$settings['pc2paper']['endpoint'] = "http://www.pc2paper.co.uk/remote/clienttalkmulti.asp";
$settings['pc2paper']['country_code'] = 1;
$settings['pc2paper']['postage_id'] = 31;
$settings['pc2paper']['extras_id'] = ""; // Must be empty string, not unset, if unused.
$settings['pc2paper']['envelope_id'] = 1;
$settings['pc2paper']['paper_id'] = 1;

// This is your Google Place API key. It's used to do
// the search-as-you-type for addresses on the homepage.
// To get your API key, visit:
// https://developers.google.com/places/javascript/
$settings['google_places']['api_key'] = "google-place-api-key";

// These are your Recaptcha API keys. It's used to do
// spam protection and bot detection on the homepage.
// To get your API keys, visit the Recaptcha site.
$settings['recaptcha']['site_key'] = "recaptcha-site-key";
$settings['recaptcha']['secret_key'] = "recaptcha-secret-key"; 
?>