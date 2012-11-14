<?php
/**
 * Jesse Jordan 2012
 * api.php
 * 
 * SEOmozume 
 * 
 * Process all API input and provide output
 */

//enable all errors
error_reporting(E_ALL);

//positions of URL input for various endpoints
define('ENDPOINT_FORMAT',		0);
define('ENDPOINT_SECTION',		1);
define('ENDPOINT_SUBSECTION',	2);
define('ENDPOINT_BULLET_ID',	3);

/**
 * Automagically load required files
 * 
 * @param string $class_name
 */
function __autoload($class) {
	@include "{$_SERVER['DOCUMENT_ROOT']}/seomozume/_includes/class.{$class}.php";
	@include "{$_SERVER['DOCUMENT_ROOT']}/seomozume/_includes/interface.{$class}.php"; 	
}

/**
 * STEP 1: Try to load resume in CSV format first
 * If that fails, we try to load JSON format.  We will prefer 
 * whichever format we can load successfully first
 */
$resume_parser_obj = new csvParser('resume.csv');

if($resume_parser_obj->isParserReady() === FALSE) {
	//it didn't work, try the json parser
	$resume_parser_obj = new jsonParser('resume.json');
}

//if the parser is still not ready, trigger an error
if($resume_parser_obj->isParserReady() === FALSE) {
	trigger_error('No valid input found', E_USER_ERROR);
	exit();
}

//okay, process request
/**
 * STEP 2: Parse the request URL, which is provided in the "url" paramater by
 * Apache's URL rewriting rule set in included .htaccess file
 */
if(!strlen($_REQUEST['url'])) {
	trigger_error('No valid endpoints provided', E_USER_ERROR);
}

$endpoints_array = explode('/', $_REQUEST['url']);

/**
 * STEP 3: Determine format of output
 */
switch($endpoints_array[ENDPOINT_FORMAT]) {
	case 'xml':
		header('Content-Type: text/xml');
		$api_obj = new xmlApi();
		break;
	case 'json':
		$api_obj = new jsonApi();
		break;
	default:
		trigger_error('Unrecognized format '.$endpoints_array[ENDPOINT_FORMAT], E_USER_ERROR);
		break;
}

/**
 * STEP 4: Push parsing results into API object for later output
 */
//feed in the sections
foreach ($resume_parser_obj->getSections() as $section) {
	$api_obj->addSection($section);
	foreach ($resume_parser_obj->getSubSections($section) as $subsection) {
		$api_obj->addSubSection($section, $subsection);
		foreach ($resume_parser_obj->getBullets($section, $subsection) as $bullet_id => $bullet_data) {
			$api_obj->addBullet($section, $subsection, $bullet_id, $bullet_data);
		} 
	}	
}

/**
 * STEP 5: Output the desired results
 */
$api_obj->printOutput(
	isset($endpoints_array[ENDPOINT_SECTION]) && strlen($endpoints_array[ENDPOINT_SECTION]) ? $endpoints_array[ENDPOINT_SECTION] : null,
	isset($endpoints_array[ENDPOINT_SUBSECTION]) && strlen($endpoints_array[ENDPOINT_SUBSECTION]) ? $endpoints_array[ENDPOINT_SUBSECTION] : null,
	isset($endpoints_array[ENDPOINT_BULLET_ID]) && strlen($endpoints_array[ENDPOINT_BULLET_ID]) ? $endpoints_array[ENDPOINT_BULLET_ID] : null
);