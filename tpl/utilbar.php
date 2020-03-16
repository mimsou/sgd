<?php
$env = new Zend_Session_Namespace('env');

$mag = $env->mag;

$magslect = $env->magslect;

$date = new Zend_Date();

$yearselect = $env->exe;

$exe = Metier_Sgs::get_exercice();

$tables = new Zend_Db_Table('exe');
$qrexe = $tables->select();
$rsexe = $qrexe->query();
$exelist = $rsexe->fetchAll();


$nom = Zend_Auth::getInstance()->getIdentity()->UTINOM;
$pnom = Zend_Auth::getInstance()->getIdentity()->UTIPNOM;
?>

<div class="profile">
    
    <div class="col-md-12">

        <div class="input-group input-group-sm">
            <span class="input-group-addon">Magasin : </span>

            <select class="form-control" name="mag" id="mag">
                
                <?php foreach ($mag as $magitem) : ?>
                    <option <?php if ($magslect == $magitem["MAGCOD"]) echo "selected='selected'"; ?> value="<?php echo $magitem["MAGCOD"]; ?>"> <?php echo $magitem["MAGCOD"]; ?> </option>
                <?php endforeach; ?>
                    
            </select>

        </div>
        
     

    </div>

    <div class="col-md-12">

        <div class="input-group input-group-sm">
            <span class="input-group-addon">Année : </span>

            <select class="form-control" name="exe" id="exe">
                
                <?php foreach ($exelist as $exeitem) : ?>
                    <option value="<?php echo $exeitem["exe"]; ?>" <?php if ($yearselect == $exeitem["exe"]) echo "selected='selected'"; ?> class="<?php
                    if (in_array((int) $exeitem["exe"], $exe)) {
                        echo "yearin";
                    } else {
                        echo "yearout";
                    }
                    ?>" > <?php echo $exeitem["exe"]; ?> </option>
                <?php endforeach; ?>
                    
            </select>

        </div>
        
     

    </div>
    <br> <br>

    <div class="col-md-12">

        <div class="input-group input-group-sm">
            <span class="input-group-addon">Date :</span>
            <span class="input-group-addon"><?php echo $date->toString("dd/MM/yyyy"); ?></span>
        </div>

    </div>

</div>