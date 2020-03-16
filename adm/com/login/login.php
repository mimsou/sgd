<?php

class login extends controler {

    public function index() {

        //check de l'authentification 
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->redirectphp(WEBROOT . "main");
        }


        $loginForm = new Zend_Form();


        $loginForm->setDecorators(
                array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'dl', 'class' => 'dl-horizontal')),
                    'Form'
        ));


        $loginForm->addElement('text', 'login', array(
            'label' => 'Login:',
            'required' => true,
            'class' => 'form-control',
            'filters' => array('StringTrim'),
        ));

        // Un bouton d'envoi
        $loginForm->addElement('password', 'password', array(
            'label' => 'mot de passe:',
            'required' => true,
            'class' => 'form-control',
            'filters' => array('StringTrim'),
        ));


        // Un bouton d'envoi
        $loginForm->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Login',
        ));

        // Et une protection anti CSRF
        $loginForm->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

        $rq = new Zend_Controller_Request_Http();

        if ($rq->isPost()) {
            $db = Model::getDb();
            if ($loginForm->isValid($rq->getPost())) {
                $adapter = new Zend_Auth_Adapter_DbTable(
                        $db, 'util', 'UTILOG', 'UTIPWD', 'MD5(?)'
                );

                $adapter->setIdentity($loginForm->getValue('login'));
                $adapter->setCredential($loginForm->getValue('password'));

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($adapter);

                if ($result->isValid()) {
                    $storage = $auth->getStorage();
                    $storage->write($adapter->getResultRowObject(array("IDUTIL", "UTIPNOM","UTILOG", "UTINOM", "idPRF")));
                    controler::intiEvirement();
                    Message::set_info_msg("Bienvenu");
                    $this->redirectphp(WEBROOT . "main");
                } else {
                    Exceptions::setLastException(Exceptions::getExceptionByCode(1001));
                    $this->redirectphp(WEBROOT . "login");
                }
            }
        }

        $loginForm->setView(new Zend_View);

        view::setContext(get_class($this));

        view::addCss("stl1.css");

        view::addJs("cheklist.js");

        view::newdata($loginForm, "form");

        view::render("tpl");
    }

}

?>