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
$__datatable_table='weberp_lohn';
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
	lohn_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	mitarbeiter_id int(11) unsigned NOT NULL DEFAULT 0,
	lohn_jahr int(4) unsigned NOT NULL DEFAULT 0,
	lohn_01_brutto float NOT NULL DEFAULT 0,
	lohn_01_netto float NOT NULL DEFAULT 0,
	lohn_02_brutto float NOT NULL DEFAULT 0,
	lohn_02_netto float NOT NULL DEFAULT 0,
	lohn_03_brutto float NOT NULL DEFAULT 0,
	lohn_03_netto float NOT NULL DEFAULT 0,
	lohn_04_brutto float NOT NULL DEFAULT 0,
	lohn_04_netto float NOT NULL DEFAULT 0,
	lohn_05_brutto float NOT NULL DEFAULT 0,
	lohn_05_netto float NOT NULL DEFAULT 0,
	lohn_06_brutto float NOT NULL DEFAULT 0,
	lohn_06_netto float NOT NULL DEFAULT 0,
	lohn_07_brutto float NOT NULL DEFAULT 0,
	lohn_07_netto float NOT NULL DEFAULT 0,
	lohn_08_brutto float NOT NULL DEFAULT 0,
	lohn_08_netto float NOT NULL DEFAULT 0,
	lohn_09_brutto float NOT NULL DEFAULT 0,
	lohn_09_netto float NOT NULL DEFAULT 0,
	lohn_10_brutto float NOT NULL DEFAULT 0,
	lohn_10_netto float NOT NULL DEFAULT 0,
	lohn_11_brutto float NOT NULL DEFAULT 0,
	lohn_11_netto float NOT NULL DEFAULT 0,
	lohn_12_brutto float NOT NULL DEFAULT 0,
	lohn_12_netto float NOT NULL DEFAULT 0,
	lohn_gesamt_brutto float NOT NULL DEFAULT 0,
	lohn_gesamt_netto float NOT NULL DEFAULT 0,
	lohn_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	lohn_create_time int(11) unsigned NOT NULL DEFAULT 0,
	lohn_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	lohn_update_time int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (lohn_id),
	KEY mandant_id (mandant_id),
	KEY mitarbeiter_id (mitarbeiter_id),
	KEY lohn_jahr (lohn_jahr),
	KEY lohn_create_user_id (lohn_create_user_id),
	KEY lohn_update_user_id (lohn_update_user_id),
	KEY lohn_update_time (lohn_update_time),
	KEY lohn_create_time (lohn_create_time)
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