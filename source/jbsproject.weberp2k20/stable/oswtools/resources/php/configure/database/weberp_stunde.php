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
$__datatable_table='weberp_stunde';
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
	stunde_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	kunde_id int(11) unsigned NOT NULL DEFAULT 0,
	stunde_datum varchar(8) NOT NULL DEFAULT \'\',
	stunde_beschreibung varchar(256) NOT NULL DEFAULT \'\',
	artikel_id int(11) unsigned NOT NULL DEFAULT 0,
	artikel_anzahl float NOT NULL DEFAULT 0,
	artikel_zusatz text NOT NULL DEFAULT \'\',
	stunde_abrechnen tinyint(1) unsigned NOT NULL DEFAULT 0,
	stunde_abgerechnet tinyint(1) unsigned NOT NULL DEFAULT 0,
	rechnung_id int(11) unsigned NOT NULL DEFAULT 0,
	rechnung_nr int(11) unsigned NOT NULL DEFAULT 0,
	stunde_create_time int(11) unsigned NOT NULL DEFAULT 0,
	stunde_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	stunde_update_time int(11) unsigned NOT NULL DEFAULT 0,
	stunde_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (stunde_id),
	KEY mandant_id (mandant_id),
	KEY kunde_id (kunde_id),
	KEY artikel_id (artikel_id),
	KEY stunde_abrechnen (stunde_abrechnen),
	KEY stunde_abgerechnet (stunde_abgerechnet),
	KEY rechnung_id (rechnung_id),
	KEY rechnung_nr (rechnung_nr),
	KEY stunde_create_time (stunde_create_time),
	KEY stunde_create_user_id (stunde_create_user_id),
	KEY stunde_update_time (stunde_update_time),
	KEY stunde_update_user_id (stunde_update_user_id)
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