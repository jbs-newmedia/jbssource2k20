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
$__datatable_table='weberp_rechnung_cron';
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
	cron_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	kunde_id int(11) unsigned NOT NULL DEFAULT 0,
	cron_date int(8) unsigned NOT NULL DEFAULT 0,
	cron_months varchar(12) NOT NULL DEFAULT \'\',
	cron_leistung_von varchar(16) NOT NULL DEFAULT \'\',
	cron_leistung_bis varchar(16) NOT NULL DEFAULT \'\',
	cron_ispublic tinyint(1) unsigned NOT NULL DEFAULT 0,
	cron_artikel_1_anzahl float NOT NULL DEFAULT 0,
	cron_artikel_1_id int(11) unsigned NOT NULL DEFAULT 0,
	cron_artikel_1_cron int(11) unsigned NOT NULL DEFAULT 0,
	cron_artikel_1_zusatz varchar(255) NOT NULL DEFAULT \'\',
	cron_artikel_1_nr int(11) unsigned NOT NULL DEFAULT 0,
	cron_artikel_1_kurz varchar(4) NOT NULL DEFAULT \'\',
	cron_artikel_1_beschreibung varchar(255) NOT NULL DEFAULT \'\',
	cron_artikel_1_beschreibung_ausblenden tinyint(1) unsigned NOT NULL DEFAULT 0,
	cron_artikel_1_preis float NOT NULL DEFAULT 0,
	cron_artikel_1_typ int(1) unsigned NOT NULL DEFAULT 0,
	cron_artikel_1_mwst int(3) unsigned NOT NULL DEFAULT 0,
	cron_create_time int(11) unsigned NOT NULL DEFAULT 0,
	cron_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	cron_update_time int(11) unsigned NOT NULL DEFAULT 0,
	cron_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (cron_id),
	KEY mandant_id (mandant_id),
	KEY kunde_id (kunde_id),
	KEY cron_date (cron_date),
	KEY cron_months (cron_months),
	KEY cron_ispublic (cron_ispublic),
	KEY cron_create_time (cron_create_time),
	KEY cron_create_user_id (cron_create_user_id),
	KEY cron_update_time (cron_update_time),
	KEY cron_update_user_id (cron_update_user_id)
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