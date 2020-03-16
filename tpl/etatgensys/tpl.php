

<div class="col-md-10 col-md-offset-1"> 
    <div class="page-header">
        <h1>Générateur d'etat <small></small></h1>
    </div>
</div><div class="clearfix"></div>

<div class="col-xs-12">

    <nav class="navbar navbar-default " role="navigation">
        <div class="navbar-header">  <a class="navbar-brand" href="#">Ajouter une table</a></div>
        <div class="navbar-inner">


            <form class="navbar-form" id="filtre_etat" action="get_shema">

                

                <div class="pull-right" style="display: inline-block">
                    <input type="submit" name="subhide" style="display: none;" /> 
                    <button class="btn btn-primary btn-sm" id="btn_ajouter" ><span class="glyphicon glyphicon-plus"> </span> Ajouter </button>
                    <button type="button" class="btn btn-primary btn-sm" id="btn_compiler" ><span class="glyphicon glyphicon-cog"> </span> Compiler le tous </button>
                    <button type="button" class="btn btn-info btn-sm" id="swipeLeft" ><span class="glyphicon glyphicon-arrow-left"> </span> Ajout tables </button>
                    <button type="button" class="btn btn-info btn-sm" id="swipeRight" ><span class="glyphicon glyphicon-arrow-right"> </span> Expression </button>


                </div>

                <div class="col-xs-9" id="tableelm">
                    <input type="hidden" class="form-control" id="ALIAS" name="ALIAS" /> 
                    <div class="col-xs-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Nom de l'etat: </span>
                        <?php
                        $db = Model::getDb();
                        $etatrs = $db->select()->from("etatgen")->query()->fetchAll();
                        ?>
                        <select class="form-control" id="etat" name="etat" >
                            <?php foreach ($etatrs as $item): ?> 
                                <option value="<?php echo $item["idETAT"]; ?>"><?php echo $item["ETAdesc"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                    
                    <div class="col-xs-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">SGBD : </span>
                            <select class="form-control" id="sgbd" name="sgbd">
                                <option value="mysql">MySQL</option>
                                <option value="db2">DB2</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Nom de connection : </span>
                            <input type="text" class="form-control" id="con" name="con" /> 
                        </div>
                    </div>

                    <div class="col-xs-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Base : </span>
                            <select type="text" class="form-control" id="BASE" name="BASE" > 
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Table : </span>
                            <select type="text" class="form-control" id="TABLE" name="TABLE" >
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </nav>
</div>

<div  class="col-md-12 tables" id="shemaarea">



</div>

<div class="col-md-12 expression" >
    <div class="col-md-4">
        <div class="panel panel-default" id="areaobj" >
            <div class="panel-body" id="listobj" style="overflow: scroll;"  >

            </div>
        </div>
    </div>
    <div class="col-md-8">
        <ul class="nav nav-tabs" data-tabs="tabs" id="tabs">
            <li  class="active"><a class="gestgen" href="#selecttab" data-toggle="tab">Expressions select</a></li>
            <li><a class="gestgen" href="#wheretab" data-toggle="tab">Expressions where</a></li>
            <li><a class="gestspes" href="#groupetab" data-toggle="tab">Expressions de groupement</a></li>
            <li><a class="gestspes" href="#ordertab" data-toggle="tab">Expressions d'ordre</a></li>

        </ul>
        <br />
        <div id='mycontent' class="tab-content">

            <div class="col-md-12 tab-pane active" id="selecttab">
                <div class="panel panel-default panel-primary" id="selectexp" >
                    <div class="panel-heading">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" style="color: #000000;">SELECT : </span>
                            <input class="form-control" id="editselect" name="editselect">
                            <span class="input-group-addon" style="color: #000000;">AS</span>
                            <input class="form-control" style="width: 100px" id="asselect" name="asselect">
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="selectplus"><span class="glyphicon glyphicon-plus"></span></button>
                            </span>
                        </div>
                    </div>
                    <div class="panel-body" id="selectrender"  >

                    </div>
                </div>
            </div>


            <div class="col-md-12 tab-pane" id="wheretab">
                <div class="panel panel-default panel-info" id="wherexpr" >
                    <div class="panel-heading">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" style="color: #000000;">WHERE : </span>
                            <input class="form-control" id="editwher" name="editwher">
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="whereplus"><span class="glyphicon glyphicon-plus"></span></button>
                            </span>
                        </div>
                    </div>
                    <div class="panel-body" id="whererender"  >

                    </div>
                </div>
            </div>

            <div class="col-md-12 tab-pane" id="groupetab">
                <div class="panel panel-default panel-success" id="groupexpr" >
                    <div class="panel-heading">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" style="color: #000000;">GROUPE BY : </span>
                            <input class="form-control" id="editgroup" name="editgroup">
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-12 tab-pane" id="ordertab">
                <div class="panel panel-default panel-danger" id="orderexpr" >
                    <div class="panel-heading">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" style="color: #000000;">ORDER BY : </span>
                            <input class="form-control" id="editorder" name="editorder">
                            <span class="input-group-addon" style="color: #000000;">TYPE : </span>
                            <select id="typeorder" name="typeorder" class="form-control" style="width: 100px;">
                                <option value="ASC">Ascendant</option>
                                <option value="DESC">Descendant</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

</div>








