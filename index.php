<?php




/**
* @version		1.0
* @package		Gesto
* @copyright	Copyright (C) 2005 - 2013 Transtu. All rights reserved.
* @developpeur  Souheyeb mimouni

*/

//Affichage d'erreurs 
ini_set('display_errors', false); 



define('BASE_PATH', dirname(__FILE__) );

define('PATH_ROOT',BASE_PATH );

define( 'MODE', 'front' );

define( 'DS', DIRECTORY_SEPARATOR );

require_once PATH_ROOT . DS . 'core/library/vendor/autoload.php';

require BASE_PATH.DS.'core/application/application.php';

$main = new mainframe();

$main->init();
try{
$main->render();
}catch (Exception $e){
    echo $e->getMessage();
}



?>
