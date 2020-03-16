<?php

class Metier_Sgs {

    private static $_instance = null;

    private function __construct() {
        //definire la date d'exercice actuel
    }

    public static function get_exercice($exe = "") {

        $db = Model::getDb();
        $idprf = Zend_Auth::getInstance()->getIdentity()->idPRF;

        $tables = new Zend_Db_Table('exe');
        $tablesrel = new Zend_Db_Table('prf_has_exe');

        $qrexe = $tables->select();
        $rsexe = $qrexe->query();
        $exelist = $rsexe->fetchAll();
        $exes = array();

        foreach ($exelist as $item) {

            if ($item["idETAT"] != 51) {

                $qrrel = $tablesrel->select()->where("exe = ?", $item["exe"])->where("idPRF = ?", $idprf);
                $rsrel = $qrrel->query();
                $droirel = $rsrel->fetchAll();

                if (!empty($droirel)) {
                    array_push($exes, $item["exe"]);
                }
            } else {
                array_push($exes, $item["exe"]);
            }
        }

        if (empty($exe)) {
            arsort($exes);
            return $exes;
        } else {
            if (in_array($exe, $exes)) {
                return $exe;
            } else {
                Message::set_info_msg("Vous n'avez pas l'autorisation de modifier sur cet exercice");
                echo json_encode(array("etat" => "1"));
                die();
            }
        }
    }

