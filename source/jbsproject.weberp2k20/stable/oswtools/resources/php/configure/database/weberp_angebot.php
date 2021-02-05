<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

/*
 * init
 */
$__datatable_table='weberp_angebot';
$__datatable_create=false;
$__datatable_do=false;

/*
 * check version of table
 */
$QreadData=osW_Tool_Database::getInstance()->query('SHOW TABLE STATUS LIKE :table:');
$QreadData->bindValue(':table:', $this->data['values_json']['database_prefix'].$__datatable_table);
$QreadData->execute();
if ($QreadData->numberOfRows()==1) {
	$QreadData->next();
	$avb_tbl=$QreadData->result['Comment'];
} else {
	$avb_tbl='0.0';
}
$avb_tbl=explode('.', $avb_tbl);
if (count($avb_tbl)==1) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=0;
} elseif (count($avb_tbl)==2) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=intval($avb_tbl[1]);
} else {
	$av_tbl=0;
	$ab_tbl=0;
}

/*
 * create table
 */
if (($av_tbl==0)&&($ab_tbl==0)) {
	$av_tbl=1;
	$ab_tbl=0;
	$__datatable_create=true;

	$QwriteData=osW_Tool_Database::getInstance()->query('
CREATE TABLE :table: (
	angebot_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	angebot_nr int(11) unsigned NOT NULL DEFAULT 0,
	kunde_id int(11) unsigned NOT NULL DEFAULT 0,
	angebot_kunde_nr int(11) NOT NULL DEFAULT 0,
	angebot_kunde_gewerblich tinyint(1) unsigned NOT NULL DEFAULT 0,
	angebot_kunde_firma_anrede varchar(32) NOT NULL DEFAULT \'\',
	angebot_kunde_firma varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_firma2 varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_rechungsasp tinyint(1) unsigned NOT NULL DEFAULT 0,
	angebot_kunde_anrede varchar(32) NOT NULL DEFAULT \'\',
	angebot_kunde_titel varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_vorname varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_nachname varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_email varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_strasse varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_land varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_plz varchar(16) NOT NULL DEFAULT \'\',
	angebot_kunde_ort varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_telefon varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_fax varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_mobil varchar(128) NOT NULL DEFAULT \'\',
	angebot_kunde_homepage varchar(128) NOT NULL DEFAULT \'\',
	angebot_datum varchar(8) NOT NULL DEFAULT \'\',
	angebot_leistung_von varchar(8) NOT NULL DEFAULT \'\',
	angebot_leistung_bis varchar(8) NOT NULL DEFAULT \'\',
	angebot_storniert tinyint(1) unsigned NOT NULL DEFAULT 0,
	angebot_erledigt tinyint(1) unsigned NOT NULL DEFAULT 0,
	angebot_gesendet tinyint(1) unsigned NOT NULL DEFAULT 0,
	angebot_gesamt_brutto float NOT NULL DEFAULT 0,
	angebot_gesamt_netto float NOT NULL DEFAULT 0,
	angebot_gesamt_mwst float NOT NULL DEFAULT 0,
	angebot_create_time int(11) unsigned NOT NULL DEFAULT 0,
	angebot_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	angebot_update_time int(11) unsigned NOT NULL DEFAULT 0,
	angebot_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (angebot_id),
	KEY mandant_id (mandant_id),
	KEY angebot_nr (angebot_nr),
	KEY kunde_id (kunde_id),
	KEY angebot_kunde_nr (angebot_kunde_nr),
	KEY angebot_storniert (angebot_storniert),
	KEY angebot_erledigt (angebot_erledigt),
	KEY angebot_gesendet (angebot_gesendet),
	KEY angebot_gesamt_brutto (angebot_gesamt_brutto),
	KEY angebot_gesamt_netto (angebot_gesamt_netto),
	KEY angebot_gesamt_mwst (angebot_gesamt_mwst),
	KEY angebot_create_time (angebot_create_time),
	KEY angebot_create_user_id (angebot_create_user_id),
	KEY angebot_update_time (angebot_update_time),
	KEY angebot_update_user_id (angebot_update_user_id)
) ENGINE='.$this->data['values_json']['database_engine'].' DEFAULT CHARSET='.$this->data['values_json']['database_character'].' COMMENT=:version:;
');
	$QwriteData->bindTable(':table:', $__datatable_table);
	$QwriteData->bindValue(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->query_handler===false) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->error;
	}
}

/*
 * update table
 */
/*
if (($av_tbl<=1)&&($ab_tbl<1)) {
	$av_tbl=1;
	$ab_tbl=1;
	$__datatable_do=true;

	... code ...
}
*/

if ($__datatable_do===true) {
	$QwriteData=osW_Tool_Database::getInstance()->query('ALTER TABLE :table: COMMENT=:version:;');
	$QwriteData->bindTable(':table:', $__datatable_table);
	$QwriteData->bindValue(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->query_handler===false) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->error;
	}
}

?>