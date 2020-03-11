<?php
if (!defined('TYPO3_MODE')) die('Access denied.');

$GLOBALS['TCA']['tt_address']['columns']['longitude']['config']['wizards'] = array(
	'coordinatepicker' => array(
		'type' => 'popup',
		'title' => 'LLL:EXT:ods_osm/locallang_db.xml:coordinatepicker.search_coordinates',
		'icon' => 'EXT:ods_osm/Resources/Public/Icons/osm.png',
		'module' => array(
			'name' => 'wizard_coordinatepicker',
		),
		'params' => array(
			'mode' => 'point',
		),
		'JSopenParams' => 'height=600,width=800,status=0,menubar=0,scrollbars=0',
	)
);
?>
