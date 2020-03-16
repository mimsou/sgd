
<div class="container">

    <nav class="navbar navbar-default" role="navigation">
        <p class="navbar-text">Filtre :</p>
        <form class="navbar-form navbar-left" role="search">

            <div class="form-group">
                <label class="label label-info" id="access_groupe">Groupe d'access</label> <button type="button" class="btn btn-default  btn-xs sise_1" id="access_edit">Edit</button>
                <select class="form-control" id="acg-list">

                </select>   
            </div>

            <div class="form-group">
                <label class="label label-info">Espace</label>
                <select class="form-control" id="espace">
                    <option value="front">FrontOffice</option>
                    <option value="ad">Administration</option>
                </select>                
            </div>

        </form>    
    </nav>

    <div class="col-md-5">
        <div class="panel panel-primary panel-default ">
            <div class="panel-heading ">Liste des index<span id="num_groupe" class="badge pull-right"></span></div>
            <div class="panel-body" id="liste_groupe">

            </div>
        </div>
    </div>



    <div class="col-md-7">
        <div class="panel panel-primary panel-default ">
            <div class="panel-heading clearfix">
                
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">Liste des actions</div>
                    <div class="form-group">
                        <button type="button" rel="check" class="btnchk ajax">ajax</button>   
                        <button type="button" class="btnchk vu">vu</button>
                    </div>
                </form>  
            </div>
            <div class="panel-body" >
                <div id="list_action">


                </div>
            </div>
        </div>
    </div>

</div>



<div title="Gestion des groupes d'acces" id="acgdiag">
    <div class="col-md-12" id="access_groupe_container">

    </div>
</div>

