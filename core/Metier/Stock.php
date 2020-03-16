<?php

class Metier_Stock {

    private static $_instance = null;

    private function __construct() {
//definire la date d'exercice actuel
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Metier_Stock();
        }
        return self::$_instance;
    }

    public static function get_mvt_article($arrayopt = array()) {

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $art = $arrayopt["art"];

        $em = Model::$em;
        $mvtquery = $em->createQueryBuilder();

        $consar = $mvtquery->select("IDENTITY(a.consArtcode) as artcode , IDENTITY(a.consCode) as code ,a.consQte as qte, b.consDate as date ")
                        ->addSelect("'Consommation' as nat")
                        ->from("StkConsomationDetail", "a")
                        ->join("a.consCode", "b")
                        ->where('a.consArtcode = :artCode')
                        ->andWhere('b.consMagcode = :mag')
                        ->andWhere('a.consExercice = :exe')
                        ->andWhere($mvtquery->expr()->in('b.consEtatcode', array(52, 54)))
                        ->setParameter('artCode', $art)
                        ->setParameter('mag', $mag)
                        ->setParameter('exe', $exe)
                        ->getQuery()->getScalarResult();

        $mvtquery = $em->createQueryBuilder();

        $transarsort = $mvtquery->select("IDENTITY(a.transArtcode) as artcode , IDENTITY(a.transCode) as code ,a.transQteliv as qte, b.stkTransDateliv as date ")
                        ->addSelect("'Transfert sortant' as nat")
                        ->from("StkTransDetail", "a")
                        ->join("a.transCode", "b")
                        ->where('a.transArtcode = :artCode')
                        ->andWhere('b.transMagliv = :mag')
                        ->andWhere('a.transExercice = :exe')
                        ->andWhere($mvtquery->expr()->in('b.transEtatcode', array(92)))
                        ->setParameter('artCode', $art)
                        ->setParameter('mag', $mag)
                        ->setParameter('exe', $exe)
                        ->getQuery()->getScalarResult();

        $mvtquery = $em->createQueryBuilder();

        $entar = $mvtquery->select("IDENTITY(a.entArtcode) as artcode , IDENTITY(a.entCode) as code ,a.entQte as qte, b.entDatereception as date  ")
                        ->addSelect("'Entrée' as nat")
                        ->from("StkEntreDetail", "a")
                        ->join("a.entCode", "b")
                        ->where('a.entArtcode = :artCode')
                        ->andWhere('b.entMagrecepcode = :mag')
                        ->andWhere('a.entExercice = :exe')
                        ->andWhere($mvtquery->expr()->in('b.entEtatcode', array(31, 33)))
                        ->setParameter('artCode', $art)
                        ->setParameter('mag', $mag)
                        ->setParameter('exe', $exe)
                        ->getQuery()->getScalarResult();

        $mvtquery = $em->createQueryBuilder();

        $transarent = $mvtquery->select("IDENTITY(a.transArtcode) as artcode , IDENTITY(a.transCode) as code ,a.transQteliv as qte, b.stkTransDateliv as date ")
                        ->addSelect("'Transfert entrant' as nat")
                        ->from("StkTransDetail", "a")
                        ->join("a.transCode", "b")
                        ->where('a.transArtcode = :artCode')
                        ->andWhere('b.transMagdem = :mag')
                        ->andWhere('a.transExercice = :exe')
                        ->andWhere($mvtquery->expr()->in('b.transEtatcode', array(92)))
                        ->setParameter('artCode', $art)
                        ->setParameter('mag', $mag)
                        ->setParameter('exe', $exe)
                        ->getQuery()->getScalarResult();

        $mvtentsort = array_merge($consar, $entar);

        $mvttrans = array_merge($transarsort, $transarent);

        $mvt = array_merge($mvtentsort, $mvttrans);

        usort($mvt, function ($a, $b) {
            $a = strtotime($a['date']);
            $b = strtotime($b['date']);
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        }
        );

        $articleok = true;
        $mvtinit = array();
        try {
            $mvtquery = $em->createQueryBuilder();

            $mvtinit = $mvtquery->select("a.stkInitial as init")
                            ->from("StkStock", "a")
                            ->where('a.exercice = :exe')
                            ->andWhere('a.artCode = :artCode')
                            ->andWhere('a.magCode = :mag')
                            ->setParameter('artCode', $art)
                            ->setParameter('mag', $mag)
                            ->setParameter('exe', $exe)->getQuery()->getsingleresult();

            $stockcount = $mvtinit["init"];
        } catch (Exception $e) {
            $articleok = false;
            $mvtinit["init"] = 0;
        }

        foreach ($mvt as $key => $item) {

            if ($item["nat"] == "Consommation") {
                $stockcount -= $item["qte"];
                $mvt[$key]["stock"] = $stockcount;
            }

            if ($item["nat"] == "Entrée") {
                $stockcount += $item["qte"];
                $mvt[$key]["stock"] = $stockcount;
            }


            if ($item["nat"] == "Transfert sortant") {
                $stockcount -= $item["qte"];
                $mvt[$key]["stock"] = $stockcount;
            }

            if ($item["nat"] == "Transfert entrant") {
                $stockcount += $item["qte"];
                $mvt[$key]["stock"] = $stockcount;
            }
        }

        return $mvt;
    }

