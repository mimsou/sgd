<!DOCTYPE html>
<html>
    <head>
        <title>Gestion de Stock - administration</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Header -->   
        <?php self::getHead(); ?>
    
    </head>
    <body>
        
        
        <!-- main application -->
        <div class="spacing"></div>
        <div class="navbar navbar-default navbar-fixed-top ">
            <div class="navbar-inner">
                 <!-- Menu -->
                <div class="menu">
                    <?php self::menu(); ?>
                </div>
            </div>
  
        </div>


        <!-- Bare de navigation -->
        <div class="chemin"> <?php self::chemin(); ?></div>

        <!-- Message du system -->
        <div class="infos">
          <?php echo self::message(); ?>
        </div>
        
        <!-- Composant -->
        <div>
            <?php self::renderCom(); ?>
        </div>
        
        
    </body>
</html>
