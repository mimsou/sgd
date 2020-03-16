<?php

class login extends controler {

    public function index() {

        //check de l'authentification 
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->redirectphp(WEBROOT . "main");
        }

        $rq = new Zend_Controller_Request_Http();

        if ($rq->isPost()) {
    
            try {

                    $db = Model::getDb();
            
                    $adapter = new Zend_Auth_Adapter_DbTable(
                            $db, 'util', 'utilog', 'utipwd', 'MD5(?)'
                    );

                    $adapter->setIdentity($rq->get("username"));
                    $adapter->setCredential($rq->get('password'));

                    $auth = Zend_Auth::getInstance();
                    $result = $auth->authenticate($adapter);
                
                    if ($result->isValid()) {
                        $storage = $auth->getStorage();
                        $storage->write($adapter->getResultRowObject(array("IDUTIL", "UTIPNOM", "UTILOG", "UTINOM", "idPRF")));
                        controler::intiEvirement();
                        Message::set_info_msg("Bienvenu");
                        $this->redirectphp(WEBROOT . "main");
                    } else {
                        Exceptions::setLastException(Exceptions::getExceptionByCode(1001));
                        $this->redirectphp(WEBROOT . "login");
                    }
                    
              
                
            } catch (Exception $ex) {
                
                if (!($ex->getMessage() == null)) {
                    Message::set_info_msg("Erreur de connection");
                    echo json_encode(array("etat" => "1"));
                } else {
                    echo json_encode(array("etat" => "0"));
                }
                
            }
        }

        view::setContext(get_class($this));
        
        view::addDefaultsCss();
        
         view::addDefaultJs();
        
        view::addCss("font-awesome.min");

        view::render("tpl");
    }

}

?>