//    public static function get_stkval($arrayopt = array()) {
//
//        if (empty($arrayopt)) {
//            return false;
//        }
//
//        $exe = $arrayopt["exe"];
//
//        if (isset($arrayopt["mag"])) {
//            $mag = $arrayopt["mag"];
//            $initmag = " and b.magCode = :mag ";
//            $entmag = " and d.entMagrecepcode = :mag ";
//            $sortmag = " and f.consMagcode =:mag ";
//        } else {
//            $initmag = "";
//            $entmag = "";
//            $sortmag = "";
//        }
//
//        if (isset($arrayopt["art"])) {
//            $art = $arrayopt["art"];
//            $initart = " and b.artCode = :artCode";
//        } else {
//            $initart = "";
//        }
//
//        if (isset($arrayopt["date"])) {
//            $entdate = " and d.entDatereception <= :todate ";
//            $sortdate = " and f.consDate <= :todate ";
//        } else {
//            $entdate = "";
//            $sortdate = "";
//        }
//
//        $em = Model::$em;
//
//        $epr = $em->createQueryBuilder();
//        $stkquery = $em->createQueryBuilder();
//        $stkqr = $stkquery->select("a.artCode,a.artDernierprix,a.artDesignation")
//                ->addSelect("(select b.stkInitial  from StkStock b where  b.exercice = :exe  $initmag and b.artCode = a.artCode ) as init")
//                ->addSelect("(select sum(c.entQte + c.entQtesurplus) from  StkEntreDetail c join c.entCode d  where (d.entEtatcode = 31 or d.entEtatcode = 33) and c.entArtcode = a.artCode and c.entExercice = :exe $entmag $entdate) as ent")
//                ->addSelect("(select sum(e.consQte) from  StkConsomationDetail e join e.consCode f  where (f.consEtatcode = 51 or f.consEtatcode = 54) and e.consArtcode = a.artCode and e.consExercice = :exe $sortmag $sortdate) as sort")
//                ->from('StkArticle', 'a');
//        if (isset($arrayopt["art"])) {
//            $stkqr = $stkqr->where('a.artCode = :artCode')
//                    ->setParameter('artCode', $art);
//        }
//
//
//        if (isset($arrayopt["date"])) {
//            if (gettype($arrayopt["date"]) == "object") {
//                $stkqr = $stkqr->setParameter('todate', $arrayopt["date"]);
//            } else {
//                $stkqr = $stkqr->setParameter('todate', new \DateTime($this->dfrm($arrayopt["date"])));
//            }
//        }
//
//
//
//        $stkqr = $stkqr->setParameter('exe', $exe);
//
//        if (isset($arrayopt["mag"])) {
//            $stkqr = $stkqr->setParameter('mag', $mag);
//        }
//
//        $stk = $stkqr->getQuery()->getScalarResult();
//
//        foreach ($stk as $key => $stks) {
//            if ($stks["init"] == NULL)
//                $stk[$key]["init"] = 0;
//            if ($stks["ent"] == NULL)
//                $stk[$key]["ent"] = 0;
//            if ($stks["sort"] == NULL)
//                $stk[$key]["sort"] = 0;
//
//
//
//            $stk[$key]["finale"] = $stk[$key]["init"] + $stk[$key]["ent"] - $stk[$key]["sort"];
//
//
//            $stk[$key]["entval"] = $stk[$key]["ent"] * $stk[$key]["artDernierprix"];
//            $stk[$key]["sortval"] = $stk[$key]["sort"] * $stk[$key]["artDernierprix"];
//            $stk[$key]["finalval"] = $stk[$key]["finale"] * $stk[$key]["artDernierprix"];
//
//
//            if (isset($arrayopt["mag"])) {
//                $stock = $em->find("StkStock", array(
//                    "artCode" => $stk[$key]["artCode"],
//                    "magCode" => $arrayopt["mag"],
//                    "exercice" => $arrayopt["exe"],
//                ));
//                $stock->setStkEntre($stk[$key]["ent"]);
//                $stock->setStkSortie($stk[$key]["sort"]);
//                $stock->setStkFinale($stk[$key]["finale"]);
//                $em->persist($stock);
//                $em->flush();
//            }
//        }
//
//        return $stk;
//    }


    public static function get_stkval($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];

        if (isset($arrayopt["mag"])) {
            $mag = $arrayopt["mag"];
            $initmag = " and b.magCode = :mag ";
            $entmag = " and d.entMagrecepcode = :mag ";
            $sortmag = " and f.consMagcode =:mag ";
            $trentmag = " and r.transMagdem =:mag  ";
            $trsortmag = " and t.transMagliv =:mag  ";
        } else {
            $initmag = "";
            $entmag = "";
            $sortmag = "";
            $trentmag = "";
            $trsortmag = "";
        }

        if (isset($arrayopt["art"])) {
            $art = $arrayopt["art"];
            $initart = " and b.artCode = :artCode";
        } else {
            $initart = "";
        }

        if (isset($arrayopt["date"])) {
            $entdate = " and d.entDatereception <= :todate ";
            $sortdate = " and f.consDate <= :todate ";
            $trentdate = " and r.stkTransDateliv <= :todate ";
            $trsortdate = " and t.stkTransDateliv <= :todate ";
        } else {
            $entdate = "";
            $sortdate = "";
            $trentdate = "";
            $trsortdate = "";
        }

        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("a.artCode,a.artDernierprix,a.artDesignation")
                ->addSelect("(select b.stkInitial from StkStock b where  b.exercice = :exe  $initmag and b.artCode = a.artCode ) as init")
                ->addSelect("(select sum(c.entQte + c.entQtesurplus) from  StkEntreDetail c join c.entCode d  where (d.entEtatcode = 31 or d.entEtatcode = 33) and c.entArtcode = a.artCode and c.entExercice = :exe $entmag $entdate) as ent")
                ->addSelect("(select sum(e.consQte) from  StkConsomationDetail e join e.consCode f  where (f.consEtatcode = 52 or f.consEtatcode = 54) and e.consArtcode = a.artCode and e.consExercice = :exe $sortmag $sortdate) as sort");

        if (isset($arrayopt["mag"])) {
            $stkqr = $stkqr->addSelect("(select sum(q.transQteliv) from  StkTransDetail q join q.transCode r  where (r.transEtatcode = 92) and q.transArtcode = a.artCode and q.transExercice = :exe $trentmag $trentdate) as transent")
                    ->addSelect("(select sum(s.transQteliv) from  StkTransDetail s join s.transCode t  where (t.transEtatcode = 92) and s.transArtcode = a.artCode and s.transExercice = :exe $trsortmag $trsortdate) as transsort");
        }

        $stkqr = $stkqr->from('StkArticle', 'a');

        if (isset($arrayopt["art"])) {
            $stkqr = $stkqr->where('a.artCode = :artCode')
                    ->setParameter('artCode', $art);
        }

        if (isset($arrayopt["date"])) {
            if (gettype($arrayopt["date"]) == "object") {
                $stkqr = $stkqr->setParameter('todate', $arrayopt["date"]);
            } else {
                $stkqr = $stkqr->setParameter('todate', new \DateTime($this->dfrm($arrayopt["date"])));
            }
        }

        $stkqr = $stkqr->setParameter('exe', $exe);

        if (isset($arrayopt["mag"])) {
            $stkqr = $stkqr->setParameter('mag', $mag);
        }

        $stk = $stkqr->getQuery()->getScalarResult();



        foreach ($stk as $key => $stks) {

            if ($stks["init"] == NULL)
                $stk[$key]["init"] = 0;
            if ($stks["ent"] == NULL)
                $stk[$key]["ent"] = 0;
            if ($stks["sort"] == NULL)
                $stk[$key]["sort"] = 0;
            if ($stks["transent"] == NULL)
                $stk[$key]["transent"] = 0;
            if ($stks["transsort"] == NULL)
                $stk[$key]["transsort"] = 0;

            $stk[$key]["finale"] = $stk[$key]["init"] + $stk[$key]["ent"] + $stk[$key]["transent"] - $stk[$key]["sort"] - $stk[$key]["transsort"];
            $stk[$key]["ent"] = $stk[$key]["ent"] + $stk[$key]["transent"];
            $stk[$key]["sort"] = $stk[$key]["sort"] + $stk[$key]["transsort"];

            $stk[$key]["entval"] = $stk[$key]["ent"] * $stk[$key]["artDernierprix"];
            $stk[$key]["sortval"] = $stk[$key]["sort"] * $stk[$key]["artDernierprix"];
            $stk[$key]["finalval"] = $stk[$key]["finale"] * $stk[$key]["artDernierprix"];

            if (isset($arrayopt["mag"])) {

                $stock = $em->find("StkStock", array(
                    "artCode" => $stk[$key]["artCode"],
                    "magCode" => $arrayopt["mag"],
                    "exercice" => $arrayopt["exe"],
                ));

                if (!empty($stock)) {
                    $stock->setStkEntre($stk[$key]["ent"] + $stk[$key]["transent"]);
                    $stock->setStkSortie($stk[$key]["sort"] + $stk[$key]["transsort"]);
                    $stock->setStkFinale($stk[$key]["finale"]);
                    $em->persist($stock);
                    $em->flush();

                    //init stock exe + 1
                    $stockplus = $em->find("StkStock", array(
                        "artCode" => $stk[$key]["artCode"],
                        "magCode" => $arrayopt["mag"],
                        "exercice" => (int) $arrayopt["exe"] + 1,
                    ));

                    if (empty($stockplus)) {
                        $stockplus = new StkStock();
                        $stockplus->setArtCode($em->find("stkArticle", $stk[$key]["artCode"]));
                        $stockplus->setMagCode($arrayopt["mag"]);
                        $stockplus->setExercice((int) $arrayopt["exe"] + 1);
                        $stockplus->setStkInitial($stk[$key]["finale"]);
                        $stockplus->setStkEntre(0);
                        $stockplus->setStkSortie(0);
                        $stockplus->setStkFinale($stk[$key]["finale"]);
                        $em->persist($stockplus);
                        $em->flush();
                    } else {
                        $stockplus->setStkInitial($stk[$key]["finale"]);
                        $stockplus->setStkFinale($stk[$key]["finale"]);
                        $em->persist($stockplus);
                        $em->flush();
                    }
                }
            }
        }

        return $stk;
    }

    public static function get_stk($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];

        if (isset($arrayopt["mag"])) {
            $mag = $arrayopt["mag"];
            $initmag = " and b.magCode = :mag ";
            $entmag = " and d.entMagrecepcode = :mag ";
            $sortmag = " and f.consMagcode =:mag ";
            $trentmag = " and r.transMagdem =:mag  ";
            $trsortmag = " and t.transMagliv =:mag  ";
        } else {
            $initmag = "";
            $entmag = "";
            $sortmag = "";
            $trentmag = "";
            $trsortmag = "";
        }

        if (isset($arrayopt["art"])) {
            $art = $arrayopt["art"];
            $initart = " and b.artCode = :artCode";
        } else {
            $initart = "";
        }

        if (isset($arrayopt["date"])) {
            $entdate = " and d.entDatereception <= :todate ";
            $sortdate = " and f.consDate <= :todate ";
            $trentdate = " and r.stkTransDateliv <= :todate ";
            $trsortdate = " and t.stkTransDateliv <= :todate ";
        } else {
            $entdate = "";
            $sortdate = "";
            $trentdate = "";
            $trsortdate = "";
        }

        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("a.artCode")
                ->addSelect("(select b.stkInitial from StkStock b where  b.exercice = :exe  $initmag and b.artCode = a.artCode ) as init")
                ->addSelect("(select sum(c.entQte + c.entQtesurplus) from  StkEntreDetail c join c.entCode d  where (d.entEtatcode = 31 or d.entEtatcode = 33) and c.entArtcode = a.artCode and c.entExercice = :exe $entmag $entdate) as ent")
                ->addSelect("(select sum(e.consQte) from  StkConsomationDetail e join e.consCode f  where (f.consEtatcode = 52 or f.consEtatcode = 54) and e.consArtcode = a.artCode and e.consExercice = :exe $sortmag $sortdate) as sort");

        if (isset($arrayopt["mag"])) {
            $stkqr = $stkqr->addSelect("(select sum(q.transQteliv) from  StkTransDetail q join q.transCode r  where (r.transEtatcode = 92) and q.transArtcode = a.artCode and q.transExercice = :exe $trentmag $trentdate) as transent")
                    ->addSelect("(select sum(s.transQteliv) from  StkTransDetail s join s.transCode t  where (t.transEtatcode = 92) and s.transArtcode = a.artCode and s.transExercice = :exe $trsortmag $trsortdate) as transsort");
        }

        $stkqr = $stkqr->from('StkArticle', 'a');
        
 

        if (isset($arrayopt["art"])) {
            $stkqr = $stkqr->where('a.artCode = :artCode')
                    ->setParameter('artCode', $art);
        }

        if (isset($arrayopt["date"])) {
            if (gettype($arrayopt["date"]) == "object") {
                $stkqr = $stkqr->setParameter('todate', $arrayopt["date"]);
            } else {
                $stkqr = $stkqr->setParameter('todate', new \DateTime($this->dfrm($arrayopt["date"])));
            }
        }

        $stkqr = $stkqr->setParameter('exe', $exe);

        if (isset($arrayopt["mag"])) {
            $stkqr = $stkqr->setParameter('mag', $mag);
        }

        $stk = $stkqr->getQuery()->getScalarResult();



        foreach ($stk as $key => $stks) {

            if ($stks["init"] == NULL)
                $stk[$key]["init"] = 0;
            if ($stks["ent"] == NULL)
                $stk[$key]["ent"] = 0;
            if ($stks["sort"] == NULL)
                $stk[$key]["sort"] = 0;
            if ($stks["transent"] == NULL)
                $stk[$key]["transent"] = 0;
            if ($stks["transsort"] == NULL)
                $stk[$key]["transsort"] = 0;

            $stk[$key]["finale"] = $stk[$key]["init"] + $stk[$key]["ent"] + $stk[$key]["transent"] - $stk[$key]["sort"] - $stk[$key]["transsort"];

            $stk[$key]["ent"] = $stk[$key]["ent"] + $stk[$key]["transent"];
            $stk[$key]["sort"] = $stk[$key]["sort"] + $stk[$key]["transsort"];

            if (isset($arrayopt["mag"])) {

                $stock = $em->find("StkStock", array(
                    "artCode" => $stk[$key]["artCode"],
                    "magCode" => $arrayopt["mag"],
                    "exercice" => $arrayopt["exe"],
                ));

                if (!empty($stock)) {
                    $stock->setStkEntre($stk[$key]["ent"] + $stk[$key]["transent"]);
                    $stock->setStkSortie($stk[$key]["sort"] + $stk[$key]["transsort"]);
                    $stock->setStkFinale($stk[$key]["finale"]);
                    $em->persist($stock);
                    $em->flush();

                    //init stock exe + 1
                    $stockplus = $em->find("StkStock", array(
                        "artCode" => $stk[$key]["artCode"],
                        "magCode" => $arrayopt["mag"],
                        "exercice" => (int) $arrayopt["exe"] + 1,
                    ));

                    if (empty($stockplus)) {
                        $stockplus = new StkStock();
                        $stockplus->setArtCode($em->find("stkArticle", $stk[$key]["artCode"]));
                        $stockplus->setMagCode($arrayopt["mag"]);
                        $stockplus->setExercice((int) $arrayopt["exe"] + 1);
                        $stockplus->setStkInitial($stk[$key]["finale"]);
                        $stockplus->setStkEntre(0);
                        $stockplus->setStkSortie(0);
                        $stockplus->setStkFinale($stk[$key]["finale"]);
                        $em->persist($stockplus);
                        $em->flush();
                    } else {
                        $stockplus->setStkInitial($stk[$key]["finale"]);
                        $stockplus->setStkFinale($stk[$key]["finale"]);
                        $em->persist($stockplus);
                        $em->flush();
                    }
                }
            }
        }

        return $stk;
    }

    public static function get_stk_nbalert($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("count(a) as cnt")
                ->from('StkStock', 'a')
                ->where('a.magCode = :magCode')
                ->setParameter('magCode', $mag)
                ->andWhere('a.exercice = :exe')
                ->setParameter('exe', $exe)
                ->andWhere('a.stkAlert >= a.stkFinale')
                ->andWhere('a.stkFinale != 0');
        $stk = $stkqr->getQuery()->getScalarResult();

        return $stk[0]["cnt"];
    }

    public static function get_stk_alert($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("a", "b")
                ->from('StkStock', 'a')
                ->join('a.artCode', 'b')
                ->where('a.magCode = :magCode')
                ->setParameter('magCode', $mag)
                ->andWhere('a.exercice = :exe')
                ->setParameter('exe', $exe)
                ->andWhere('a.stkAlert >= a.stkFinale')
                ->andWhere('a.stkFinale != 0');

        $stk = $stkqr->getQuery()->getScalarResult();

        return $stk;
    }

    public static function get_stk_nbsecurite($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("count(a) as cnt")
                        ->from('StkStock', 'a')
                        ->where('a.magCode = :magCode')
                        ->setParameter('magCode', $mag)
                        ->andWhere('a.exercice = :exe')
                        ->setParameter('exe', $exe)
                        ->andWhere('a.stkSecurite >= a.stkFinale')->andWhere('a.stkFinale != 0');
        $stk = $stkqr->getQuery()->getScalarResult();

        return $stk[0]["cnt"];
    }

    public static function get_stk_securite($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("a", "b")
                ->from('StkStock', 'a')
                ->join('a.artCode', 'b')
                ->where('a.magCode = :magCode')
                ->setParameter('magCode', $mag)
                ->andWhere('a.exercice = :exe')
                ->setParameter('exe', $exe)
                ->andWhere('a.stkSecurite >= a.stkFinale')
                ->andWhere('a.stkFinale != 0');
        $stk = $stkqr->getQuery()->getScalarResult();

        return $stk;
    }

    public static function get_stk_nbrupture($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("count(a) as cnt")
                ->from('StkStock', 'a')
                ->where('a.magCode = :magCode')
                ->setParameter('magCode', $mag)
                ->andWhere('a.exercice = :exe')
                ->setParameter('exe', $exe)
                ->andWhere('a.stkFinale = 0');
        $stk = $stkqr->getQuery()->getScalarResult();

        return $stk[0]["cnt"];
    }

    public static function get_stk_rupture($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("a", "b")
                ->from('StkStock', 'a')
                ->join('a.artCode', 'b')
                ->where('a.magCode = :magCode')
                ->setParameter('magCode', $mag)
                ->andWhere('a.exercice = :exe')
                ->setParameter('exe', $exe)
                ->andWhere('a.stkFinale = 0');
        $stk = $stkqr->getQuery()->getScalarResult();

        return $stk;
    }

    public static function get_stk_nbperim($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $next = new DateTime();
        $next->modify('+1 month');

        $nexts = new DateTime();
        $nexts->modify('+2 month');

        $epr = $em->createQueryBuilder();
        $entquery = $em->createQueryBuilder();
        $entqr = $entquery->select("count(IDENTITY(a)) as cnt ")
                ->from('StkEntreDetail', 'a')
                ->andWhere('a.entDateperomption > :deb')
                ->setParameter('deb', $next)
                ->andWhere('a.entDateperomption < :fin')
                ->setParameter('fin', $nexts);

        $ent = $entqr->getQuery()->getScalarResult();

        return $ent[0]["cnt"];
    }

    public static function get_stk_perim($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $mag = $arrayopt["mag"];
        $em = Model::$em;

        $next = new DateTime();
        $next->modify('+1 month');

        $nexts = new DateTime();
        $nexts->modify('+2 month');

        $epr = $em->createQueryBuilder();
        $entquery = $em->createQueryBuilder();
        $entqr = $entquery->select("a")
                ->from('StkEntreDetail', 'a')
                ->andWhere('a.entDateperomption > :deb')
                ->setParameter('deb', $next)
                ->andWhere('a.entDateperomption < :fin')
                ->setParameter('fin', $nexts);

        $ent = $entqr->getQuery()->getScalarResult();

        return $ent;
    }

    public static function get_art_stk($arrayopt = array()) {

        if (empty($arrayopt)) {
            return false;
        }

        $exe = $arrayopt["exe"];
        $mag = $arrayopt["mag"];
        $art = $arrayopt["art"];
        $em = Model::$em;

        $epr = $em->createQueryBuilder();
        $stkquery = $em->createQueryBuilder();
        $stkqr = $stkquery->select("a", "b")
                ->from('StkStock', 'a')
                ->join('a.artCode', 'b')
                ->where('b.artCode = :art')
                ->setParameter('art', $art)
                ->andWhere('a.magCode = :magCode')
                ->setParameter('magCode', $mag)
                ->andWhere('a.exercice = :exe')
                ->setParameter('exe', $exe);


        $art = $stkqr->getQuery()->getScalarResult();

        return $art;
    }

}
