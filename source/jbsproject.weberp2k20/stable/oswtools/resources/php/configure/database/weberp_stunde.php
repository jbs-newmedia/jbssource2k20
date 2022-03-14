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
$__datatable_table='weberp_stunde';
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