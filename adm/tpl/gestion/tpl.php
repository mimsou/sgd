<div class="content">

    <div class="col-lg-8 col-md-offset-2" id="param_util">
        <div class="panel panel-default panel-primary ">


            <div class="panel-heading">Paramètre de l'utilisateur</div>

            <div class="panel-body">


                <div class="row">

                    <div class="col-lg-4">

                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Services</span>

                            <select class="form-control" name="ent" id="ent">

                            </select>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="input-group input-group-sm">

                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Profil <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript:;" id="btn_aj_prf">Crée un nouveau profil</a></li>
                                    <li class="divider"></li>
                                    <li><a href="javascript:;" id="btn_sup_prf">Supprimer le profil selectioné</a></li>
                                    <li><a href="javascript:;" id="btn_cop_prf">Copier le profil selectioné</a></li>
                                    <li><a href="javascript:;" id="btn_pri_prf">Designer comme profil principale</a></li>
                                </ul>
                            </div>

                            <select class="form-control" name="prf" id="prf">

                            </select>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="input-group input-group-sm">

                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Utilisateur <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript:;" id="btn_rc_util">Recherche d'utilisateur</a></li>
                                    <li class="divider"></li>
                                    <li><a href="javascript:;" id="btn_aj_util">Crée un nouveau utilisateur</a></li>
                                    <li><a href="javascript:;" id="btn_modif_util">Modifier l'utilisateur selectioné</a></li>
                                    <li><a href="javascript:;" id="btn_sup_util">Supprimer l'utilisateur selectioné</a></li>
                                </ul>
                            </div>

                            <select class="form-control" name="util" id="util">

                            </select>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>


    <div class="clearfix"></div>
    <div id="content">
        <ul class="nav nav-tabs" data-tabs="tabs" id="tabs">
            <li  class="active"><a class="gestgen" href="#gst_menu" data-toggle="tab">Gestion du menu</a></li>
            <li><a class="gestgen" href="#interface" data-toggle="tab">Droits et actions</a></li>
            <li><a class="gestspes" href="#affectationdroit" data-toggle="tab">Affectation des droits</a></li>
            <li><a class="gestspes" href="#affectationmag" data-toggle="tab">Affectation des Magasins</a></li>
            <li><a class="gestspes" href="#affectationexe" data-toggle="tab">Gestion des exercices</a></li>
            <li><a class="gestspes" href="#histuser" data-toggle="tab">Historique des utilisateurs</a></li>
        </ul>

        <div id='mycontent' class="tab-content">

            <!------------------------------------------------------------ Gestion de menu ------------------------------------------------>


            <div class="tab-pane active" id="gst_menu">

                <div class="relt top1">

                    <div class="col-lg-6"> 
                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading">Liste menu <button id="btn_aj_menu" class="btn btn-xs btn-success pull-right">Ajout Menu</button></div>

                            <div class="panel-body">
                                <div id="gst_modul_menu" class="jstree-drop">

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-6"> 
                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading">Liste des interfaces </div>

                            <div class="panel-body">
                                <div id="gst_menu_modul"></div>
                            </div>

                        </div>
                    </div>




                </div>

            </div>


            <!------------------------------------------------------------ Gestion droit d'access ------------------------------------------------------->


            <div class="tab-pane"  id="interface">

                <div class="relt top1">

                    <div class="col-lg-6 col-md-offset-3"> 

                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading">Gestion des droits </div>

                            <div class="panel-body">

                                <div id="gst_droi" >

                                    <div class="row">
                                        <div class="col-lg-6 col-lg-offset-3">

                                            <div class="input-group input-group-sm">

                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Groupe de droits <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="javascript:;" id="btn_aj_grbdroit">Crée un groupe de droit</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="javascript:;" id="btn_sup_grbdroit">Supprimer le groupe de droit</a></li>
                                                    </ul>
                                                </div>

                                                <select class="form-control" name="grbdroit" id="grbdroit">

                                                </select>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row relt top02">
                                        <div class="col-lg-8 col-lg-offset-2">

                                            <div class="input-group input-group">

                                                <div class="input-group-btn">

                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Droits<span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="javascript:;" id="btn_aj_droit">Crée un nouveau droits</a></li>
                                                        <li><a href="javascript:;" id="btn_aj_mark">Ajouter un marqueur</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="javascript:;" id="btn_sup_droit">Supprimer le droit</a></li>
                                                    </ul>

                                                </div>

                                                <select class="form-control" name="droit" id="droit">

                                                </select>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>


                    <div class="col-lg-6 col-md-offset-3 "> 

                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading">Selection et gestions des modules</div>

                            <div class="panel-body">
                                <div class="" id="gst_actions">

                                </div>
                            </div> 
                        </div>

                    </div>


                </div>

            </div>

            <!------------------------------------------------------------ Gestion affectation de droits ------------------------------------------------------->


            <div class="tab-pane"  id="affectationdroit">

                <div class="relt top1">

                    <div class="col-lg-6 col-md-offset-3 "> 

                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading" id="droit_affect_title">Affectation des droit pour : </div> 

                            <div class="panel-body">
                                <div class="" id="gst_droit_profil">

                                </div>
                            </div> 
                        </div>

                    </div>

                </div>
            </div>
            <!-------------------------------------------- Affectation Mag ---------------------------------------------->

            <div class="tab-pane"  id="affectationmag">

                <div class="relt top1">

                    <div class="col-lg-6 col-md-offset-3 "> 

                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading" id="mag_affect_title">Affectation des magasin pour : </div> 

                            <div class="panel-body">
                                <div class="" id="gst_mag_profil">

                                </div>
                            </div> 
                        </div>

                    </div>

                </div>
            </div>

            <!------------------------------------------------------------ Gestion affectation de l'anné exercice ------------------------------------------------------->

            <div class="tab-pane"  id="affectationexe">

                <div class="relt top1">

                    <div class="col-lg-6 col-md-offset-3 "> 

                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading" id="mag_affect_title">Année exercice : <button id="ouvexe" class="btn btn-primary btn-xs">Ouverture Exercice</button></div> 

                            <div class="panel-body">

                                <div class="" id="gst_exe_profil">

                                </div>

                            </div> 
                        </div>

                    </div>

                </div>

            </div>

            <!------------------------------------------------------------ Gestion de l'historique utilisateurs ------------------------------------------------------->
            <?php
            $date = new Zend_Date();
            ?>


            <div class="tab-pane"  id="histuser">

                <div class="relt top1">

                    <div class="col-lg-12 "> 

                        <div class="panel panel-default panel-primary ">

                            <div class="panel-heading" id="mag_affect_title">Historique des utilisateurs : 

                            </div> 

                            <div class="panel-body">

                                <nav class="navbar navbar-default " role="navigation">
                                    <div class="navbar-header">  <a class="navbar-brand" href="#">Filtre</a></div>
                                    <div class="navbar-inner">

                                        <form class="navbar-form" id="filtre_pump">

                                            <div class="pull-right" style="displeay: inline-block">
                                                <input type="submit" name="subhide" style="display: none;" /> 
                                                <button class="btn btn-primary btn-sm  " id="btn_aff_hist" type="button" ><span class="glyphicon glyphicon-refresh"> </span> Rechercher </button>  
                                                <button class="btn btn-primary btn-sm  " id="btn_aff_hist_ftr" type="button" ><span class="glyphicon glyphicon-refresh"> </span> Recherche avancé </button>  
                                            </div>

                                           
                                            <div class="col-md-2">
                                                <div class="input-group input-group-sm">

                                                    <span class="input-group-addon">Description : </span>
                                                    <input id="descriptOPER" name="descriptOPER" class="form-control"  />

                                                </div>
                                            </div>

                                            

                                            <div class="col-md-2">
                                                <div class="input-group input-group-sm">

                                                    <span class="input-group-addon">Utilisateur selectioné: </span>
                                                    <span class="input-group-addon"><input type="checkbox" id="selecteduser" name="selecteduser"   /></span>


                                                </div>
                                            </div>

                                        </form>

                                    </div>
                                </nav>
                                <div class="col-md-12">

                                    <table class="tablesorter table table-bordered" id="histprixtab" >
                                        <thead>
                                            <tr class="fixedhead" id="headstk">
                                                <th colspan="8" style="text-align: right;" id="editdate"><?php echo "Editer le : " . $date->toString("dd/MM/yyyy"); ?></th>
                                            </tr>
                                            <tr class="fixedhead" id="headstk">
                                                <th colspan="8"><center><h2><span id="titletab"></span></h2></center></th>

                                        </tr>
                                        <tr class="fixedhead" id="headstk">
                                            <th>ID OPERATION</th>
                                            <th>ID MODULE</th>
                                            <th>FONCTION</th>
                                            <th>DESCRIPTION OPERATION</th>
                                            <th>DATE ET HEUR</th>
                                            <th>NOM PRENOM</th>
                                            <th>PROFIL</th>
                                            <th >PARAMETRE D'EXECUTION</th>
                                        </tr>
                                        </thead>
                                        <tbody id="data_result">

                                        </tbody>
                                    </table>

                                </div> 
                            </div>
                        </div>

                    </div>

                </div>

            </div>


        </div>
        <?php
        $date = new Zend_Date();
        ?>


    </div>

