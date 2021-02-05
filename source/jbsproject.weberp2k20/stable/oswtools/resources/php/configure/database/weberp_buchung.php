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
$__datatable_table='weberp_buchung';
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
	buchung_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	buchung_checksum varchar(32) NOT NULL DEFAULT \'\',
	buchung_auftragskonto varchar(16) NOT NULL DEFAULT \'\',
	buchung_buchungtag int(8) unsigned NOT NULL DEFAULT 0,
	buchung_valutadatum int(8) NOT NULL DEFAULT 0,
	buchung_text varchar(128) NOT NULL DEFAULT \'\',
	buchung_verwendungszweck varchar(128) NOT NULL DEFAULT \'\',
	buchung_kontoinhaber varchar(128) NOT NULL DEFAULT \'\',
	buchung_iban varchar(64) NOT NULL DEFAULT \'\',
	buchung_bic varchar(32) NOT NULL DEFAULT \'\',
	buchung_betrag float NOT NULL DEFAULT 0,
	buchung_waehrung varchar(12) NOT NULL DEFAULT \'\',
	buchung_info varchar(16) NOT NULL DEFAULT \'\',
	buchung_create_time int(11) unsigned NOT NULL DEFAULT 0,
	buchung_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	buchung_update_time int(11) unsigned NOT NULL DEFAULT 0,
	buchung_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (buchung_id),
	KEY mandant_id (mandant_id),
	KEY buchung_auftragskonto (buchung_auftragskonto),
	KEY buchung_buchungtag (buchung_buchungtag),
	KEY buchung_valutadatum (buchung_valutadatum),
	KEY buchung_checksum (buchung_checksum),
	KEY buchung_create_time (buchung_create_time),
	KEY buchung_create_user_id (buchung_create_user_id),
	KEY buchung_update_time (buchung_update_time),
	KEY buchung_update_user_id (buchung_update_user_id)
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