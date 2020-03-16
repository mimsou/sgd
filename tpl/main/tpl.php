<div  style="height:350px;width: 550px;display: none;" id="canvasRadar"></div>
<?php 

$em = Model::$em;
$ent = $em->find("ent",1);
$ent->setEntnom("Administrateur");

$collection = new \Doctrine\Common\Collections\ArrayCollection();

$em->persist($ent);
$em->flush();
?>