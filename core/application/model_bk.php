<?php

class Model {

    protected static $methods = array();
    private static $connections = array();

    final public static function __callStatic($name, $arguments) {
        return call_user_func(self::$methods[$name], $arguments);
    }

    final public static function addMethod($name, $fn) {
        self::$methods[$name] = Closure::bind($fn, NULL, __CLASS__);
    }

    public function __construct() {
        self::addConnections();
        Zend_Db_Table_Abstract::setDefaultAdapter(self::getDb());
    }

    public static function addConnections() {
        $cnts = parse_ini_file(PATH_ROOT . DS . "connections.ini", true);
        foreach ($cnts as $key => $cnt) {
            $prm = var_export($cnt["param"],true);
            $fntstr = 'self::addMethod($key, function() {
                try {
                    
                    $db = Zend_Db::factory("'.$cnt["factory"].'", '.$prm.');
                    
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
            });';
            eval($fntstr);
        }
    }

    public static function default_adapter($defaul) {
        return $defaul;
    }

}

?>
