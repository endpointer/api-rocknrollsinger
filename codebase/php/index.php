<?php

require_once 'vendor/autoload.php';

// M 	= Module
// EP	= Endpoint

$_ENV[RockNRollSingerApi\Constants::CONFIG_ID] = parse_ini_file('.config.ini');

require_once	'src/RockNRollSingerApi' .

	'/Endpoint' .

	'/' . $_GET['M'] .

	'/' . $_GET['EP'] .

	'Endpoint.php';