</div>







<!-------------------------------------------- Dialogue ---------------------------------------------->

<div id="dg_aj_prf" title="Ajout profil">
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon">Nom :</span> <input type="text" class="form-control" name="PRFnom" id="PRFnom" />
        </div>
    </div>
</div>

<div id="dg_aj_menu" title="Ajout menu">
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon">Nom :</span> <input type="text" class="form-control" name="MENUnom" id="MENUnom" />
        </div>
    </div>
</div>

<div id="dg_aj_droit" title="Ajout droit">
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon">Nom :</span> <input type="text" class="form-control" name="DROITnom" id="DROITnom" />
        </div>
    </div>
</div>

<div id="dg_aj_grbdroit" title="Ajout groupe de droit">
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon">Nom :</span> <input type="text" class="form-control" name="GRBDROITnom" id="GRBDROITnom" />
        </div>
    </div>
</div>



<div id="dg_aj_util" title="Ajout utilisateur">
    <form id="frm_aj_util" name="frm_aj_util"  action="ajout_util" >
        <div class="col-lg-7 col-lg-offset-1 top1">
            <div class="input-group input-group-sm marg_top_1">
                <span class="input-group-addon">Nom :</span> <input type="text" class="form-control required" name="UTIPNOM" id="UTIPNOM" />
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Prénom :</span> <input type="text" class="form-control required" name="UTINOM" id="UTINOM" />
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Login :</span> <input type="text" class="form-control required" name="UTILOG" id="UTILOG" />
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Matricule :</span> <input type="text" class="form-control required" name="UTImat" id="UTImat" />
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Passe :</span> <input type="password" class="form-control required" name="UTIPWD" id="UTIPWD" /><br/>
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Passe 2:</span> <input type="password" class="form-control required" name="UTIPWD2" id="UTIPWD2" /><br/>
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">etat:</span> 
                <select class="form-control required" name="ETATCOD" id="ETATCOD">
                    <option value="41">Activer</option>
                    <option value="42">Désactivé</option>
                </select>
                <br/>
            </div><br/>
            <input type="hidden" class="form-control" name="IDUTIL" id="IDUTIL" />
        </div>
    </form>

