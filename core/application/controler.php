<?php

class controler {

    public $taskName = array();
    private static $_instance = null;
    private static $modul = null;

    function __construct() {
        
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new controler();
        }
        return self::$_instance;
    }

    public static function get_passed_vars($name, $default = null, $hash = 'default', $type = 'none', $mask = 0) {

        // Ensure hash and type are uppercase
        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        $type = strtoupper($type);
        $sig = $hash . $type . $mask;

        // Get the input hash
        switch ($hash) {
            case 'GET' :
                $input = &$_GET;
                break;
            case 'POST' :
                $input = &$_POST;
                break;
            case 'FILES' :
                $input = &$_FILES;
                break;
            case 'COOKIE' :
                $input = &$_COOKIE;
                break;
            case 'ENV' :
                $input = &$_ENV;
                break;
            case 'SERVER' :
                $input = &$_SERVER;
                break;
            default:
                $input = &$_REQUEST;
                $hash = 'REQUEST';
                break;
        }

        if (isset($GLOBALS['_JREQUEST'][$name]['SET.' . $hash]) && ($GLOBALS['_JREQUEST'][$name]['SET.' . $hash] === true)) {
            // Get the variable from the input hash
            $var = (isset($input[$name]) && $input[$name] !== null) ? $input[$name] : $default;
        } elseif (!isset($GLOBALS['_JREQUEST'][$name][$sig])) {
            if (isset($input[$name]) && $input[$name] !== null) {
                // Get the variable from the input hash and clean it
                $var = $input[$name];

                // Handle magic quotes compatability
                if (get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')) {
                    
                }

                $GLOBALS['_JREQUEST'][$name][$sig] = $var;
            } elseif ($default !== null) {
                // Clean the default value
                $var = $default;
            } else {
                $var = $default;
            }
        } else {
            $var = $GLOBALS['_JREQUEST'][$name][$sig];
        }
        return $var;
    }

    public static function has_params($params) {
        if (isset($_GET[$params]) || !empty($_GET[$params])) {
            return true;
        }
        return false;
    }

    public static function dfrm($date) {
        $pos = strpos($date, "-");
        if ($pos === false) {
            $arraydate = explode("/", $date);
            return $arraydate[2] . "-" . $arraydate[1] . "-" . $arraydate[0];
        } else {
            return $date;
        }
    }

    public static function get_params($index = null) {
        $params = explode("/", self::get_passed_vars("opt"));
        if ($index == null)
            return $params;
        return $params[$index];
    }

    public function registerTaskName($taskname) {
        $namespace = new Zend_Session_Namespace();

        if (isset($namespace->taskName)) {
            if (!isset($namespace->taskName[$this->_getCurentParam()])) {
                $key = $this->_getCurentParam();
                if ($key !== "")
                    $namespace->taskName[$key] = $taskname;
            }
        } else {
            $namespace->taskName = array();
            $key = $this->_getCurentParam();
            if ($key !== "")
                $namespace->taskName[$key] = $taskname;
        }
    }

    public function _getCurentParam() {
        $fullurl = str_replace("/SGS/", "", $_SERVER["REDIRECT_URL"]);
        $lastshar = substr($fullurl, -1);
        if ($lastshar == "/") {
            $fullurl = substr_replace($fullurl, "", -1);
        }

        $param = explode("/", $fullurl);
        return end($param);
    }

    public static function redirectjavascipt($path) {
        print ("<script language = \"JavaScript\">");
        print ("location.href = '" . $path . "';");
        print ("</script>");
    }

    public static function redirectphp($path) {
        ob_start();
        header("location:$path");
        ob_flush();
        exit();
    }

    public static function formatfloat($rss) {

        foreach ($rss as $key => $rs) {
            foreach ($rs as $keys => $item) {
                if (floatval($rss[$key][$keys]) != 0 && preg_match("/\//", (string) $rss[$key][$keys]) != 1 && preg_match("/-/", (string) $rss[$key][$keys]) != 1) {
                    $rss[$key][$keys] = round($rss[$key][$keys], 3); //number_format($rss[$key][$keys], 2, '.', ' ');;
                }
            }
        }

        return $rss;
    }

    public static function intiEvirement() {

        $idprf = Zend_Auth::getInstance()->getIdentity()->idPRF;
        $tablesrel = new Zend_Db_Table('prf_has_mag');
        $qrmag = $tablesrel->select()->where("idPRF = ?", $idprf);
        $rsmag = $qrmag->query();
        $mag = $rsmag->fetchAll();

        $env = new Zend_Session_Namespace('env');
        $env->mag = $mag;

        $date = new Zend_Date();
        $year = $date->get("y");
        $env->exe = $year;
    }

    public static function setEvirement($opt, $var) {

        $env = new Zend_Session_Namespace('env');

        switch ($opt) {
            case "mag" : $env->magslect = $var;
                break;
            case "exe" : $env->exe = $var;
                break;
        }
    }

    public static function getEvirement($opt) {

        $env = new Zend_Session_Namespace('env');

        switch ($opt) {
            case "mag" : return $env->magslect;
                break;
            case "exe" : return $env->exe;
                break;
        }
    }

    public static function connected() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return true;
        }
        return false;
    }

    public static function journalise($params) {

        if (Zend_Auth::getInstance()->hasIdentity()) {

            $idprf = Zend_Auth::getInstance()->getIdentity()->idPRF;
            $idutil = Zend_Auth::getInstance()->getIdentity()->IDUTIL;
            $prenom = Zend_Auth::getInstance()->getIdentity()->UTIPNOM;
            $nom = Zend_Auth::getInstance()->getIdentity()->UTINOM;
            $ip = $_SERVER['REMOTE_ADDR'];

            $db = Model::getDb();
            $tables = new Zend_Db_Table('modul');

            if (!isset($params[1]))
                $params[1] = "index";

            $qr = $tables->select()->where("MODULpath = ?", $params[0])->where("MODULtask = ?", $params[1])->where("MODULmod = ?", MODE);
            $rs = $qr->query();
            $module = $rs->fetch();

            $params = self::get_passed_vars("data");

            if (empty($params)) {
                $params = "";
            }

            if (!empty($module)) {
                $data = array(
                    "idMODUL" => $module["idMODUL"],
                    "actionOPER" => $module["MODULtask"],
                    "descriptOPER" => $module["MODULnom"],
                    "catOPER" => $module["MODULcat"],
                    "dateOPER" => new Zend_Db_Expr('NOW()'),
                    "idPRF" => $idprf,
                    "IDUTIL" => $idutil,
                    "UTINOM" => $nom,
                    "UTIPNOM" => $prenom,
                    "paramOPER" => var_export($params, true),
                    "ipOPER" => $ip,
                );

                $db->insert("oper", $data);
            }
        }
    }

    public static function getModul() {
        return self::$modul;
    }

    public static function checkAcess($params) {


        $request = new Zend_Controller_Request_Http();
        $rqtype = $request->isXmlHttpRequest();
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            Exceptions::setLastException(Exceptions::getExceptionByCode(1001));
            echo json_encode(array("etat" => "2"));
            if (!$rqtype) {
                self::redirectphp(WEBROOT . "login");
            }
        } else {

            $idprf = Zend_Auth::getInstance()->getIdentity()->idPRF;

            $db = Model::getDb();
            $tables = new Zend_Db_Table('modul');

            if (!isset($params[1]))
                $params[1] = "index";

            $qr = $tables->select()->where("MODULpath = ?", $params[0])->where("MODULtask = ?", $params[1])->where("MODULmod = ?", MODE);
            $rs = $qr->query();
            self::$modul = $rs->fetch();
            $idmodule = self::$modul["idMODUL"];

            $qrmd = $db->select("idMODUL")->from("modul", "idMODUL")->where("idMODUL IN ?", $db->select()->from("droit_has_modul", "idMODUL")->where("idDROIT IN ?", $db->select()->from("prf_has_droit", "idDROIT")->where("idPRF = ?", $idprf)));

            $rsmd = $qrmd->query();
            $md = $rsmd->fetchAll();

            $mdarray = array();
            foreach ($md as $item) {
                array_push($mdarray, $item["idMODUL"]);
            }

            /*
              if ($idprf == 0) {
              ini_set('display_errors', true);
              }else{
              ini_set('display_errors', false);
              }
             * */

            if (!in_array($idmodule, $mdarray) && $idprf != 0) {
                Exceptions::setLastException(Exceptions::getExceptionByCode(1005));
                echo json_encode(array("etat" => "2"));
                if (!$rqtype) {
                    self::redirectphp(WEBROOT . "main");
                }
                die();
            }
        }
    }

}

?>
