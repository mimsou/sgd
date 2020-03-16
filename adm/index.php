<?php
/**
Administration
* @version		1.0
* @package		Gesto
* @copyright	Copyright (C) 2005 - 2013 Transtu. All rights reserved.
* @developpeur  Souheyeb mimouni

*/

//Affichage d'erreurs 
//ini_set('display_errors', 0); 

define('BASE_PATH', dirname(__FILE__) );

define( 'MODE', 'ad' );

define( 'DS', DIRECTORY_SEPARATOR );

$parts = explode( DS, BASE_PATH );

array_pop( $parts );

define( 'PATH_ROOT',implode( DS, $parts ) );

require_once PATH_ROOT . DS . 'core/library/vendor/autoload.php';

require PATH_ROOT.DS.'core/application/application.php';
$main = new mainframe();
$main->init();
$main->render();
?>
