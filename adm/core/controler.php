<?php

class controler {

    public $tpl = "";
    public $data = array();
    public $msg;

    function __construct() {
        $this->msg = new App_Message();
    }

    public function has_params($params) {
        if (isset($_GET[$params]) || empty($_GET[$params])) {
            return true;
        }
        return false;
    }

    public function get_params($params) {
        return explode("/", $_GET[$params]);
    }
    

    public function render($filename, $head) {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $head->hasident = true;
        }
        $tplroot = WEBROOT . "tpl/" . get_class($this) . "/";
        $this->tpl = ADROOT . "tpl/" . get_class($this) . "/" . $filename . ".php";
        require(ADROOT . "tpl/index.php");
    }

    public function newdata($data, $ref) {
        $this->data[$ref] = $data;
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
        $fullurl = str_replace("/SGS/ad/", "", $_SERVER["REDIRECT_URL"]);
        $lastshar = substr($fullurl, -1);
        if ($lastshar == "/") {
            $fullurl = substr_replace($fullurl, "", -1);
        }

        $param = explode("/", $fullurl);
        return end($param);
    }

    public function renderCom() {
        require($this->tpl);
    }
    
     public function message() {
        $msg = $this->msg;
        require(PATH_ROOT . DS . "tpl\message.php");
    }

    public function redirectjavascipt($path) {
        print ("<script language = \"JavaScript\">");
        print ("location.href = '" . $path . "';");
        print ("</script>");
    }

    public function redirectphp($path) {
        ob_start();
        header("location:$path");
        ob_flush();
    }

    public function checkAcess() {

        // recupéré l'identité
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->redirectphp(WEBROOT . "login");
        }
    }

}

?>