    public static function exist_in_table($id, $table, $db) {
        
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Metier_Sgs();
        }
        return self::$_instance;
    }

    public static function update_bon_stock($oper, $art, $boncod, $mag, $exe, $newqte, $tabled, $mouv = null) {

        switch ($tabled) {

            case "BEMARCHED" : $champ = "BEQTE";
                $boncodlib = "becod";
                $bonexelib = "beexe";
                $mvt = "e";
                break;
            case "BEETRD" : $champ = "BEQTE";
                $boncodlib = "becod";
                $bonexelib = "beexe";
                $mvt = "e";
                break;
            case "BED" : $champ = "BEQTE";
                $boncodlib = "becod";
                $bonexelib = "beexe";
                $mvt = "e";
                break;
            case "BEDAMD" : $champ = "BEQTE";
                $boncodlib = "becod";
                $bonexelib = "beexe";
                $mvt = "e";
                break;

            case "BTID" : $champ = "BTIQREC";
                $boncodlib = "bticod";
                $bonexelib = "btiexe";
                $mvt = $mouv;
                break;

            case "BCNDB" : $champ = "BCNQLIV";
                $boncodlib = "bcncod";
                $bonexelib = "bcnexe";
                $mvt = "s";
                break;

            case "BCND" : $champ = "BCNQLIV";
                $boncodlib = "bcncod";
                $bonexelib = "bcnexe";
                $mvt = "s";
                break;

            case "BCNHABD" : $champ = "BCNQLIV";
                $boncodlib = "bcncod";
                $bonexelib = "bcnexe";
                $mvt = "s";
                break;
            case "BTRD" : $champ = "QTERREC";
                $boncodlib = "btrcod";
                $bonexelib = "btrexe";
                $mvt = $mouv;
                break;
        }

        $db = Model::getDb2();

//calculé la différence
        if ($oper == "supp_bon") {
            $val = $db->select()->from($tabled)->where($boncodlib . " = ?", $boncod)->where($bonexelib . " = ?", $exe)->query()->fetchAll();
            $error = false;
            foreach ($val as $item) {
                if (!self::update_stock($mvt, $item["ARTCOD"], $mag, $exe, (int) $item[$champ])) {
                    $error = true;
                }
            }

            if ($error) {
                return false;
            } else {
                return true;
            }
        } else if ($oper == "ajout_art") {
            $diff = "-" . $newqte;
            if (self::update_stock($mvt, $art, $mag, $exe, $diff)) {
                return true;
            } else {
                return false;
            }
        } else {

            $val = $db->select()->from($tabled, $champ)->where("artcod = ?", $art)->where($boncodlib . " = ?", $boncod)->where($bonexelib . " = ?", $exe)->query()->fetchAll();
            if ($oper == "supp_art") {
                $diff = (int) $val[0][$champ];
                if (self::update_stock($mvt, $art, $mag, $exe, $diff)) {
                    return true;
                } else {
                    return false;
                }
            } else if ($oper == "up_art") {
                $diff = (int) $val[0][$champ] - $newqte;

                if (self::update_stock($mvt, $art, $mag, $exe, $diff)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public static function qte_in_stk($mag, $exe, $art = "") {
        $db = Model::getDb2();
        $val = $db->select()->from("STK", "STKACT")->where("artcod = ?", $art)->where("MAGCOD" . " = ?", $mag)->where("STKEXE = ?", $exe)->query()->fetchAll();
        if (isset($val[0]["STKACT"])) {
            return $val[0]["STKACT"];
        } else {
            return 0;
        }
    }

    public static function get_inv($exe, $mag, $grb = "", $art = "") {

        try {

            $db = Model::getDb2();

            if (!$db->describeTable("INVART$exe", "SGSFIC")) {
                return false;
            };

            $res = $db->select()->from("INVART" . $exe, array("ARTART", "ARTQTE", "ARTJJI", "ARTMMI", "ARTAAI"))->where("ARTMAG" . " = ?", $mag)->where("ARTJJI + ARTMMI + ARTAAI != ?", 0);


            if (!empty($grb)) {
                $res = $res->where("substr(DIGITS(ARTART),1,2)" . " = ?", $grb);
            } else {
                if (!empty($art)) {
                    $res = $res->where("ARTART" . " = ?", $art);
                }
            }

            $val = $res->query()->fetchAll();

            $invtab = array();

            foreach ($val as $key => $tem) {
                if ($tem["ARTMMI"] < 10)
                    $tem["ARTMMI"] = "0" . $tem["ARTMMI"];
                if ($tem["ARTJJI"] < 10)
                    $tem["ARTJJI"] = "0" . $tem["ARTJJI"];

                $inv = array(
                    "ARTCOD" => $tem["ARTART"],
                    "ARTQTE" => $tem["ARTQTE"],
                    "DATEINV" => $tem["ARTAAI"] . $tem["ARTMMI"] . $tem["ARTJJI"]
                );

                array_push($invtab, $inv);
            }

            return $invtab;
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            return false;
        }
    }

    public static function update_stock($mouv, $art, $mag, $exe, $diff) {

        if ($mouv == "e") {

            $db = Model::getDb2();
            if ($db->query("update stk set  STKENT = STKENT - (" . (string) $diff . ") ,  STKACT = STKACT - (" . (string) $diff . ") where MAGCOD = " . $mag . " and ARTCOD = " . $art . " and STKEXE = " . $exe)) {
                return true;
            } else {
                return false;
            }
        } else if ($mouv == "s") {
            $db = Model::getDb2();
            if ($qry = $db->query("update STK set STKSORT = STKSORT - (" . (string) $diff . ") ,  STKACT = STKACT + (" . (string) $diff . ") where MAGCOD = " . $mag . " and ARTCOD = " . $art . " and STKEXE = " . $exe)) {
                return true;
            } else {
                return false;
            }
        } else if ($mouv == "r") {

            if ((float) $diff < 0) {
                $champ = "STKSORT";
                $sgn = "-";
            } else if ((float) $diff > 0) {
                $champ = "STKENT";
                $sgn = "+";
            } else {
                return false;
            }

            $db = Model::getDb2();
            if ($qry = $db->query("update STK set STKSORT = $champ + (" . (string) abs($diff) . ") ,  STKACT = STKACT $sgn (" . (string) abs($diff) . ") where MAGCOD = " . $mag . " and ARTCOD = " . $art . " and STKEXE = " . $exe)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function get_stk($art = "", $mag, $exe, $grp = "", $artaskey = false) {

        $db = Model::getDb2();
        $art_array = array();
        $reqstk = $db->select()->from("STK")->where("MAGCOD = ?", $mag)->where("STKEXE = ?", $exe);

        if (!empty($art)) {
            $reqstk = $reqstk->where("ARTCOD = ?", $art);
        } else {
            if (!empty($grp)) {
                $reqstk = $reqstk->where("substr(DIGITS(ARTCOD),1,2) = ?", $grp);
            }
        }

        $restsk = $reqstk->query()->fetchAll();

        if (!$artaskey) {

            foreach ($restsk as $item) {
                $art = array(
                    "ART" => $item["ARTCOD"],
                    "INIT" => $item["STKINIT"],
                    "ENT" => $item["STKENT"],
                    "SORT" => $item["STKSORT"],
                    "ACT" => $item["STKACT"]
                );
                array_push($art_array, $art);
            }
        } else {

            foreach ($restsk as $item) {
                $art = array(
                    "ART" => $item["ARTCOD"],
                    "INIT" => $item["STKINIT"],
                    "ENT" => $item["STKENT"],
                    "SORT" => $item["STKSORT"],
                    "ACT" => $item["STKACT"]
                );

                $art_array[$item["ARTCOD"]] = $art;
            }
        }

        return $art_array;
    }

    public static function get_stk_art_STT($art, $exe) {

        $db = Model::getDb2();

        $reqstk = $db->query("select sum(STKACT) as qte  from STK where ARTCOD = $art and stkexe = $exe");

        $restsk = $reqstk->fetchAll();

        return $restsk[0]["QTE"];
    }

    public static function init_stk($art = "", $mag, $exe) {

        $db = Model::getDb2();
        $art_array = array();
        $reqstk = $db->select()->from("STK")->where("MAGCOD = ?", $mag)->where("STKEXE = ?", $exe);

        if (!empty($art)) {
            $reqstk = $reqstk->where("ARTCOD = ?", $art);
        }

        $restsk = $reqstk->query()->fetchAll();

        foreach ($restsk as $item) {
            $art = array(
                "ART" => $item["ARTCOD"],
                "INIT" => $item["STKINIT"],
                "ENT" => 0,
                "SORT" => 0
            );
            array_push($art_array, $art);
        }
        return $art_array;
    }

    public static function correct_stk_mag($exe, $mag, $art = "", $grp = "") {

        self::cor_ent_art_mag($mag, $exe, $art, $grp);

        self::cor_sort_art_mag($mag, $exe, $art, $grp);

        if (!empty($art)) {
            $artqr = " AND ARTCOD = " . $art;
            $artrds = " AND A.ARTCOD = " . $art;
        } else {
            $artqr = "";
            $artrds = "";
        }

        if (!empty($grp)) {
            $grpqr = " AND substr(DIGITS(ARTCOD),1,2) = " . $grp;
            $grprds = " AND substr(DIGITS(A.ARTCOD),1,2) = " . $grp;
        } else {
            $grpqr = "";
            $grprds = "";
        }

        $db = Model::getDb2();

        $db->query("update stk set stkact = (stkinit + stkent) - stksort where magcod = " . $mag . " AND stkexe = " . $exe . $artqr . $grpqr);
        $db->query("update sgsfic.STK A 
                    set A.STKACT = 
                    case when (A.STKACT + (select sum(QTERDS) from sgsfic.RDSSTK B where A.ARTCOD = B.ARTCOD and A.MAGCOD = B.MAGCOD and A.STKEXE =B.RDSEXE group by B.ARTCOD)) IS NOT NULL THEN 
                    (A.STKACT + (select sum(QTERDS) from sgsfic.RDSSTK B where A.ARTCOD = B.ARTCOD and A.MAGCOD = B.MAGCOD and A.STKEXE =B.RDSEXE group by B.ARTCOD)) 
                    ELSE A.STKACT + 0 END where A.MAGCOD = $mag AND A.STKEXE = " . $exe . $artrds . $grprds);
    }

    public static function cor_ent_art_mag($mag, $exe, $art = "", $grp = "") {

        $db = Model::getDb2();

        $etable = array("BEMARCHE", "BE", "BEETR");

        if (!empty($art)) {
            $artqr = " AND C.ARTCOD = " . $art;
        } else {
            $artqr = "";
        }

        if (!empty($grp)) {
            $grpqr = " AND substr(DIGITS(C.ARTCOD),1,2) = " . $grp;
        } else {
            $grpqr = "";
        }

        if ($db->query("update stk C set STKENT = 0  where C.MAGCOD = " . $mag . " AND C.STKEXE = " . $exe . $artqr . $grpqr)) {

            foreach ($etable as $tbl) {

                $qtrentm = $db->query("update stk C set STKENT = STKENT + (SELECT case when SUM(B.BEQTE + B.BEQTSP) IS NULL then 0 else SUM(B.BEQTE + B.BEQTSP) end AS SUMQTE 
			   FROM " . $tbl . "E A LEFT JOIN " . $tbl . "D B                        
			   ON A.BECOD = B.BECOD AND A.BEEXE = B.BEEXE                      
			   WHERE A.BEEXE = C.STKEXE AND B.ARTCOD = C.ARTCOD AND A.MAGCOD = C.MAGCOD ) where C.MAGCOD = " . $mag . " AND C.STKEXE = " . $exe . $artqr . $grpqr);
            }

            $qtrentb = $db->query("update stk C set STKENT = STKENT + (SELECT case when SUM(B.BTIQREC) IS NULL then 0 else SUM(B.BTIQREC) end AS SUMQTE
			   FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
			   WHERE A.BTIEXE = C.STKEXE  AND A.MAGDEM = C.MAGCOD AND B.ARTCOD = C.ARTCOD AND B.ETBONCOD = 14) where C.MAGCOD = " . $mag . " AND C.STKEXE = " . $exe . $artqr . $grpqr);
        }

        return true;
    }

    public static function cor_sort_art_mag($mag, $exe, $art = "", $grp = "") {

        $db = Model::getDb2();

        $etable = array("BCNE" => "BCND", "BCNEB" => "BCNDB", "BCNHABE" => "BCNHABD");

        if (!empty($art)) {
            $artqr = " AND C.ARTCOD = " . $art;
        } else {
            $artqr = "";
        }

        if (!empty($grp)) {
            $grpqr = " AND substr(DIGITS(C.ARTCOD),1,2) = " . $grp;
        } else {
            $grpqr = "";
        }


        if ($qrinit = $db->query("update stk C set STKSORT = 0  where C.MAGCOD = " . $mag . " AND C.STKEXE = " . $exe . $artqr . $grpqr)) {

            foreach ($etable as $key => $tbl) {

                $qtrsortm = $db->query("update stk C set STKSORT = STKSORT + (SELECT case when SUM(B.BCNQLIV) IS NULL then 0 else SUM(B.BCNQLIV) end AS SUMQTE
				FROM " . $key . " A LEFT JOIN " . $tbl . " B ON A.BCNCOD = B.BCNCOD AND A.BCNEXE = B.BCNEXE
				WHERE A.BCNEXE = C.STKEXE AND A.MAGCOD = C.MAGCOD AND B.ARTCOD = C.ARTCOD AND B.ETBONCOD = 8) where C.MAGCOD = " . $mag . " AND C.STKEXE = " . $exe . $artqr . $grpqr);
            }

            $qtrsortb = $db->query("update stk C set STKSORT = STKSORT + ( SELECT  case when SUM(B.BTIQREC) IS NULL then 0 else SUM(B.BTIQREC) end AS SUMQTE
			   FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
			   WHERE A.BTIEXE =  C.STKEXE  AND A.MAGDES = C.MAGCOD  AND B.ARTCOD = C.ARTCOD AND B.ETBONCOD = 14 )  where C.MAGCOD = " . $mag . " AND C.STKEXE = " . $exe . $artqr . $grpqr);
        }

        return true;
    }

    public static function art_detail($artcod, $champ = "", $magdem = "", $magdes = "", $exe = "") {

        $db = Model::getDb2();

        $artinfo = $inv = $db->select()->from(array("A"=> "ART"));

        if (!empty($magdem) && !empty($exe)) {
            $inv = $inv->joinLeft(array("B"=>"STK",), "A.ARTCOD = B.ARTCOD and B.STKEXE = $exe and B.MAGCOD = $magdem", array(new Zend_Db_Expr("B.STKACT AS DEMACT")));
        }

        if (!empty($magdes) && !empty($exe)) {
            $inv = $inv->joinLeft(array("C"=>"STK"), "A.ARTCOD = C.ARTCOD and C.STKEXE = $exe and C.MAGCOD = $magdes", array(new Zend_Db_Expr("C.STKACT AS DESACT")));
        }

        $inv = $inv->joinLeft(array("D"=>"UDIST"), "D.UDCOD = A.UDCOD", array("UDLIB"));

        $inv = $inv->where("A.ARTCOD = ?", $artcod);

        $artinfo = $inv->query()->fetchAll();

        if (!empty($artinfo)) {
            $artinfo = $artinfo[0];


            $artref = $inv = $db->select()->from("REFART")->where("ARTCOD = ?", $artcod)->where("ETATCOD = ?", 1)->query()->fetchAll();
            if (!empty($artref)) {
                $artinfo["ARTREF"] = $artref[0]["REF"];
            }

            if ($champ == "")
                return $artinfo;
            else
                return $artinfo[$champ];
        }else {
            return false;
        }
    }

    public static function art_in_mag($artcod, $mag) {

        $db = Model::getDb2();

        $artmag = $db->select()->from("ARTMAG")->where("ARTCOD = ?", $artcod)->where("MAGCOD = ?", $mag)->query()->fetchAll();

        if (!empty($artmag))
            return true;
        else
            return false;
    }

    public static function cal_stkp($exe, $mag, $art = "") {

        ini_set('memory_limit', '-1');

        set_time_limit(0);

        $db = Model::getDb2();

        if (self::del_from_stkp($exe, $mag, $art)) {

            if (self::insert_into_stkp($exe, $mag, $art)) {

                self::call_stkp_ent($exe, $mag, $art);
                self::call_stkp_sort($exe, $mag, $art);
            }
        }
    }

    private static function insert_into_stkp($exe, $mag, $art) {

        $db = Model::getDb2();

        if (!empty($mag)) {
            $qrmaginsert = " AND B.MAGCOD = $mag";
        } else {
            $qrmaginsert = "AND B.MAGCOD in (1,2,4610,4620,4625,4630,4640,4650,4660,4670,4680)";
        }

        if (!empty($art)) {
            $qrartinsert = " AND B.ARTCOD = $art";
        } else {
            $qrartinsert = "";
        }

        if ($db->query("insert into sgsfic.stkp (
                    select B.ARTCOD , B.MAGCOD , B.STKEXE , B.STKINIT , (A.ARTAAI || case when A.ARTMMI < 10 THEN ( '' || '0' || A.ARTMMI) ELSE '' || A.ARTMMI END || case when A.ARTJJI < 10 THEN ( '' || '0' || A.ARTJJI) ELSE '' || A.ARTJJI END) as STKDINVT , B.STKENT , B.STKSORT , A.ARTQTE as STKACT , B.ARTUD from sgsfic.invart$exe A
                    join sgsfic.stk B on A.ARTART = B.ARTCOD and A.ARTMAG = B.MAGCOD 
                    where B.STKEXE = $exe " . $qrmaginsert . $qrartinsert . ")")) {
            return true;
        } else {
            return false;
        }
    }

    private static function del_from_stkp($exe, $mag, $art) {

        $db = Model::getDb2();

        if (!empty($mag)) {
            $qrmagdel = " AND MAGCOD = $mag";
        } else {
            $qrmagdel = "AND MAGCOD in (1,2,4610,4620,4625,4630,4640,4650,4660,4670,4680)";
        }

        if (!empty($art)) {
            $qrartdel = " AND ARTCOD = $art";
        } else {
            $qrartdel = "";
        }

        if ($db->query("delete from stkp where STKEXE = $exe " . $qrmagdel . $qrartdel)) {
            return true;
        } else {
            return false;
        }
    }

    private static function call_stkp_ent($exe, $mag, $art) {

        ini_set('memory_limit', '-1');

        set_time_limit(0);

        $qteent = 0;
        $db = Model::getDb2();

        $etable = array("BEMARCHE", "BE", "BEETR");

        if (!empty($mag)) {
            $qrmag = " AND C.MAGCOD = $mag";
            $mvtmag = " AND A.MAGCOD = $mag";
            $mvtmagbti = " AND A.MAGDEM = $mag";
        } else {
            $mvtmagbti = " AND A.MAGDEM in (1,2,4610,4620,4625,4630,4640,4650,4660,4670,4680)";
            $mvtmag = "";
            $qrmag = " AND C.MAGCOD  in (1,2,4610,4620,4625,4630,4640,4650,4660,4670,4680) ";
        }

        if (!empty($art)) {
            $qrart = " AND C.ARTCOD = $art";
            $mvtart = " AND B.ARTCOD = $art";
        } else {
            $qrart = "";
            $mvtart = "";
        }



        $db->query("update stkp C set STKACT = STKACT + (
                            SELECT  case when SUM(B.BEQTE + B.BEQTSP) IS NULL then 0 else SUM(B.BEQTE + B.BEQTSP) end  AS SUMQTE 
                            FROM BEMARCHEE A  
                            LEFT JOIN BEMARCHED B ON A.BECOD = B.BECOD AND A.BEEXE = B.BEEXE                      
                            WHERE A.BEEXE = C.STKEXE AND B.ARTCOD = C.ARTCOD AND A.MAGCOD = C.MAGCOD AND A.BEDATREC > C.STKDINIT  $mvtmag $mvtart
                            ) where C.stkexe = $exe " . $qrmag . $qrart);


        $db->query("update stkp C set STKACT = STKACT + (
                            SELECT  case when SUM(B.BEQTE + B.BEQTSP) IS NULL then 0 else SUM(B.BEQTE + B.BEQTSP) end  AS SUMQTE 
                            FROM BEE A  
                            LEFT JOIN BED B ON A.BECOD = B.BECOD AND A.BEEXE = B.BEEXE                      
                            WHERE A.BEEXE = C.STKEXE AND B.ARTCOD = C.ARTCOD AND A.MAGCOD = C.MAGCOD AND A.BEDATREC > C.STKDINIT  $mvtmag $mvtart
                            ) where C.stkexe = $exe " . $qrmag . $qrart);


        $db->query("update stkp C set STKACT = STKACT + (
                            SELECT  case when SUM(B.BEQTE + B.BEQTSP) IS NULL then 0 else SUM(B.BEQTE + B.BEQTSP) end  AS SUMQTE 
                            FROM BEETRE A  
                            LEFT JOIN BEETRD B ON A.BECOD = B.BECOD AND A.BEEXE = B.BEEXE                      
                            WHERE A.BEEXE = C.STKEXE AND B.ARTCOD = C.ARTCOD AND A.MAGCOD = C.MAGCOD AND A.BEDATREC > C.STKDINIT  $mvtmag $mvtart
                            ) where C.stkexe = $exe " . $qrmag . $qrart);


        $db->query("update stkp C set STKACT = STKACT + (
                    SELECT  case when SUM(B.BTIQREC) IS NULL then 0 else SUM(B.BTIQREC) end  AS SUMQTE
                    FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
                    WHERE A.BTIEXE = C.STKEXE  AND A.MAGDEM = C.MAGCOD AND B.ARTCOD = C.ARTCOD AND A.BTIDATE > C.STKDINIT AND B.ETBONCOD = 14 $mvtmagbti $mvtart
                    ) where C.stkexe = $exe " . $qrmag . $qrart);
    }

    private static function call_stkp_sort($exe, $mag, $art) {

        ini_set('memory_limit', '-1');

        set_time_limit(0);

        $qteent = 0;
        $db = Model::getDb2();

        $etable = array("BCNE" => "BCND", "BCNEB" => "BCNDB", "BCNHABE" => "BCNHABD");

        if (!empty($mag)) {
            $qrmag = " AND C.MAGCOD = $mag";
            $mvtmag = " AND A.MAGCOD = $mag";
            $mvtmagbti = " AND A.MAGDES = $mag";
        } else {
            $mvtmagbti = " AND A.MAGDES in (1,2,4610,4620,4625,4630,4640,4650,4660,4670,4680) ";
            $mvtmag = "";
            $qrmag = " AND C.MAGCOD in (1,2,4610,4620,4625,4630,4640,4650,4660,4670,4680) ";
        }


        if (!empty($art)) {
            $qrart = " AND C.ARTCOD = $art";
            $mvtart = " AND B.ARTCOD = $art";
        } else {
            $qrart = "";
            $mvtart = "";
        }



        $db->query("update stkp C set STKACT = STKACT - (
                            SELECT  case when SUM(B.BCNQLIV) IS NULL then 0 else SUM(B.BCNQLIV) end  AS SUMQTE 
                            FROM BCNE A  
                            LEFT JOIN BCND B ON A.BCNCOD = B.BCNCOD AND A.BCNEXE = B.BCNEXE                   
                            WHERE A.BCNEXE = C.STKEXE AND B.ARTCOD = C.ARTCOD AND A.MAGCOD = C.MAGCOD AND A.BCNDATE > C.STKDINIT AND A.ETBONCOD = 8 $mvtmag $mvtart
                            ) where C.stkexe = $exe " . $qrmag . $qrart);


        $db->query("update stkp C set STKACT = STKACT - (
                            SELECT  case when SUM(B.BCNQLIV) IS NULL then 0 else SUM(B.BCNQLIV) end  AS SUMQTE 
                            FROM BCNEB A  
                            LEFT JOIN BCNDB B ON A.BCNCOD = B.BCNCOD AND A.BCNEXE = B.BCNEXE                   
                            WHERE A.BCNEXE = C.STKEXE AND B.ARTCOD = C.ARTCOD AND A.MAGCOD = C.MAGCOD AND A.BCNDATE > C.STKDINIT AND A.ETBONCOD = 8 $mvtmag $mvtart
                            ) where C.stkexe = $exe " . $qrmag . $qrart);


        $db->query("update stkp C set STKACT = STKACT - (
                            SELECT  case when SUM(B.BCNQLIV) IS NULL then 0 else SUM(B.BCNQLIV) end  AS SUMQTE 
                            FROM BCNHABE A  
                            LEFT JOIN BCNHABD B ON A.BCNCOD = B.BCNCOD AND A.BCNEXE = B.BCNEXE                   
                            WHERE A.BCNEXE = C.STKEXE AND B.ARTCOD = C.ARTCOD AND A.MAGCOD = C.MAGCOD AND A.BCNDATE > C.STKDINIT AND A.ETBONCOD = 8 $mvtmag $mvtart
                            ) where C.stkexe = $exe " . $qrmag . $qrart);


        $db->query("update stkp C set STKACT = STKACT - (
                    SELECT  case when SUM(B.BTIQREC) IS NULL then 0 else SUM(B.BTIQREC) end  AS SUMQTE
                    FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
                    WHERE A.BTIEXE = C.STKEXE  AND A.MAGDES = C.MAGCOD AND B.ARTCOD = C.ARTCOD AND A.BTIDATE > C.STKDINIT AND B.ETBONCOD = 14 $mvtmagbti $mvtart
                    ) where C.stkexe = $exe " . $qrmag . $qrart);
    }

    public static function get_journ_art($date, $mag, $exe, $grp = "") {

        $mvt = array();

        $mvt = self::get_ent_art_jrn_mag($mvt, $date, $grp, $mag, $exe, $grp);

        $mvt = self::get_sort_art_jrn_mag($mvt, $date, $grp, $mag, $exe, $grp);

        $mvt = self::array_sort($mvt, 'code', SORT_ASC);

        return $mvt;
    }

    public static function get_ent_art_jrn_mag($mvt, $date, $grp, $mag, $exe) {

        $qteent = 0;
        $db = Model::getDb2();

        $etable = array("BEMARCHE", "BE", "BEETR");

        if (!empty($grp)) {
            $grpqr = " AND substr(DIGITS(B.ARTCOD),1,2) = $grp ";
        } else {
            $grpqr = "";
        }

        foreach ($etable as $tbl) {

            $qrqte = $db->query("SELECT DISTINCT A.*
			   FROM " . $tbl . "E A LEFT JOIN " . $tbl . "D B                        
			   ON A.BECOD = B.BECOD AND A.BEEXE = B.BEEXE                      
			   WHERE A.BEDATREC = $date AND A.BEEXE = " . $exe . " $grpqr AND A.MAGCOD = " . $mag);

            $qteres = $qrqte->fetchAll();

            switch ($tbl) {
                case "BEMARCHE" : $mvtname = "BE Marché";
                    break;
                case "BE" : $mvtname = "BE Locale";
                    break;
                case "BEETR" : $mvtname = "BE Etrangé";
                    break;
            }

            foreach ($qteres as $item) {
                array_push($mvt, array(
                    "type" => "ent",
                    "mvt" => $mvtname,
                    "code" => $item["BECOD"],
                    "date" => $item["BEDATREC"]
                ));
            }
        }

        $qrbtqte = $db->query("SELECT DISTINCT A.*
			   FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
			   WHERE A.BTIDATE = $date AND A.BTIEXE = " . $exe . "  AND A.MAGDEM = " . $mag . " $grpqr AND B.ETBONCOD = 14");

        $qteresbti = $qrbtqte->fetchAll();
        foreach ($qteresbti as $item) {
            array_push($mvt, array(
                "type" => "ent",
                "mvt" => "BTI entrant",
                "code" => $item["BTICOD"],
                "date" => $item["BTIDATE"]
            ));
        }

        $qrbrtqte = $db->query("SELECT DISTINCT A.*
			   FROM BTRE A LEFT JOIN BTRD B ON A.BTRCOD = B.BTRCOD AND A.BTREXE = B.BTREXE 
			   WHERE A.BTRDATE = $date AND A.BTREXE = " . $exe . "  AND A.MAGDES = " . $mag . " $grpqr AND A.ETATCOD = 15");

        $qteresbtr = $qrbrtqte->fetchAll();

        foreach ($qteresbtr as $item) {
            array_push($mvt, array(
                "type" => "ent",
                "mvt" => "BR entrant",
                "code" => $item["BTRCOD"],
                "date" => $item["BTRDATE"]
            ));
        }


        return $mvt;
    }

    public static function get_sort_art_jrn_mag($mvt, $date, $grp, $mag, $exe) {

        $qtesort = 0;
        $db = Model::getDb2();

        $etable = array("BCNE" => "BCND", "BCNEB" => "BCNDB", "BCNHABE" => "BCNHABD");


        if (!empty($grp)) {
            $grpqr = " AND substr(DIGITS(B.ARTCOD),1,2) = $grp ";
        } else {
            $grpqr = "";
        }

        foreach ($etable as $key => $tbl) {

            $qrqte = $db->query("SELECT DISTINCT A.*
				FROM " . $key . " A LEFT JOIN " . $tbl . " B ON A.BCNCOD = B.BCNCOD AND A.BCNEXE = B.BCNEXE
				WHERE A.BCNDATE = $date AND A.BCNEXE = " . $exe . " AND A.MAGCOD = " . $mag . " $grpqr AND B.ETBONCOD = 8");

            $qteres = $qrqte->fetchAll();

            switch ($key) {
                case "BCNE" : $mvtname = "BCN Métro";
                    break;
                case "BCNEB" : $mvtname = "BCN Bus";
                    break;
                case "BCNHABE" : $mvtname = "BCN Habillement";
                    break;
            }

            foreach ($qteres as $item) {

                array_push($mvt, array(
                    "type" => "sort",
                    "mvt" => $mvtname,
                    "code" => $item["BCNCOD"],
                    "date" => $item["BCNDATE"]
                ));
            }
        }

        $qrbtqte = $db->query("SELECT DISTINCT A.*
			   FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
			   WHERE A.BTIDATE = $date AND A.BTIEXE = " . $exe . "  AND A.MAGDES = " . $mag . " $grpqr AND B.ETBONCOD = 14");

        $qteresbti = $qrbtqte->fetchAll();

        foreach ($qteresbti as $item) {

            array_push($mvt, array(
                "type" => "sort",
                "mvt" => "BTI sortant",
                "code" => $item["BTICOD"],
                "date" => $item["BTIDATE"]
            ));
        }

        $qrbrtqte = $db->query("SELECT DISTINCT A.* 
			   FROM BTRE A LEFT JOIN BTRD B ON A.BTRCOD = B.BTRCOD AND A.BTREXE = B.BTREXE 
			   WHERE A.BTRDATE = $date AND A.BTREXE = " . $exe . "  AND A.BTRMAG = " . $mag . " $grpqr AND A.ETATCOD = 15");

        $qteresbtr = $qrbrtqte->fetchAll();
        foreach ($qteresbtr as $item) {
            array_push($mvt, array(
                "type" => "sort",
                "mvt" => "BR sortant",
                "code" => $item["BTRCOD"],
                "date" => $item["BTRDATE"]
            ));
        }

        return $mvt;
    }

    public static function array_sort($array, $on, $order = SORT_ASC) {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = (int) $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }



            foreach ($sortable_array as $k => $v) {
                array_push($new_array, $array[$k]);
            }
        }

        return $new_array;
    }

    public static function get_fiche_art($art, $mag, $exe, $invt = true) {

        $mvt = array();

        //die(var_dump($mvt));
        if ($invt) {
            $invt = self::get_inv($exe, $mag, "", $art);

            if (!empty($invt)) {
                array_push($mvt, array(
                    "type" => "invent",
                    "mvt" => "Inventaire",
                    "code" => "-",
                    "qte" => $invt[0]["ARTQTE"],
                    "date" => $invt[0]["DATEINV"]
                ));
            }
        }

        $mvt = self::get_ent_art_mag($mvt, $art, $mag, $exe);
        $mvt = self::get_sort_art_mag($mvt, $art, $mag, $exe);
        $mvt = self::get_redress_art_mag($mvt, $art, $mag, $exe);

        $mvt = self::array_sort($mvt, 'date', SORT_ASC);

        $stkinti = self::init_stk($art, $mag, $exe);
        if (!empty($stkinti)) {
            $stkqte = $stkinti[0]["INIT"];
        } else {
            return false;
        }

        $invt = false;

        $stkp = 0;

        foreach ($mvt as $key => $item) {

            if ($item["type"] == "ent") {
                $stkqte += $item["qte"];
            } else if ($item["type"] == "sort") {
                $stkqte -= $item["qte"];
            } else if ($item["type"] == "rds") {
                $stkqte += $item["qte"];
            } else if ($item["type"] == "invent") {
                $invt = true;
                $stkp = $item["qte"];
            }

            if ($invt == true) {
                if ($item["type"] == "ent") {
                    $stkp += $item["qte"];
                } else if ($item["type"] == "sort") {
                    $stkp -= $item["qte"];
                }
            }

            $mvt[$key]["stk"] = $stkqte;
            $mvt[$key]["stkp"] = $stkp;
        }

        return $mvt;
    }

    public static function get_ent_art_mag($mvt, $art, $mag, $exe) {

        $qteent = 0;
        $db = Model::getDb2();

        $etable = array("BEMARCHE", "BE", "BEETR");

        foreach ($etable as $tbl) {

            $qrqte = $db->query("SELECT A.BECOD , (B.BEQTE + B.BEQTSP) as qte , A.BEDATREC
			   FROM " . $tbl . "E A LEFT JOIN " . $tbl . "D B                        
			   ON A.BECOD = B.BECOD AND A.BEEXE = B.BEEXE                      
			   WHERE A.BEEXE = " . $exe . " AND B.ARTCOD = " . $art . " AND A.MAGCOD = " . $mag);

            $qteres = $qrqte->fetchAll();

            switch ($tbl) {
                case "BEMARCHE" : $mvtname = "BE Marché";
                    break;
                case "BE" : $mvtname = "BE Locale";
                    break;
                case "BEETR" : $mvtname = "BE Etrangé";
                    break;
            }

            foreach ($qteres as $item) {
                array_push($mvt, array(
                    "type" => "ent",
                    "mvt" => $mvtname,
                    "code" => $item["BECOD"],
                    "qte" => $item["QTE"],
                    "date" => $item["BEDATREC"]
                ));
            }
        }

        $qrbtqte = $db->query("SELECT A.BTICOD , B.BTIQLIV , A.BTIDATE , A.MAGDEM ,A.MAGDES  
			   FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
			   WHERE A.BTIEXE = " . $exe . "  AND A.MAGDEM = " . $mag . " AND B.ARTCOD = " . $art . " AND B.ETBONCOD = 14");

        $qteresbti = $qrbtqte->fetchAll();
        foreach ($qteresbti as $item) {
            array_push($mvt, array(
                "type" => "ent",
                "mvt" => "BTI entrant",
                "magdem" => $item["MAGDEM"],
                "magdes" => $item["MAGDES"],
                "code" => $item["BTICOD"],
                "qte" => $item["BTIQLIV"],
                "date" => $item["BTIDATE"]
            ));
        }

        $qrbrtqte = $db->query("SELECT A.BTRCOD , B.QTERREC , A.BTRDATE 
			   FROM BTRE A LEFT JOIN BTRD B ON A.BTRCOD = B.BTRCOD AND A.BTREXE = B.BTREXE 
			   WHERE A.BTREXE = " . $exe . "  AND A.MAGDES = " . $mag . " AND B.ARTCOD = " . $art . " AND A.ETATCOD = 15");

        $qteresbtr = $qrbrtqte->fetchAll();
        foreach ($qteresbtr as $item) {
            array_push($mvt, array(
                "type" => "ent",
                "mvt" => "BR entrant",
                "code" => $item["BTRCOD"],
                "qte" => $item["QTERREC"],
                "date" => $item["BTRDATE"]
            ));
        }


        return $mvt;
    }

    public static function get_redress_art_mag($mvt, $art, $mag, $exe) {

        $db = Model::getDb2();

        $redress = $db->select()->from(array("A" => "RDSSTK"))
                        ->where("MAGCOD = ?", $mag)
                        ->where("RDSEXE = ?", $exe)
                        ->where("ARTCOD = ?", $art)
                        ->query()->fetchAll();

        foreach ($redress as $rds) {
            array_push($mvt, array(
                "type" => "rds",
                "mvt" => "Redressement",
                "code" => $rds["RDSCOD"],
                "qte" => $rds["QTERDS"],
                "date" => $rds["RDSDATE"]
            ));
        }

        return $mvt;
    }

    public static function get_sort_art_mag($mvt, $art, $mag, $exe) {

        $qtesort = 0;
        $db = Model::getDb2();

        $etable = array("BCNE" => "BCND", "BCNEB" => "BCNDB", "BCNHABE" => "BCNHABD");

        foreach ($etable as $key => $tbl) {

            $qrqte = $db->query("SELECT A.BCNCOD , B.BCNQLIV , A.BCNDATE 
				FROM " . $key . " A LEFT JOIN " . $tbl . " B ON A.BCNCOD = B.BCNCOD AND A.BCNEXE = B.BCNEXE
				WHERE A.BCNEXE = " . $exe . " AND A.MAGCOD = " . $mag . " AND B.ARTCOD = " . $art . " AND B.ETBONCOD = 8");

            $qteres = $qrqte->fetchAll();

            switch ($key) {
                case "BCNE" : $mvtname = "BCN Métro";
                    break;
                case "BCNEB" : $mvtname = "BCN Bus";
                    break;
                case "BCNHABE" : $mvtname = "BCN Habillement";
                    break;
            }

            foreach ($qteres as $item) {

                array_push($mvt, array(
                    "type" => "sort",
                    "mvt" => $mvtname,
                    "code" => $item["BCNCOD"],
                    "qte" => $item["BCNQLIV"],
                    "date" => $item["BCNDATE"]
                ));
            }
        }

        $qrbtqte = $db->query("SELECT A.BTICOD , B.BTIQLIV , A.BTIDATE , A.MAGDEM ,A.MAGDES
			   FROM BTIE A LEFT JOIN BTID B ON A.BTICOD = B.BTICOD AND A.BTIEXE = B.BTIEXE 
			   WHERE A.BTIEXE = " . $exe . "  AND A.MAGDES = " . $mag . " AND B.ARTCOD = " . $art . " AND B.ETBONCOD = 14");

        $qteresbti = $qrbtqte->fetchAll();

        foreach ($qteresbti as $item) {

            array_push($mvt, array(
                "type" => "sort",
                "mvt" => "BTI sortant",
                "magdem" => $item["MAGDEM"],
                "magdes" => $item["MAGDES"],
                "code" => $item["BTICOD"],
                "qte" => $item["BTIQLIV"],
                "date" => $item["BTIDATE"]
            ));
        }

        $qrbrtqte = $db->query("SELECT A.BTRCOD , B.QTERREC , A.BTRDATE 
			   FROM BTRE A LEFT JOIN BTRD B ON A.BTRCOD = B.BTRCOD AND A.BTREXE = B.BTREXE 
			   WHERE A.BTREXE = " . $exe . "  AND A.BTRMAG = " . $mag . " AND B.ARTCOD = " . $art . " AND A.ETATCOD = 15");

        $qteresbtr = $qrbrtqte->fetchAll();
        foreach ($qteresbtr as $item) {
            array_push($mvt, array(
                "type" => "sort",
                "mvt" => "BR sortant",
                "code" => $item["BTRCOD"],
                "qte" => $item["QTERREC"],
                "date" => $item["BTRDATE"]
            ));
        }

        return $mvt;
    }

}

?>
