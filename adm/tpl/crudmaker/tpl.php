

<div class="col-md-10 col-md-offset-1"> 
    <div class="page-header">
        <h1>Création d'un composant CRUD <small></small></h1>
    </div>
</div><div class="clearfix"></div>

<div class="col-md-12">

    <nav class="navbar navbar-default " role="navigation">
        <div class="navbar-header">  <a class="navbar-brand" href="#">Ajouter une table</a></div>
        <div class="navbar-inner">


            <form class="navbar-form" id="filtre_crud" action="get_shema">

                <div class="pull-right" style="display: inline-block">
                    <input type="submit" name="subhide" style="display: none;" /> 
                    <button class="btn btn-primary btn-sm" id="btn_ajouter" ><span class="glyphicon glyphicon-plus"> </span> Ajouter </button>
                    <button type="button" class="btn btn-primary btn-sm" id="btn_compiler" ><span class="glyphicon glyphicon-cog"> </span> Compiler le tous </button>

                </div>

                <div class="col-md-8">

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">SGBD : </span>
                            <select class="form-control" id="sgbd" name="sgbd">
                                <option value="mysql">MySQL</option>
                                <option value="db2">DB2</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Nom de connection : </span>
                            <input type="text" class="form-control" id="con" name="con" /> 
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Base : </span>
                            <select type="text" class="form-control" id="BASE" name="BASE" > 
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
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

<div class="col-md-12" id="shemaarea">



</div>


<!-- dialog -->

<div title="Clef etrangére" id="fkmake">
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon">Base :</span> 
            <select class="form-control" name="basefk" id="basefk" >
                <option value=""></option> 
            </select>
        </div>
    </div></br></br>
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon">Table :</span> 
            <select class="form-control"  name="tablefk" id="tablefk" ><option value=""></option> </select>
        </div>
    </div></br></br>
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon" id="forekey">Cléf :</span> <select  class="form-control"  name="keyfk" id="keyfk" ><option value=""></option> </select>
        </div>
    </div></br></br>
    <div class="col-lg-9 col-lg-offset-2">
        <div class="input-group input-group-sm top1">
            <span class="input-group-addon">Libellé :</span> <select  class="form-control" name="libfk" id="libfk" ><option value=""></option> </select>
        </div>
    </div></br></br>
</div>








