<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GESTION DE STOCK</title>

        <?php self::getHead(); ?>
        
    </head>

    <body style="background:#F7F7F7;">
        <div class="">
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>

            <div id="wrapper">
              
                <div id="login" class=" form">
                      <div class="trans_logo">

                </div>
                <div class="separator">

                </div>
                    <section class="login_content">
                        <form id="login-form" method="post" action="" enctype="application/x-www-form-urlencoded">
                            <h1> Authentification</h1>
                            <div>
                                <input type="text" class="form-control" id="username" name="username"  autocomplete="off" placeholder="Utilisateur">
                            </div>
                            <div>
                                <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Mot de passe">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-default submit">Connection</button>

                            </div>
                            <div class="clearfix"></div>

                        </form>
                    </section>
                </div>




            </div>
        </div>
    </body>
</html>