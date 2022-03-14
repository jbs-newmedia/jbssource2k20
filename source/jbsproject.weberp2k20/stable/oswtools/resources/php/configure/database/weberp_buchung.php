<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license MIT License
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