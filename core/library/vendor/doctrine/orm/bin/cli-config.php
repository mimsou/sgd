<?php

ini_set('display_errors', 0);

error_reporting(0); 

define('BASE_PATH', dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) );

define('PATH_ROOT', dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) );

define( 'MODE', 'front' );

define( 'DS', DIRECTORY_SEPARATOR );

require BASE_PATH.DS.'core/application/application.php';

$main = new mainframe();

$main->init();

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper(Model::$em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper(Model::$em)
));
return $helperSet;
