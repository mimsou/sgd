<!DOCTYPE html>
<html>

    <head>
        <title>Gesto</title>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
        <script src="<?php echo WEBROOT; ?>/lib/js/jquery-1.11.0.min.js" type="text/javascript" ></script>
        <script src="<?php echo WEBROOT; ?>/lib/js/html5shiv.js" type="text/javascript" ></script>
        <script src="<?php echo WEBROOT; ?>/lib/js/respond.min.js" type="text/javascript" ></script>
        <script src="<?php echo WEBROOT; ?>/lib/js/jquery.validate.min.js" type="text/javascript" ></script>
        <script src="<?php echo WEBROOT; ?>/lib/js/jquery.tipsy.js" type="text/javascript" ></script>
        <script src="<?php echo WEBROOT; ?>/lib/js/messages_fr.js" type="text/javascript" ></script>
        <script src="<?php echo WEBROOT; ?>/lib/js/bootstrap.min.js" type="text/javascript" ></script>  
        <script src="<?php echo WEBROOT; ?>/lib/js/ajax.js" type="text/javascript" ></script>  
        <script src="<?php echo WEBROOT; ?>/install/install.js" type="text/javascript" ></script> 
        <link type = "text/css" href = "<?php echo WEBROOT; ?>/lib/css/main.css" rel = "stylesheet" ></script>
    <link type = "text/css" href = "<?php echo WEBROOT; ?>/lib/css/tipsy.css" rel = "stylesheet" ></script>
<link type = "text/css" href = "<?php echo WEBROOT; ?>/lib/css/bootstrap.min.css" rel = "stylesheet" ></script>
<link type = "text/css" href = "<?php echo WEBROOT; ?>/inventaire/lib/css/bootstrap-theme.min.css" rel = "stylesheet" ></script>

</head>

<body>

    <div class="spacing"></div>

    <div class="navbar navbar-default navbar-fixed-top topmenu">
        <div class="navbar-inner">
            <div class="offset1">
                <a href="main" class="logo top05"></a> 
            </div>
        </div>
    </div>

    <!-- Composant -->
    <div class="composant">
        <div class=""></div>

        <div class="col-md-4 col-md-offset-4 top1">

            <div class="panel panel-default ">

                <div class="panel-body">
                    <h3> Installation </h3>
                </div>

                <div class="panel-footer">
                    <form method="post" action="" id="paraminstall" enctype="application/x-www-form-urlencoded">
                        <?php if(!empty($message)): ?>
                        <div class="input-group input-group-sm"  style="margin-bottom: 10px;">
                            <h3 class="input-group-addon" style="color: red;"><?php echo $message; ?> </h3>
                        </div>
                        <?php endif; ?>
                        <div class="input-group input-group-sm"  style="margin-bottom: 10px;">
                            <h3 class="input-group-addon">Paramétre administrateur : </h3>

                        </div>
                        <div class="input-group input-group-sm" style="margin-bottom: 5px;">
                            <span class="input-group-addon" >Login SA : </span>
                            <input value="admin" name="loginsa" type="text" class="form-control" >
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 5px;">
                            <span class="input-group-addon">Passe SA : </span>
                            <input name="passa" id="passa" type="password" class="form-control" >
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 10px;">
                            <span class="input-group-addon">Passe SA (verification) : </span>
                            <input name="passa2"  type="password" class="form-control" >
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 10px;">
                            <h3 class="input-group-addon">Configuration base de données : </h3>
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 5px;">
                            <span class="input-group-addon">Serveur : </span>
                            <input name="serveur" type="text" class="form-control" >
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 5px;">
                            <span class="input-group-addon">Nom de la BD : </span>
                            <input name="base" type="text" class="form-control" >
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 5px;">
                            <span class="input-group-addon">Utilisateur de la BD  : </span>
                            <input name="util" type="text" class="form-control" >
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 5px;">
                            <span class="input-group-addon">Passe de la BD  : </span>
                            <input name="passbd" type="password" class="form-control" >
                        </div>

                        <div class="input-group input-group-sm"  style="margin-bottom: 5px;">
                            <button class="btn btn-primary btn-lg" id="btn_install"> Installation </button>
                        </div>


                        <input type="hidden" name="config" value="1">

                    </form>
                </div>

            </div>
        </div>       
    </div>


</body>
</html>
