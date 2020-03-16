SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `droit` (
  `idDROIT` int(11) NOT NULL,
  `DROITnom` varchar(45) DEFAULT NULL,
  `DROITdcr` date DEFAULT NULL,
  `DROITmark` varchar(45) DEFAULT NULL,
  `idGRBDROIT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `droit` (`idDROIT`, `DROITnom`, `DROITdcr`, `DROITmark`, `idGRBDROIT`) VALUES
(99, 'Page d\'accueil', '2015-11-24', NULL, 25),
(102, 'Consultation', '2017-10-03', '', 27),
(103, 'Création', '2017-10-03', 'consultdemapro', 27),
(104, 'Annulation', '2017-10-03', 'annulationdemapro', 27);

CREATE TABLE `droit_has_modul` (
  `idDROIT` int(11) NOT NULL,
  `idMODUL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `droit_has_modul` (`idDROIT`, `idMODUL`) VALUES
(99, 433),
(99, 434),
(99, 435),
(99, 436),
(99, 437),
(99, 438),
(99, 439),
(99, 440),
(99, 441),
(99, 442),
(99, 443),
(99, 444),
(99, 462),
(99, 463),
(99, 464),
(99, 533),
(99, 534),
(99, 535),
(99, 536),
(99, 537),
(99, 538),
(100, 433),
(100, 434),
(100, 435),
(100, 436),
(100, 437),
(100, 438),
(100, 439),
(100, 440),
(100, 441),
(100, 442),
(100, 443),
(100, 444),
(100, 455),
(100, 456),
(100, 457),
(100, 458),
(100, 459),
(100, 460),
(100, 461),
(100, 462),
(100, 463),
(100, 464),
(100, 465),
(100, 466),
(100, 467),
(100, 468),
(100, 469),
(100, 470),
(100, 471),
(100, 472),
(100, 473),
(100, 476),
(100, 477),
(100, 478),
(100, 479),
(100, 480),
(100, 481),
(100, 482),
(100, 483),
(100, 484),
(100, 485),
(100, 486),
(100, 487),
(100, 488),
(100, 489),
(100, 490),
(100, 491),
(100, 492),
(100, 493),
(100, 494),
(100, 495),
(100, 496),
(100, 497),
(100, 498),
(100, 499),
(100, 500),
(100, 503),
(100, 504),
(100, 505),
(100, 506),
(100, 507),
(100, 508),
(100, 509),
(100, 513),
(100, 514),
(100, 515),
(100, 516),
(100, 518),
(100, 519),
(100, 520),
(100, 521),
(100, 522),
(100, 523),
(100, 524),
(102, 458),
(102, 486),
(102, 487),
(102, 491),
(103, 488),
(103, 505),
(104, 489),
(104, 490),
(105, 461),
(105, 492),
(105, 494),
(105, 495),
(105, 497),
(105, 499),
(105, 500),
(106, 498),
(107, 493),
(107, 496),
(110, 518),
(110, 519),
(110, 524),
(111, 520),
(111, 522),
(112, 523),
(113, 456),
(113, 466),
(113, 470),
(113, 503),
(114, 467),
(115, 469),
(115, 526),
(116, 525),
(116, 527),
(118, 468),
(119, 460),
(119, 471),
(119, 476),
(119, 478),
(119, 479),
(120, 472),
(121, 477),
(122, 457),
(122, 480),
(122, 481),
(122, 485),
(122, 532),
(123, 482),
(123, 504),
(124, 483),
(124, 484),
(125, 506),
(125, 516),
(126, 507),
(126, 513),
(127, 508),
(127, 515),
(128, 509),
(128, 514),
(129, 528),
(129, 529),
(129, 530),
(129, 531),
(129, 532),
(130, 521),
(131, 541),
(131, 542),
(131, 544),
(132, 543),
(132, 545),
(132, 548),
(133, 546),
(134, 547),
(135, 549);

CREATE TABLE `ent` (
  `idENT` int(11) NOT NULL,
  `ENTcod` int(11) DEFAULT NULL,
  `ENTnom` varchar(45) DEFAULT NULL,
  `SRVidsup` int(11) DEFAULT NULL,
  `idHERA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ent` (`idENT`, `ENTcod`, `ENTnom`, `SRVidsup`, `idHERA`) VALUES
(1, 1000, 'Administrateur', NULL, NULL);

CREATE TABLE `etat` (
  `idETAT` int(11) NOT NULL,
  `ETATnom` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `etat` (`idETAT`, `ETATnom`) VALUES
(21, 'Module dÃƒÂ©sactivÃƒÂ©'),
(22, 'Module activÃƒÂ©'),
(31, 'profil activÃƒÂ©'),
(32, 'profil dÃƒÂ©sactivÃƒÂ©');

CREATE TABLE `etatgen` (
  `idETAT` int(11) NOT NULL,
  `ETAdesc` varchar(50) NOT NULL,
  `ETAparam` text NOT NULL,
  `ETAdatecr` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `exe` (
  `exe` int(11) NOT NULL,
  `idETAT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `exe` (`exe`, `idETAT`) VALUES
(2017, 52),
(2018, 51);

CREATE TABLE `grbdroit` (
  `idGRBDROIT` int(11) NOT NULL,
  `GRBDROITnom` varchar(45) DEFAULT NULL,
  `GRBDROITdcr` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `grbdroit` (`idGRBDROIT`, `GRBDROITnom`, `GRBDROITdcr`) VALUES
(25, 'Accueil', '2015-11-24');

CREATE TABLE `hera` (
  `idHERA` int(11) NOT NULL,
  `HERAnom` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mag` (
  `MAGCOD` int(11) NOT NULL,
  `MAGLIB` varchar(45) DEFAULT NULL,
  `MAGDCR` date DEFAULT NULL,
  `OPECOD` int(11) DEFAULT NULL,
  `OPEEXE` int(11) DEFAULT NULL,
  `TMAGCOD` int(11) DEFAULT NULL,
  `ETATCOD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `mag` (`MAGCOD`, `MAGLIB`, `MAGDCR`, `OPECOD`, `OPEEXE`, `TMAGCOD`, `ETATCOD`) VALUES
(1, 'DEPO A', '2017-05-27', 1, 1, 1, 1),
(2, 'DEPO B', '2017-11-27', NULL, NULL, NULL, 1);

CREATE TABLE `menu` (
  `idMENU` int(11) NOT NULL,
  `MENUnom` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `MENUparent` int(11) DEFAULT NULL,
  `MENUmod` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `MENUicon` varchar(100) CHARACTER SET utf8 NOT NULL,
  `MENUmnseq` int(11) DEFAULT NULL,
  `MENUdcr` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `modul` (
  `idMODUL` int(11) NOT NULL,
  `MODULnom` varchar(45) DEFAULT NULL,
  `MODULpath` varchar(45) DEFAULT NULL,
  `MODULtask` varchar(45) DEFAULT NULL,
  `MODULmod` varchar(45) DEFAULT NULL,
  `MODULmnseq` varchar(45) DEFAULT NULL,
  `MODULdcr` date DEFAULT NULL,
  `MODULtype` varchar(45) DEFAULT NULL,
  `MODULcat` varchar(1) NOT NULL,
  `MODULicon` varchar(100) NOT NULL,
  `idMENU` int(11) NOT NULL,
  `MODULupdate` int(11) NOT NULL,
  `idETAT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `modul` (`idMODUL`, `MODULnom`, `MODULpath`, `MODULtask`, `MODULmod`, `MODULmnseq`, `MODULdcr`, `MODULtype`, `MODULcat`, `MODULicon`, `idMENU`, `MODULupdate`, `idETAT`) VALUES
(348, 'crudmaker-index', 'crudmaker', 'index', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(349, 'crudmaker-asyn_get_shema_db2', 'crudmaker', 'asyn_get_shema_db2', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(350, 'crudmaker-asyn_get_shema_mysql', 'crudmaker', 'asyn_get_shema_mysql', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(351, 'crudmaker-asyn_compile', 'crudmaker', 'asyn_compile', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(352, 'login-index', 'login', 'index', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(353, 'etatgensys-index', 'etatgensys', 'index', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(354, 'etatgensys-asyn_get_config_etat', 'etatgensys', 'asyn_get_config_etat', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(355, 'etatgensys-asyn_get_shema_db2', 'etatgensys', 'asyn_get_shema_db2', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(356, 'etatgensys-asyn_get_shema_mysql', 'etatgensys', 'asyn_get_shema_mysql', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(357, 'etatgensys-asyn_get_base_mysql', 'etatgensys', 'asyn_get_base_mysql', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(358, 'etatgensys-asyn_get_table_mysql', 'etatgensys', 'asyn_get_table_mysql', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(359, 'etatgensys-asyn_get_table_column_mysql', 'etatgensys', 'asyn_get_table_column_mysql', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(360, 'etatgensys-asyn_compile', 'etatgensys', 'asyn_compile', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(361, 'gestion-index', 'gestion', 'index', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(362, 'gestion-asyn_get_supser', 'gestion', 'asyn_get_supser', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(363, 'gestion-asyn_get_srvlist', 'gestion', 'asyn_get_srvlist', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(364, 'gestion-asyn_aj_prf', 'gestion', 'asyn_aj_prf', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(365, 'gestion-asyn_get_prf', 'gestion', 'asyn_get_prf', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(366, 'gestion-asyn_sup_prf', 'gestion', 'asyn_sup_prf', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(367, 'gestion-asyn_aj_util', 'gestion', 'asyn_aj_util', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(368, 'gestion-asyn_get_allutil', 'gestion', 'asyn_get_allutil', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(369, 'gestion-asyn_get_util', 'gestion', 'asyn_get_util', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(370, 'gestion-asyn_ck_login', 'gestion', 'asyn_ck_login', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(371, 'gestion-asyn_modif_util', 'gestion', 'asyn_modif_util', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(372, 'gestion-asyn_sup_util', 'gestion', 'asyn_sup_util', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(373, 'gestion-asyn_autocomplete_user', 'gestion', 'asyn_autocomplete_user', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(374, 'gestion-asyn_serch_user', 'gestion', 'asyn_serch_user', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(375, 'gestion-asyn_ajout_menu', 'gestion', 'asyn_ajout_menu', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(376, 'gestion-asyn_get_allvu', 'gestion', 'asyn_get_allvu', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(377, 'gestion-asyn_get_menulist', 'gestion', 'asyn_get_menulist', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(378, 'gestion-asyn_menu_remove', 'gestion', 'asyn_menu_remove', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(379, 'gestion-asyn_module_cat', 'gestion', 'asyn_module_cat', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(380, 'gestion-asyn_module_renome', 'gestion', 'asyn_module_renome', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(381, 'gestion-asyn_modif_menu', 'gestion', 'asyn_modif_menu', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(382, 'gestion-asyn_modif_menu_menu', 'gestion', 'asyn_modif_menu_menu', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(383, 'gestion-asyn_modif_modul', 'gestion', 'asyn_modif_modul', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(384, 'gestion-asyn_ajout_menu_icone', 'gestion', 'asyn_ajout_menu_icone', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(385, 'gestion-asyn_modif_menu_position', 'gestion', 'asyn_modif_menu_position', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(386, 'gestion-_sub_menu_position', 'gestion', '_sub_menu_position', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(387, 'gestion-asyn_get_droit_list', 'gestion', 'asyn_get_droit_list', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(388, 'gestion-asyn_aj_droit', 'gestion', 'asyn_aj_droit', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(389, 'gestion-asyn_sup_droit', 'gestion', 'asyn_sup_droit', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(390, 'gestion-asyn_get_grbdoit_list', 'gestion', 'asyn_get_grbdoit_list', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(391, 'gestion-asyn_aj_grbdroit', 'gestion', 'asyn_aj_grbdroit', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(392, 'gestion-asyn_sup_grbdroit', 'gestion', 'asyn_sup_grbdroit', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(393, 'gestion-asyn_get_modul_list', 'gestion', 'asyn_get_modul_list', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(394, 'gestion-asyn_droit_moduls', 'gestion', 'asyn_droit_moduls', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(395, 'gestion-asyn_get_all_droits', 'gestion', 'asyn_get_all_droits', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(396, 'gestion-asyn_ajout_marqueur', 'gestion', 'asyn_ajout_marqueur', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(397, 'gestion-asyn_prf_droit', 'gestion', 'asyn_prf_droit', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(398, 'gestion-asyn_get_all_mag', 'gestion', 'asyn_get_all_mag', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(399, 'gestion-asyn_mag_prf', 'gestion', 'asyn_mag_prf', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(400, 'gestion-asyn_get_all_exe', 'gestion', 'asyn_get_all_exe', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(401, 'gestion-asyn_exe_prf', 'gestion', 'asyn_exe_prf', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(402, 'gestion-asyn_get_hist_action', 'gestion', 'asyn_get_hist_action', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(403, 'gestion-asyn_get_hist_action_ftr', 'gestion', 'asyn_get_hist_action_ftr', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(404, 'EditMenu-index', 'EditMenu', 'index', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(405, 'EditMenu-edit', 'EditMenu', 'edit', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(406, 'EditMenu-asyn_get_access_groupe', 'EditMenu', 'asyn_get_access_groupe', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(407, 'EditMenu-asyn_delete_item_access_list', 'EditMenu', 'asyn_delete_item_access_list', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(408, 'EditMenu-asyn_menu_affect', 'EditMenu', 'asyn_menu_affect', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(409, 'EditMenu-asyn_get_modules', 'EditMenu', 'asyn_get_modules', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(410, 'EditMenu-asyn_get_groupes', 'EditMenu', 'asyn_get_groupes', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(411, 'EditMenu-asyn_create_access_groupe_item', 'EditMenu', 'asyn_create_access_groupe_item', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(412, 'EditMenu-asyn_change_module_name', 'EditMenu', 'asyn_change_module_name', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(413, 'EditMenu-asyn_change_groupe_name', 'EditMenu', 'asyn_change_groupe_name', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(414, 'EditMenu-asyn_edit_access', 'EditMenu', 'asyn_edit_access', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(415, 'EditMenu-asyn_delete_menu_affectation', 'EditMenu', 'asyn_delete_menu_affectation', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(416, 'EditMenu-asyn_get_groupe_params', 'EditMenu', 'asyn_get_groupe_params', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(417, 'main-index', 'main', 'index', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(418, 'main-asyn_ajout_module', 'main', 'asyn_ajout_module', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(419, 'main-asyn_get_prim_function', 'main', 'asyn_get_prim_function', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(420, 'main-asyn_deconection', 'main', 'asyn_deconection', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(421, 'main-asyn_create_exception', 'main', 'asyn_create_exception', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(422, 'main-asyn_get_msg', 'main', 'asyn_get_msg', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(423, 'main-asyn_get_exception', 'main', 'asyn_get_exception', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(424, 'main-asyn_get_all_right', 'main', 'asyn_get_all_right', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(425, 'updateMod-index', 'updateMod', 'index', 'ad', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(426, 'updateMod-asyn_update_ad', 'updateMod', 'asyn_update_ad', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(427, 'updateMod-asyn_update_front', 'updateMod', 'asyn_update_front', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(428, 'updateMod-asyn_reset_update_state', 'updateMod', 'asyn_reset_update_state', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(429, 'updateMod-asyn_get_com_arborecense', 'updateMod', 'asyn_get_com_arborecense', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(430, 'updateMod-asyn_get_net_modul', 'updateMod', 'asyn_get_net_modul', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(431, 'updateMod-asyn_supp_perim_modul', 'updateMod', 'asyn_supp_perim_modul', 'ad', NULL, '2015-11-24', 'asyn', '', '', 0, 1, 21),
(432, 'Page de connection', 'login', 'index', 'front', NULL, '2015-11-24', 'vu', '', '', 0, 1, 21),
(433, 'Page d\'accueil', 'main', 'index', 'front', NULL, '2015-11-24', 'vu', 'P', '', 0, 1, 21),
(434, 'Deconnection', 'main', 'asyn_deconection', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(435, 'RecupÃƒÂ©rer les paramÃƒÂ©tre de l\'utilisateu', 'main', 'asyn_get_profil_det', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(436, 'RecupÃƒÂ©rer le nom de l\'utilisateur', 'main', 'asyn_get_utilog', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(437, 'Sauvegarder les paramÃƒÂ©tre utilisateur', 'main', 'asyn_sauv_profil', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(438, 'Generer une exception', 'main', 'asyn_create_exception', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(439, 'RecupÃƒÂ©rer message', 'main', 'asyn_get_msg', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(440, 'RecupÃƒÂ©rer exception', 'main', 'asyn_get_exception', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(441, 'Changer Exercice', 'main', 'asyn_change_exe', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(442, 'Changer un magasin', 'main', 'asyn_change_mag', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(443, 'Exercice courant', 'main', 'asyn_get_curent_exe', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(444, 'Lire tout les droits de l\'utilisateur', 'main', 'asyn_get_all_right', 'front', NULL, '2015-11-24', 'asyn', 'P', '', 0, 1, 21),
(445, 'etatgen-index', 'etatgen', 'index', 'ad', NULL, '2017-05-27', 'vu', '', '', 0, 1, 21),
(446, 'etatgen-asyn_get_shema_db2', 'etatgen', 'asyn_get_shema_db2', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(447, 'etatgen-asyn_get_shema_mysql', 'etatgen', 'asyn_get_shema_mysql', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(448, 'etatgen-asyn_get_base_mysql', 'etatgen', 'asyn_get_base_mysql', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(449, 'etatgen-asyn_get_table_mysql', 'etatgen', 'asyn_get_table_mysql', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(450, 'etatgen-asyn_get_table_column_mysql', 'etatgen', 'asyn_get_table_column_mysql', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(451, 'etatgen-asyn_compile', 'etatgen', 'asyn_compile', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(452, 'crudmaker-asyn_get_base_mysql', 'crudmaker', 'asyn_get_base_mysql', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(453, 'crudmaker-asyn_get_table_mysql', 'crudmaker', 'asyn_get_table_mysql', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(454, 'crudmaker-asyn_get_table_column_mysql', 'crudmaker', 'asyn_get_table_column_mysql', 'ad', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(462, 'main-asyn_getautoselect', 'main', 'asyn_getautoselect', 'front', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(463, 'main-asyn_add_entitie', 'main', 'asyn_add_entitie', 'front', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(464, 'main-asyn_getautoselectsearch', 'main', 'asyn_getautoselectsearch', 'front', NULL, '2017-05-27', 'asyn', '', '', 0, 1, 21),
(538, 'main-asyn_isadmin', 'main', 'asyn_isadmin', 'front', NULL, '2017-10-04', 'asyn', '', '', 0, 1, 21),
(539, 'gestion-asyn_get_ouvexe', 'gestion', 'asyn_get_ouvexe', 'ad', NULL, '2018-01-17', 'asyn', '', '', 0, 1, 21),
(540, 'main-asyn_isadmin', 'main', 'asyn_isadmin', 'ad', NULL, '2018-01-17', 'asyn', '', '', 0, 1, 21),
(550, 'main-asyn_get_mag', 'main', 'asyn_get_mag', 'front', NULL, '2018-01-19', 'asyn', '', '', 0, 1, 21);

CREATE TABLE `oper` (
  `idOPER` int(20) NOT NULL,
  `idMODUL` int(20) NOT NULL,
  `actionOPER` varchar(100) NOT NULL,
  `descriptOPER` varchar(150) NOT NULL,
  `dateOPER` datetime NOT NULL,
  `catOPER` varchar(1) NOT NULL,
  `idPRF` int(20) NOT NULL,
  `IDUTIL` int(20) NOT NULL,
  `UTINOM` varchar(50) NOT NULL,
  `UTIPNOM` varchar(50) NOT NULL,
  `paramOPER` text NOT NULL,
  `ipOPER` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


 

CREATE TABLE `prf` (
  `idPRF` int(11) NOT NULL,
  `PRFnom` varchar(45) DEFAULT NULL,
  `PRFtype` int(11) DEFAULT NULL,
  `PRFdcr` date DEFAULT NULL,
  `idENT` int(11) NOT NULL,
  `PRFetat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `prf` (`idPRF`, `PRFnom`, `PRFtype`, `PRFdcr`, `idENT`, `PRFetat`) VALUES
(0, 'Supper administrateur', 2, '2015-11-24', 1, 31),
(1, 'Utilisateur', 2, '2017-10-03', 1, 31);

CREATE TABLE `prf_has_droit` (
  `idPRF` int(11) NOT NULL,
  `idDROIT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `prf_has_droit` (`idPRF`, `idDROIT`) VALUES
(0, 99),
(0, 102),
(0, 103),
(0, 104),
(0, 105),
(0, 106),
(0, 107),
(0, 108),
(0, 109),
(0, 110),
(0, 111),
(0, 112),
(0, 113),
(0, 114),
(0, 115),
(0, 116),
(0, 118),
(0, 119),
(0, 120),
(0, 121),
(0, 122),
(0, 123),
(0, 124),
(0, 125),
(0, 126),
(0, 127),
(0, 128),
(0, 129),
(0, 130),
(0, 131),
(0, 132),
(0, 133),
(0, 135),
(1, 99),
(1, 102),
(1, 103),
(1, 104),
(1, 105),
(1, 106),
(1, 107),
(1, 108),
(1, 109),
(1, 110),
(1, 111),
(1, 112),
(1, 113),
(1, 114),
(1, 115),
(1, 116),
(1, 118),
(1, 119),
(1, 120),
(1, 121),
(1, 122),
(1, 123),
(1, 124),
(1, 125),
(1, 126),
(1, 127),
(1, 128),
(1, 129),
(1, 130);

CREATE TABLE `prf_has_exe` (
  `id` int(11) NOT NULL,
  `idPRF` int(11) NOT NULL,
  `exe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `prf_has_exe` (`id`, `idPRF`, `exe`) VALUES
(2, 0, 2017);

CREATE TABLE `prf_has_mag` (
  `idPRF` int(11) NOT NULL,
  `MAGCOD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `prf_has_mag` (`idPRF`, `MAGCOD`) VALUES
(0, 1),
(0, 2),
(1, 1);

CREATE TABLE `util` (
  `IDUTIL` int(11) NOT NULL,
  `UTILOG` varchar(45) DEFAULT NULL,
  `UTIPWD` varchar(45) DEFAULT NULL,
  `UTINOM` varchar(45) DEFAULT NULL,
  `UTIPNOM` varchar(45) DEFAULT NULL,
  `UTIDCR` date DEFAULT NULL,
  `UTImat` decimal(10,0) DEFAULT NULL,
  `OPECOD` decimal(10,0) DEFAULT NULL,
  `OPEEXE` decimal(10,0) DEFAULT NULL,
  `PROFCOD` decimal(10,0) DEFAULT NULL,
  `ETATCOD` decimal(10,0) DEFAULT NULL,
  `idPRF` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 

ALTER TABLE `droit`
  ADD PRIMARY KEY (`idDROIT`),
  ADD KEY `fk_DROIT_GRBDROIT1_idx` (`idGRBDROIT`);

ALTER TABLE `droit_has_modul`
  ADD PRIMARY KEY (`idDROIT`,`idMODUL`),
  ADD KEY `fk_DROIT_has_MODUL_MODUL1_idx` (`idMODUL`),
  ADD KEY `fk_DROIT_has_MODUL_DROIT1_idx` (`idDROIT`);

ALTER TABLE `ent`
  ADD PRIMARY KEY (`idENT`);

ALTER TABLE `etat`
  ADD PRIMARY KEY (`idETAT`);

ALTER TABLE `etatgen`
  ADD PRIMARY KEY (`idETAT`);

ALTER TABLE `exe`
  ADD PRIMARY KEY (`exe`);

ALTER TABLE `grbdroit`
  ADD PRIMARY KEY (`idGRBDROIT`);

ALTER TABLE `hera`
  ADD PRIMARY KEY (`idHERA`);

ALTER TABLE `mag`
  ADD PRIMARY KEY (`MAGCOD`),
  ADD KEY `fk_MAG_ETAT1_idx` (`ETATCOD`);

ALTER TABLE `menu`
  ADD PRIMARY KEY (`idMENU`);

ALTER TABLE `modul`
  ADD PRIMARY KEY (`idMODUL`),
  ADD KEY `fk_MODUL_MENU1_idx` (`idMENU`),
  ADD KEY `fk_MODUL_ETAT1_idx` (`idETAT`);

ALTER TABLE `oper`
  ADD PRIMARY KEY (`idOPER`);

ALTER TABLE `prf`
  ADD PRIMARY KEY (`idPRF`),
  ADD KEY `fk_PRF_ENT1_idx` (`idENT`),
  ADD KEY `fk_PRF_ETAT1_idx` (`PRFetat`);

ALTER TABLE `prf_has_droit`
  ADD PRIMARY KEY (`idPRF`,`idDROIT`),
  ADD KEY `fk_PRF_has_DROIT_DROIT1_idx` (`idDROIT`),
  ADD KEY `fk_PRF_has_DROIT_PRF1_idx` (`idPRF`);

ALTER TABLE `prf_has_exe`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `prf_has_mag`
  ADD PRIMARY KEY (`idPRF`,`MAGCOD`),
  ADD KEY `fk_PRF_has_MAG_MAG1_idx` (`MAGCOD`),
  ADD KEY `fk_PRF_has_MAG_PRF1_idx` (`idPRF`);

ALTER TABLE `util`
  ADD PRIMARY KEY (`IDUTIL`),
  ADD KEY `fk_UTIL_PRF1_idx` (`idPRF`);


ALTER TABLE `droit`
  MODIFY `idDROIT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

ALTER TABLE `droit_has_modul`
  MODIFY `idDROIT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

ALTER TABLE `ent`
  MODIFY `idENT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `etatgen`
  MODIFY `idETAT` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `grbdroit`
  MODIFY `idGRBDROIT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

ALTER TABLE `hera`
  MODIFY `idHERA` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `menu`
  MODIFY `idMENU` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `modul`
  MODIFY `idMODUL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=551;

ALTER TABLE `oper`
  MODIFY `idOPER` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18915;

ALTER TABLE `prf`
  MODIFY `idPRF` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `prf_has_exe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `util`
  MODIFY `IDUTIL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