</div>


<?php
$db = Model::getDb();
$grbdroits = $db->select()->from("grbdroit")->query()->fetchAll();
?>

<div id="hist_util_filt_diag" title="recherche_utilisateur">
    <form id="frm_hist_util_filt" name="frm_hist_util_filt"  action="get_hist_utilisateur_filtre" >
        <div class="col-lg-10 col-lg-offset-1 top1">
            <div class="input-group input-group-sm marg_top_1">
                <span class="input-group-addon">Par action :</span> 

                <select type="text" class="form-control" name="droit" id="droit" />
                  <option value="">toutes les actions</option>
                <?php foreach ($grbdroits as $grbdroit): ?>
                    <optgroup label="<?php echo $grbdroit["GRBDROITnom"]; ?>">
                        <?php
                        $db = Model::getDb();
                        $droits = $db->select()->from(array("A" => "droit"))
                                        ->where("idGRBDROIT = ?", $grbdroit["idGRBDROIT"])->query()->fetchAll();
                        ?>

                        <?php foreach ($droits as $droit): ?>
                            <option value="<?php echo $droit["idDROIT"]; ?>"><?php echo $droit["DROITnom"]; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
                </select>

            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Par description :</span> <input type="text" class="form-control" name="descriptOPERF" id="descriptOPER" />
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Par paramétre :</span> <input type="text" class="form-control" name="paramOPER" id="paramOPER" />
            </div><br/>
             <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Par catégorie :</span> 
                
                <select id="modulecathist" name="modulecathist" class="form-control" type="text">
                     <option value="">Toutes</option>
                    <option value="C">Création (ajout)</option>
                    <option value="R">Lecture (Consultation)</option>
                    <option value="U">Modification (Mise à jour)</option>
                    <option value="D">Suppression (Annulation)</option>
                    <option value="P">Paramétrage</option>
                </select>
                <br/>
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Date debut :</span> <input type="text" class="form-control" name="datedebutil" id="datedebutil" />
            </div><br/>
            <div class="input-group input-group-sm top1 marg_top_1">
                <span class="input-group-addon">Date fin :</span> <input type="password" class="form-control" name="datefinutil" id="datefinutil" /><br/>
            </div><br/>
        </div>
    </form>

</div>




</div>
</div>
