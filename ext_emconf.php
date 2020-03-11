<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'OpenStreetMap for tt_address',
	'description' => 'This extends ods_osm to use tt_address records.',
	'author' => 'Robert Heel',
	'author_email' => 'typo3@bobosch.de',
	'constraints' => [
		'depends' => [
			'ods_osm' => '2.0.0-',
			'tt_address' => '3.1.0-',
			'typo3' => '6.2.0-9.5.99',
		],
		'conflicts' => [
		],
		'suggests' => [
		],
	],
	'state' => 'stable',
	'version' => '4.0.0',
];
