<?php

/**
 * This file is part of the VIS2:WebDMS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebDMS
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

/*
 * init
 */
$__datatable_table='webdms_status';
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
	status_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	status_titel varchar(16) NOT NULL DEFAULT \'\',
	status_ispublic tinyint(1) unsigned NOT NULL DEFAULT 0,
	status_create_time int(11) unsigned NOT NULL DEFAULT 0,
	status_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	status_update_time int(11) unsigned NOT NULL DEFAULT 0,
	status_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (status_id),
	KEY mandant_id (mandant_id),
	KEY status_ispublic (status_ispublic),
	KEY status_create_time (status_create_time),
	KEY status_create_user_id (status_create_user_id),
	KEY status_update_time (status_update_time),
	KEY status_update_user_id (status_update_user_id)
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