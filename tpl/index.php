<!DOCTYPE html>
<html>
    <head>
        <title>Gestion des fournitures de bureau</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Header -->
        <?php self::getHead(); ?>

        <script type="text/javascript">
            $(document).ready(function () {
                $("[datepick]").each(function () {
                    $(this).rules("add"
                            , {
                                "regex": /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/
                            });
                });
            });
        </script>
    </head>
    <body class="nav-md">
        <div class="preload">
            <img  class="preloadimg" src="<?php echo SCRIPTROOT . "/lib/img/bigRotLoader.gif"; ?>" />
        </div>
        <div class="container body" id="flscr" >
            <div class="main_container" >
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="<?php echo WEBROOT ?>main" class="site_title"><i class="fa fa-train"></i> <span> G. Stock</span> </a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <?php
                        $nom = Zend_Auth::getInstance()->getIdentity()->UTINOM;
                        $pnom = Zend_Auth::getInstance()->getIdentity()->UTIPNOM;
                        ?>
                        <div class="profile">
                            <div class="profile_pic">
                                <img src="<?php echo WEBROOT ?>/lib/css/images/User.png" alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>Bienvenu,</span>
                                <h2><?php echo $nom . " " . $pnom; ?></h2>
                            </div>
                        </div>
                        <!-- /menu profile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <?php self::menu(); ?>
                            </div>
                            <div class="menu_section">
                                <?php self::util_bar(); ?>
                            </div>
                        </div>
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small"> 
                            <a data-toggle="tooltip" data-placement="top" title="Logout">
                                <span id="deconection_mn" class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                            <a  data-placement="top" title="Settings">
                                <span class="glyphicon glyphicon-cogq" aria-hidden="true"></span>
                            </a>

                            <a  data-placement="top" title="Settings">
                                <span class="glyphicon glyphicon-cogq" aria-hidden="true"></span>
                            </a>

                            <a data-toggle="tooltip" data-placement="top" id="fs-doc-button" title="FullScreen">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>

                        </div>
                        <!-- /menu footer buttons -->


                    </div>
                </div>

                <div class="top_nav">

                    <div class="nav_menu">
                        <nav class="" role="navigation"> 
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                            <div class="nav navbar-nav navbar-left navbar-breacrumb">
                                <ol class="breadcrumb inblock">
                                    <?php self::chemin(); ?>
                                </ol>
                                <span style="font-size: 22px;top: 5px;" class="glyphicon glyphicon-menu-right" ></span>


                            </div>

                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <?php echo $nom; ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">

                                        <li>
                                            <a href="javascript:;"  id="editprofil">
                                                <span>Changement de mot de passe</span>
                                            </a>
                                        </li>
                                        <li><a href="javascript:;"  id="deconection" ><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                        </li>
                                    </ul>
                                </li>

                                <li role="presentation" class="dropdown">
                                    <a id="clickalert" href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-bell-o"></i>
                                        <span class="badge bg-red" id="nbalert">0</span>
                                    </a>
                                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">


                                    </ul>
                                </li>

                                <li role="presentation" class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-bullhorn"></i>
                                        <span class="badge bg-blue" id="nbalert">1</span>
                                    </a>
                                    <ul id="menu2" class="dropdown-menu list-unstyled msg_list" role="menu">
                                        <li>
                                            <a id="perstkbtn" class="btn btn-xs btn-primary" style="color: #FFF">

                                                <span>
                                                    <span>Raferchir les seuil d'alerte du stock</span>
                                                    <span class="time"></span>

                                                </span>

                                            </a>
                                            <span class="message">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon">Période</span>
                                                    <select id="perstk" class="form-control">
                                                        <option value="90">3 mois</option>
                                                        <option value="180">6 mois</option>
                                                        <option value="365">1 ans</option>
                                                    </select>
                                                </div>
                                            </span>
                                        </li>

                                    </ul>
                                </li>

                                <li class="leftli">
                                    <?php self::rendermodhead() ?>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>



                <!-- Bare de navigation -->


                <!-- Message du system -->



                <div class="right_col bgimg" role="main">
                    <?php echo self::message(); ?>
                    <?php self::renderCom(); ?>
                    <div id="alertdialog"></div>
                     <div id="alertdialogp"></div>
                    <div class="clearfix"></div>
                </div>


                <footer>

                </footer>



            </div>


    </body>
</html>
