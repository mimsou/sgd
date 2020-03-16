<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Model {

    public static $em;

    public function __construct() {
        Zend_Db_Table_Abstract::setDefaultAdapter($this->getDb());
    }

    public static function init_doctrine() {

        $paths = array(PATH_ROOT . DS . "core/Metier");
        $isDevMode = true;
        $vars = new vars();
        $dbParams = array(
            'driver' => $vars->driver,
            'host' => $vars->db_host,
            'user' => $vars->db_user,
            'password' => $vars->db_pass,
            'dbname' => $vars->db_name,
            'charset'       =>  $vars->charset,
            'driverOptions' => $vars->driverOptions
        );

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
        self::$em = EntityManager::create($dbParams, $config);
    
        
    }

    public static function getDb() {
        try {
            $config = config::getInstance();
            $vars = $config->getVars();
            $db = Zend_Db::factory('Pdo_Mysql', array(
                        'host' => $vars->db_host,
                        'username' => $vars->db_user,
                        'password' => $vars->db_pass,
                        'dbname' => $vars->db_name
            ));

            $db->query("SET NAMES utf8");

            $defadabt = self::default_adapter($db);
            Zend_Db_Table_Abstract::setDefaultAdapter($defadabt);
            return $defadabt;
        } catch (Exception $ex) {
            if (!($ex->getMessage() == null)) {
                Message::set_info_msg("Erreur de connection");
                echo json_encode(array("etat" => "1"));
            } else {
                echo json_encode(array("etat" => "0"));
            }
        }
    }

    public static function getDb3() {

        $config = config::getInstance();
        $vars = $config->getVars();
        $db = Zend_Db::factory('Pdo_Mysql', array(
                    'host' => $vars->db_host,
                    'username' => $vars->db_user,
                    'password' => $vars->db_pass,
                    'dbname' => 'habillement'
        ));

        $defadabt = self::default_adapter($db);
        Zend_Db_Table_Abstract::setDefaultAdapter($defadabt);
        return $defadabt;
    }

    public static function getDb2() {
        try {
            $config = config::getInstance();
            $vars = $config->getVars();

            $db = Zend_Db::factory('Odbc_Db2', array(
                        'dsn' => $vars->db_dsn,
                        'user' => $vars->db_user_db2,
                        'password' => $vars->db_pass_db2,
            ));


            $defadabt = self::default_adapter($db);
            Zend_Db_Table_Abstract::setDefaultAdapter($defadabt);
            return $defadabt;
        } catch (Exception $ex) {

            if (!($ex->getMessage() == null)) {
                Message::set_info_msg("Erreur de connection");
                echo json_encode(array("etat" => "1"));
            } else {
                echo json_encode(array("etat" => "0"));
            }
        }
    }

    public static function default_adapter($defaul) {
        return $defaul;
    }

}

?>
