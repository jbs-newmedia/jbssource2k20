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
$__datatable_table='weberp_rechnung';
$__datatable_create=false;
$__datatable_do=false;

/*
 * check version of table
 */
$QreadData=new \osWFrame\Core\Database();
$QreadData->prepare('SHOW TABLE STATUS LIKE :table:');
$QreadData->bindString(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
$QreadData->execute();
if ($QreadData->rowCount()==1) {
	$QreadData_result=$QreadData->fetch();
	$avb_tbl=$QreadData_result['Comment'];
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

	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('
CREATE TABLE :table: (
	rechnung_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	rechnung_nr int(11) unsigned NOT NULL DEFAULT 0,
	kunde_id int(11) unsigned NOT NULL DEFAULT 0,
	rechnung_kunde_nr int(11) NOT NULL DEFAULT 0,
	rechnung_kunde_gewerblich tinyint(1) unsigned NOT NULL DEFAULT 0,
	rechnung_kunde_firma_anrede varchar(32) NOT NULL DEFAULT \'\',
	rechnung_kunde_firma varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_firma2 varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_rechungsasp tinyint(1) unsigned NOT NULL DEFAULT 0,
	rechnung_kunde_anrede varchar(32) NOT NULL DEFAULT \'\',
	rechnung_kunde_titel varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_vorname varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_nachname varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_email varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_strasse varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_land varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_plz varchar(16) NOT NULL DEFAULT \'\',
	rechnung_kunde_ort varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_telefon varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_fax varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_mobil varchar(128) NOT NULL DEFAULT \'\',
	rechnung_kunde_homepage varchar(128) NOT NULL DEFAULT \'\',
	rechnung_datum varchar(8) NOT NULL DEFAULT \'\',
	rechnung_leistung_von varchar(8) NOT NULL DEFAULT \'\',
	rechnung_leistung_bis varchar(8) NOT NULL DEFAULT \'\',
	rechnung_storniert tinyint(1) unsigned NOT NULL DEFAULT 0,
	rechnung_bezahlt tinyint(1) unsigned NOT NULL DEFAULT 0,
	rechnung_gesendet tinyint(1) unsigned NOT NULL DEFAULT 0,
	rechnung_gesamt_brutto float NOT NULL DEFAULT 0,
	rechnung_gesamt_netto float NOT NULL DEFAULT 0,
	rechnung_gesamt_mwst float NOT NULL DEFAULT 0,
	rechnung_create_time int(11) unsigned NOT NULL DEFAULT 0,
	rechnung_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	rechnung_update_time int(11) unsigned NOT NULL DEFAULT 0,
	rechnung_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (rechnung_id),
	KEY mandant_id (mandant_id),
	KEY rechnung_nr (rechnung_nr),
	KEY kunde_id (kunde_id),
	KEY rechnung_kunde_nr (rechnung_kunde_nr),
	KEY rechnung_storniert (rechnung_storniert),
	KEY rechnung_bezahlt (rechnung_bezahlt),
	KEY rechnung_gesendet (rechnung_gesendet),
	KEY rechnung_gesamt_brutto (rechnung_gesamt_brutto),
	KEY rechnung_gesamt_netto (rechnung_gesamt_netto),
	KEY rechnung_gesamt_mwst (rechnung_gesamt_mwst),
	KEY rechnung_create_time (rechnung_create_time),
	KEY rechnung_create_user_id (rechnung_create_user_id),
	KEY rechnung_update_time (rechnung_update_time),
	KEY rechnung_update_user_id (rechnung_update_user_id)
) ENGINE=:engine: DEFAULT CHARSET=:charset: COMMENT=:version:;
');
	$QwriteData->bindRaw(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QwriteData->bindString(':engine:', $this->getJSONStringValue('database_engine'));
	$QwriteData->bindString(':charset:', $this->getJSONStringValue('database_character'));
	$QwriteData->bindString(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->getErrorMessage();
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
	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('ALTER TABLE :table: COMMENT=:version:;');
	$QwriteData->bindString(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QwriteData->bindString(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->getErrorMessage();
	}
}

?>