<?php

class EditMenu extends controler {

    public function index() {
        $this->redirectphp(BASE_PATH . "Editmenu/edit");
    }

    public function edit() {
        
        $this->checkAcess();
        $this->registerTaskName("Modification du menu");

        //Preparé l'entéte
        $head = new head();
        $head->setContext(get_class($this));
        $head->addDefaultsCss();
        $head->addDefaultJs();

        //Affichage
        $this->render("tpl", $head);
        
    }

    public function asyn_get_access_groupe() {
        
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();

        if ($request->isXmlHttpRequest()) {
        
            //initialisé la base de donné
            $model = new Model();
            $db = $model->getDb();

            //Recupérer les groupe d'access
            $qrg = $db->select()->from("s_access_groupe")->where('status = ?', 1);
            $resg = $qrg->query();
            $accessgroupe = $resg->fetchAll();

            echo json_encode($accessgroupe);
            
        } else {
            
            $this->redirectphp(SCRIPTADROOT);
            
        }
    }

    public function asyn_delete_item_access_list() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();
        if ($request->isXmlHttpRequest()) {

            $params = $this->get_passed_vars("data");
            if (!isset($params["id"]))
                return false;

            $model = new Model();
            $db = $model->getDb();

            $select1 = $db->select()->from(array('m' => 's_access_menu_list'))->where('m.id_acg = ?', $params["id"]);
            $select2 = $db->select()->from(array('l' => 's_access_user_list'))->where('l.id_acg = ?', $params["id"]);
            $select = $db->select()->union(array($select2, $select1));
            $res = $select->query();
            $result = $res->fetchAll();


            if (empty($result)) {
                if ($db->delete('s_access_groupe', 'id_acg =' . $params["id"])) {
                    echo json_encode(array("1", $params["id"]));
                } else {
                    echo json_encode(array("Erreur - la valeur n'existe pas", $params["id"]));
                }
            } else {
                echo json_encode(array("Erreur - Veuillez vidé le groupe", $params["id"]));
            }
        } else {
            $this->redirectphp(SCRIPTADROOT);
        }
    }

    public function asyn_menu_affect() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();
        if ($request->isXmlHttpRequest()) {

            if ($request->isXmlHttpRequest()) {
                $params = $this->get_passed_vars("data");
                if (!(isset($params["id_module"]) && isset($params["id_acg"]) && isset($params["id_groupe"])))
                    return false;


                //initialiser la table
                $model = new Model();
                $table = $model->getTable("menu_affectation");

                //recupéré l'utilisateur connecté

                $data = array(
                    'id_acg' => $params["id_acg"],
                    'id_module_g' => $params["id_groupe"],
                    'id_module_m' => $params["id_module"]
                );
                if ($table->insert($data))
                    echo json_encode('1');
                else
                    echo json_encode('0');
            } else {
                $this->redirectphp(SCRIPTADROOT);
            }
        }
    }

    public function asyn_get_modules() {
        
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();
        if ($request->isXmlHttpRequest()) {

            $params = $this->get_passed_vars("data");
            
            if (!(isset($params["mode"]) && isset($params["id_acg"]) && isset($params["vu"]) && isset($params["ajax"])))
                return false;

            $model = new Model();
            $db = $model->getDb();

            //Recupérer les groupe
            $qrm = $db->select()->from("s_modules")->where('mode = ?', $params["mode"]);
            $resm = $qrm->query();
            $modules = $resm->fetchAll();


            for ($i = 0; $i < count($modules); $i++) {

                $qrac = $db->select()->from("s_access_menu_list")->where('id_module = ?', $modules[$i]["id"])->where('id_acg = ?', $params["id_acg"]);
                $resac = $qrac->query();
                $acc = $resac->fetchAll();

                if (empty($acc)) {
                    $modules[$i]["access"] = 0;
                } else {
                    $modules[$i]["access"] = 1;
                }
            }

            header_remove();
            
            echo json_encode($modules);
            
        } else {
            $this->redirectphp(SCRIPTADROOT);
        }
        
    }

    public function asyn_get_groupes() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();
        if ($request->isXmlHttpRequest()) {

           $params = $this->get_passed_vars("data");
            if (!isset($params["mode"]))
                return false;


            $model = new Model();
            $db = $model->getDb();

//Recupérer les groupe
            $qrg = $db->select()->from("s_groupe")->where('interface = ?', $params["mode"]);
            $resg = $qrg->query();
            $groupes = $resg->fetchAll();

            echo json_encode($groupes);
        }else {
            $this->redirectphp(SCRIPTADROOT);
        }
    }

    public function asyn_create_access_groupe_item() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();
        if ($request->isXmlHttpRequest()) {



            //initialiser la table
            $model = new Model();
            $table = $model->getTable("access_groupe");

            //recupéré l'utilisateur connecté

            $data = array(
                'nom' => "nouveau group d'access",
                'status' => "1"
            );

            echo json_encode($table->insert($data));
        } else {
            $this->redirectphp(SCRIPTADROOT);
        }
    }

    public function asyn_change_module_name() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();

        if ($request->isXmlHttpRequest()) {
             $params = $this->get_passed_vars("data");
            if (!(isset($params["id_module"]) && isset($params["name"]))) 
                return false;
              
            //initialisé la base de donné
            $model = new Model();
            $db = $model->getDb();


            $data = array(
                'front_name' => $params["name"]
            );

            $where[] = "id=" . $params["id_module"];
            header_remove();
            if ($db->update("s_modules", $data, $where)) {
                echo json_encode(array(
                    "status" => "1",
                    "id" => $params["id_module"],
                    "name" => $params["name"]
                ));
            } else {
                echo json_encode(array(
                    "status" => "0",
                ));
            }
        } else {
            $this->redirectphp(SCRIPTADROOT);
        }
    }
    
    public function asyn_change_groupe_name() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();

        if ($request->isXmlHttpRequest()) {
             $params = $this->get_passed_vars("data");
            if (!(isset($params["id_groupe"]) && isset($params["name"]))) 
                return false;
              
            //initialisé la base de donné
            $model = new Model();
            $db = $model->getDb();


            $data = array(
                'front_name' => $params["name"]
            );

            $where[] = "id=" . $params["id_groupe"];
            header_remove();
            if ($db->update("s_groupe", $data, $where)) {
                echo json_encode(array(
                    "status" => "1",
                    "id" => $params["id_groupe"],
                    "name" => $params["name"]
                ));
            } else {
                echo json_encode(array(
                    "status" => "0",
                ));
            }
        } else {
            $this->redirectphp(SCRIPTADROOT);
        }
    }

    public function asyn_edit_access() {

        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();

        if ($request->isXmlHttpRequest()) {
            $params = $this->get_passed_vars("data");
            if (!(isset($params["id_module"]) && isset($params["id_acg"]) && isset($params["etas"])))
                return false;

            $model = new Model();
            $db = $model->getDb();

            if ($params["etas"] == "a") {
                $n = $db->delete("s_access_menu_list", array("id_acg= " . $params["id_acg"], "id_module = " . $params["id_module"]));
                if ($n) {
                    echo json_encode(array(
                        'id_module' => $params["id_module"],
                        'status' => "1"
                    ));
                } else {

                    echo json_encode(array(
                        'id_module' => $params["id_module"],
                        'status' => "0"
                    ));
                }
            } else if ($params["etas"] == "d") {

                $table = $model->getTable("access_menu_list");

                //recupéré l'utilisateur connecté

                $data = array(
                    'id_acg' => $params["id_acg"],
                    'id_module' => $params["id_module"]
                );

                if ($table->insert($data)) {
                    echo json_encode(array(
                        'id_module' => $params["id_module"],
                        'status' => "1"
                    ));
                } else {
                    echo json_encode(array(
                        'id_module' => $params["id_module"],
                        'status' => "0"
                    ));
                }
            }
        } else {
            $this->redirectphp(SCRIPTADROOT);
        }
    }

    public function asyn_delete_menu_affectation() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();

        if ($request->isXmlHttpRequest()) {
            $params = $this->get_passed_vars("data");
            if (!(isset($params["id_module"]) && isset($params["id_acg"]) && isset($params["id_groupe"])))
                return false;

            $model = new Model();
            $db = $model->getDb();
            
             if ($db->delete('s_menu_affectation', array('id_module_m = ?' => $params["id_module"],'id_acg = ?' => $params["id_acg"],'id_module_g = ?' => $params["id_groupe"]))) {
                 echo json_encode(array(
                     "status"=>"1",
                     "id_module"=>$params["id_module"]
                 ));
             }else {
                echo json_encode(array(
                     "status"=>"0"
                 ));
             }
        }
    }

    public function asyn_get_groupe_params() {
        $this->checkAcess();
        $request = new Zend_Controller_Request_Http();

        if ($request->isXmlHttpRequest()) {
            $params = $this->get_passed_vars("data");
            if (!(isset($params["id_groupe"]) && isset($params["id_acg"])))
                return false;

//initialisé la base de donné
            $model = new Model();
            $db = $model->getDb();

//Recupérer les groupe
            $qrg = $db->select()->from("s_groupe")->where('id = ?', $params["id_groupe"]);
            $resg = $qrg->query();
            $groupe = $resg->fetchAll();

            $qrm = $db->select()->from("s_modules")->join('s_menu_affectation', 's_menu_affectation.id_module_m = s_modules.id')->where('s_menu_affectation.id_module_g = ?', $params["id_groupe"])->where('s_menu_affectation.id_acg = ?', $params["id_acg"]);
            $resm = $qrm->query();
            $module = $resm->fetchAll();


            for ($i = 0; $i < count($groupe); $i++) {
                $qrac = $db->select()->from("s_access_menu_list")->where('id_module = ?', $groupe[$i]["id"])->where('id_acg = ?', $params["id_acg"]);
                $resac = $qrac->query();
                $acc = $resac->fetchAll();

                if (empty($acc)) {
                    $groupe[$i]["access"] = 0;
                } else {
                    $groupe[$i]["access"] = 1;
                }
            }

            $result = array($groupe, $module);

            echo json_encode($result);
            
        } else {
            $this->redirectphp(SCRIPTADROOT);
        }
    }

}

?>