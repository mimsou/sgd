<?php

class mainframe {

    public $vars = 1;

// initialisation de l'application
    public function init() {

        require PATH_ROOT . DS . 'core/application/config.php';
        config::init();
        Model::init_doctrine();
        //Model::addConnections();
    }

    public function render() {

        Exceptions::init();
        View::init();
        
            
         set_exception_handler(function($e) {
               if (Zend_Auth::getInstance()->getIdentity()->IDUTIL == 1) {
                    
                       echo "Uncaught exception: " , $exception->getMessage(), "\n";
                } else {
                Exceptions::setLastException("Erreur system Merci de contacter un administrateur");
                echo json_encode(array("etat" => "2"));
            }
         });
        
       

        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            
            if (!(error_reporting() & $errno)) {
                // Ce code d'erreur n'est pas inclus dans error_reporting()
                return;
            }
            
            if (Zend_Auth::getInstance()->getIdentity()->IDUTIL == 1) {
                
                switch ($errno) {
                    case E_USER_ERROR:
                        $msgerror = "<b>Mon ERREUR</b> [$errno] $errstr<br />\n";
                        $msgerror .= "  Erreur fatale sur la ligne $errline dans le fichier $errfile";
                        $msgerror .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                        $msgerror .= "Arrêt...<br />\n";

                        break;

                    case E_USER_WARNING:
                        $msgerror = "<b>Mon ALERTE</b> [$errno] $errstr<br />\n";
                        break;

                    case E_USER_NOTICE:
                        $msgerror = "<b>Mon AVERTISSEMENT</b> [$errno] $errstr<br />\n";
                        break;

                    default:
                        $msgerror = "Type d'erreur inconnu : [$errno] $errstr<br />\n";
                        break;
                }
                
                Exceptions::setLastException($msgerror);
                echo json_encode(array("etat" => "2"));
            } else {
                Exceptions::setLastException("Transaction annulé merci de vérifier vos données avec l'administrateur du systéme");
                echo json_encode(array("etat" => "2"));
            }
            Model::$em->getConnection()->rollBack();        
            return true;
            
        });

        // recupérer les variable de l'url
        $param = controler::get_params();

        //controlleur
        $ctl = $param[0];

        //action
        $task = isset($param[1]) ? $param[1] : 'index';
        $task = !empty($task) ? $task : "index";

        //controle d'identification
        if ($ctl != "login" && $task != "asyn_get_all_right")
            controler::checkAcess($param);

        define('CTL', $ctl);

        define('TASK', $task);

        if (controler::has_params("opt") && file_exists(BASE_PATH . DS . 'com/' . $ctl . "/" . $ctl . '.php')) {

            require (BASE_PATH . DS . 'com/' . $ctl . "/" . $ctl . '.php');

            $controle = new $ctl();

            //si controlleur (action) existe passer a excution
            if (method_exists($controle, $task)) {
                if (strpos($task, "asyn_") !== false) {
                    $request = new Zend_Controller_Request_Http();
                    if ($request->isXmlHttpRequest() || Zend_Auth::getInstance()->getIdentity()->IDUTIL == 1) {
                        $controle->$task();
                        controler::journalise($param);
                    } else {
                        Exceptions::setLastException(Exceptions::getExceptionByCode(1003));
                        controler::redirectphp(WEBROOT . "main");
                    }
                } else {
                    $controle->$task();
                    controler::journalise($param);
                }
            } else {
                Exceptions::setLastException(Exceptions::getExceptionByCode(1002));
                controler::redirectphp(WEBROOT . "main");
            }
        } else {
            Exceptions::setLastException(Exceptions::getExceptionByCode(1002));
            controler::redirectphp(WEBROOT . "main");
        }
    
    }
    
    
    
    

}

?>
