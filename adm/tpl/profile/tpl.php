<div class="row ">
    <div class="col-md-10 col-md-offset-1  top1">
        
        <div class="col-md-7"> <h3><a href="http://127.0.0.1/SGS/ad/profile/">Gestion de profils</a></h3></div>

        <div class="col-md-4">
            <a href="<?php echo SCRIPTADROOT ?>profile/ajout" class="btn btn-primary btn-sm " type="button"><i class="glyphicon glyphicon-plus-sign"></i> Ajout profile</a>  
            <a href="modif" class="btn btn-primary btn-sm " type="button"><i class="glyphicon glyphicon-pencil"></i> Modification profile</a>
        </div>

        <div class="row">
            <div class="col-md-7" ><?php echo $this->data["form"] ?></div>
        </div>

        <div class="row">
            <div class="span7" ></div>
        </div>
    </div>
</div>