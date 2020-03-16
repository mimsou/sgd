<?php

class config {

    private $vars;
    private static $_instance = null;

    private function __construct() {

        //Définition du mode
        if (MODE == "ad") {
            define("RES_PATH", BASE_PATH);
        } else if (MODE == "front") {
            define("RES_PATH", PATH_ROOT);
        };

        define("WEBROOT", str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]));
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new config();
        }
        return self::$_instance;
    }

    public static function init() {
        $config = self::getInstance();
        $config->load_Zend_framework();
        $config->chk_install();
        $config->load_application_class();
        $config->Load_business_class();
    }

    private function chk_install() {

        if (!file_exists(PATH_ROOT . DS . "variables.php")) {

            if (isset($_POST["config"])) {

                try {


                    $db = Zend_Db::factory('Pdo_Mysql', array(
                                'host' => $_POST["serveur"],
                                'username' => $_POST["util"],
                                'password' => $_POST["passbd"],
                                'dbname' => $_POST["base"]
                    ));

                    $dbname = $_POST["base"];

                    $schema = file_get_contents(PATH_ROOT . DS . "install" . DS . "db_config.sql");
                    $schema = explode(";\n", $schema);
                    $schema = array_map('trim', $schema);
                    $schema = array_filter($schema, 'strlen');


                    foreach ($schema as $sql) {
                        $db->query($sql);
                    }

                    $db->query("INSERT INTO `$dbname`.`util` (
                                `IDUTIL` ,
                                `UTILOG` ,
                                `UTIPWD` ,
                                `UTINOM` ,
                                `UTIPNOM` ,
                                `UTIDCR` ,
                                `UTImat` ,
                                `OPECOD` ,
                                `OPEEXE` ,
                                `PROFCOD` ,
                                `ETATCOD` ,
                                `idPRF`
                                )
                                VALUES (
                                1 , '" . $_POST["loginsa"] . "', '" . md5($_POST["passa"]) . "', 'admin', 'admin', NOW(), '11111', '0', '2014', '1', '1', '0')");

                    $rootarray = explode(DS, PATH_ROOT);
                    $root_dir = $rootarray[count($rootarray) - 1];

                    $confpfp = '<?php' . " \n";
                    $confpfp .= 'class vars {' . " \n";
                    $confpfp .= '//chemin' . " \n";
                    $confpfp .= 'public $root_dir = "' . $root_dir . '";' . " \n";
                    $confpfp .= 'public $ad_dir = "adm";' . " \n";
                    $confpfp .= '//base' . " \n";
                    $confpfp .= 'public $driver = "pdo_mysql";' . " \n";
                    $confpfp .= 'public $db_host = "' . $_POST["serveur"] . '";' . " \n";
                    $confpfp .= 'public $db_name = "' . $_POST["base"] . '";' . " \n";
                    $confpfp .= 'public $db_user = "' . $_POST["util"] . '";' . " \n";
                    $confpfp .= 'public $db_pass = "' . $_POST["passbd"] . '";' . " \n";
                    $confpfp .= "public " . '$driverOptions' . " = array('1002'=> ".'"SET NAMES'. "'UTF8' COLLATE 'utf8_general_ci'".'");' . " \n";
                    $confpfp .= 'public $charset = ' . "'UTF8'" . ';' . " \n";
                    $confpfp .= '}' . " \n";
                    $confpfp .= '?>' . " \n";

                    $handle = fopen(PATH_ROOT . DS . "variables.php", "a+");
                    fwrite($handle, $confpfp);
                    fclose($handle);

                    header("location:/$root_dir/login");
                } catch (Exception $e) {
                    $message = $e->getMessage();
                }
            }

            require PATH_ROOT . DS . "install" . DS . "install.php";
            die();
        } else {
            //Chargement des variable de configuration
            require PATH_ROOT . DS . "variables.php";
            $this->vars = new vars();
            define("SCRIPTROOT", $this->_full_url($_SERVER));
        }
    }

    private function _full_url($s) {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
        if (MODE == "front") {
            return $protocol . '://' . $host . $port . "/" . $this->vars->root_dir;
        } else if (MODE == "ad") {
            return $protocol . '://' . $host . $port . "/" . $this->vars->root_dir . "/" . $this->vars->ad_dir;
        }
    }

    public function getVars() {
        return $this->vars;
    }

    private function load_application_class() {
        require PATH_ROOT . DS . "core/application/includes.php";
    }

    private function load_Zend_framework() {
        $path_library = PATH_ROOT . DS . 'core/library';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path_library);
        require (PATH_ROOT . DS . 'core/library/Zend/Loader.php');
        require (PATH_ROOT . DS . 'core/library/Zend/Loader/Autoloader.php');
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
    }

    private function Load_business_class() {
        $path_library = PATH_ROOT . DS . 'core';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path_library);
        Zend_Loader_Autoloader::getInstance()->registerNamespace('Metier_');

        $path_library = PATH_ROOT . DS . 'core\Metier';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path_library);

        $path_library = PATH_ROOT . DS . 'core\Metier';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path_library);

        $path_library = PATH_ROOT . DS . 'core\metier_tr';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path_library);
    }

}

?>
