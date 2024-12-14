<?php

$settings = Generic::application_constants();
$settings["DB_DATE_FORMAT"]	="Y-m-d";
$settings["DB_DT_FORMAT"]	="Y-m-d H:i:s";
$settings["FRONT_TIME"]		="g:ia";
$settings["DB_TIME"]		="H:i:s";
$settings["DELIMITER"]      = "\t";
$settings["ATTACHMENT_PATH"]= "/var/www/mtest/attachments_copy/";

$AllocatedPMHours = explode(",",$settings['_AllocatedPmHours']);
$AllocatedHoursArray = array();
foreach($AllocatedPMHours as $rows){
	$AllocatedPmHoursTemp = explode("~",$rows);
	$AllocatedHoursArray[strtolower($AllocatedPmHoursTemp[0])] = strip_tags(strtolower($AllocatedPmHoursTemp[1]));
}

/* Global Define */
$settings['DEFAULT_PAGE_LIMIT'] = 100;

/* Contract Listing Section Start */
$settings['CONTRACT_TYPE'] = array(
		1=>'Total Coverage', 
		2=>'Lump Sum', 
		3=>'Fixed Labor', 
		4=>'Itemized Fixed Labor', 
		5=>'Itemized Lump Sum', 
	);

$settings['CONTRACT_FILE_PATH'] = 'uploads/contract_files';
/* Contract Listing Section End */


return $settings;