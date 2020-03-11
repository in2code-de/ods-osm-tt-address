<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ods_osm']['tables']['tt_address'] = array(
	'FORMAT' => '%01.11f',
	'lon' => 'longitude',
	'lat' => 'latitude',
	'address' => 'address',
	'zip' => 'zip',
	'city' => 'city',
	'state' => 'region',
	'country' => 'country',
);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ods_osm']['tables']['sys_category']['MM']['tt_address'] = array(
	'local' => 'sys_category',
	'mm' => 'sys_category_record_mm',
	'foreign' => 'tt_address'
);
?>