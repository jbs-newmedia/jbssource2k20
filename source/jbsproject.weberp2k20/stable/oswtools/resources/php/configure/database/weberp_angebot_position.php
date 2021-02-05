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
$__datatable_table='weberp_angebot_position';
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