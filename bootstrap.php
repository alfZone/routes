<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

//echo __DIR__;

require __DIR__ . '/autoload.php';

@session_start();

try {

	include __DIR__ . '/routes/routes.php';

} catch(\Exception $e){
	echo $e->getMessage();
}

?>