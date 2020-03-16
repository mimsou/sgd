<div class="col-md-10 col-md-offset-1">
    <div class="page-header">
        <h1> Edition speciale <small></small></h1>
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-12">
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">  <a class="navbar-brand" href="#">Filtre</a></div>
        <div class="navbar-inner">

            <form class="navbar-form" id="filtre_etat" action="get_etat">
                <div class="pull-right" style="display: inline-block">
                    <input type="submit" name="subhide" style="display: none;" />
                    <button type="button" class="btn btn-primary btn-sm" id="btn_gen"  ><span class="glyphicon glyphicon-plus"> </span> Génerer </button>
                    <button type="button" class="btn btn-success btn-sm" id="btn_imp"  ><span class="glyphicon glyphicon-print"> </span> Imprimer </button>
                </div>
                <div class="col-md-10" >
                    <div class="col-md-3">
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
                    <div class="col-md-9" id="ETAsearch">
                    </div>
                </div>

            </form>

        </div>
    </nav>

</div>

<div class="col-md-12" id="printarea">

    <table class="tablesorter table table-bordered table-condensed" id="expexcel">
        <thead>
            <tr class="fixedhead">
                <th colspan="40"><center><h3 id="ETAdesc"></h3></center></th>
        </tr>
        <tr class="fixedhead" id="ETAchamp"></tr>

            </thead>
        <tbody id="ETAdata">

        </tbody>
    </table>
</div>
