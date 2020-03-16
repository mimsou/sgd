<?php echo $html; ?>

<div class="btn-group pull-right margin_rg_1 top03">
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="glyphicon glyphicon-cog "></span>  <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="javascript:;" id="updateMod" >Mise à jour des composants </a>
        </li>
        <li>
            <a href="javascript:;" id="netmodul" >Netoyer les composants périmés <span class="label label-danger" id="numnet"></span></a>
        </li> 
        <li>
            <a href="<?php echo SCRIPTROOT; ?>/crudmaker" >Crée un composant CRUD </a>
        </li>
        <li>
            <a href="javascript:;" id="newmodul" >Crée un composant vide </a>
        </li>
        <li>
            <a href="javascript:;" id="deconection" >Deconnection</a>
        </li>
    </ul>
</div>
<div class="btn-group margin_rg_1 top03">
    <div class="ajaxspin">
        <img src="<?php
        $var = new vars();
        $base = str_replace("/" . $var->ad_dir, "", SCRIPTROOT);
        echo $base . "/lib/img/load.gif";
        ?>" /> 
    </div>
</div>


