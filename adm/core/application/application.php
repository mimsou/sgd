<?php

class mainframe {

    public $vars;

    // initialisation de l'application
    public function init() {
        require PATH_ROOT . DS . 'core\application\config.php';
        $config = config::getInstance();
        $config->load_Zend_framework();
        $config->load_application_class();
    }

    public function has_params($params) {
        if (isset($_GET[$params]) || empty($_GET[$params])) {
            return true;
        }
        return false;
    }

    public function get_params() {
        $controler = new controler();
        return explode("/", $controler->get_passed_vars("opt"));
    }

    //authentification si non renvoie vers login
    public function render() {
        //charger Zend framework
        $controler = new controler();

        if ($this->has_params("opt")) {

             // recupérer les variable de l'url
             $param = $this->get_params();

            //controlleur
            $ctl = $param[0];

            //action
            $task = isset($param[1]) ? $param[1] : 'index';
            $task = !empty($task) ? $task : "index";


             //si composant existe passer a excution
            if (file_exists(BASE_PATH . DS . 'com/' . $ctl . "/" . $ctl . '.php')) {

                require (BASE_PATH . DS . 'com/' . $ctl . "/" . $ctl . '.php');

                $controle = new $ctl();
                //si controlleur (action) existe passer a excution
                if (method_exists($controle, $task)) {
                    $controle->$task();
                } else {
                    echo "Erreur 404 - task not found";
                }
            } else {
                $controler->redirectphp(WEBROOT . "main");
            }
        } else {
            $controler->redirectphp(WEBROOT . "main");
        }
    }

}

?>
