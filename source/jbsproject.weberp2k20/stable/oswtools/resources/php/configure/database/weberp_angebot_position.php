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
$__datatable_table='weberp_angebot_position';
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
	position_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	angebot_id int(11) unsigned NOT NULL DEFAULT 0,
	position_pos int(11) unsigned NOT NULL DEFAULT 0,
	position_artikel_anzahl float NOT NULL DEFAULT 0,
	position_artikel_id int(11) unsigned NOT NULL DEFAULT 0,
	position_artikel_cron int(11) unsigned NOT NULL DEFAULT 0,
	position_artikel_zusatz text NOT NULL DEFAULT \'\',
	position_artikel_nr int(11) unsigned NOT NULL DEFAULT 0,
	position_artikel_kurz varchar(4) NOT NULL DEFAULT \'\',
	position_artikel_beschreibung text NOT NULL DEFAULT \'\',
	position_artikel_beschreibung_ausblenden tinyint(1) unsigned NOT NULL DEFAULT 0,
	position_artikel_preis float NOT NULL DEFAULT 0,
	position_artikel_typ int(1) unsigned NOT NULL DEFAULT 0,
	position_artikel_mwst int(3) unsigned NOT NULL DEFAULT 0,
	position_create_time int(11) unsigned NOT NULL DEFAULT 0,
	position_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	position_update_time int(11) unsigned NOT NULL DEFAULT 0,
	position_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (position_id),
	KEY mandant_id (mandant_id),
	KEY angebot_id (angebot_id),
	KEY position_pos (position_pos),
	KEY position_artikel_id (position_artikel_id),
	KEY position_artikel_cron (position_artikel_cron),
	KEY position_create_time (position_create_time),
	KEY position_create_user_id (position_create_user_id),
	KEY position_update_time (position_update_time),
	KEY position_update_user_id (position_update_user_id)
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