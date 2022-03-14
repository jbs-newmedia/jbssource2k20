<?php

/**
 * This file is part of the VIS2:WebDMS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebDMS
 * @link https://jbs-newmedia.com
 * @license MIT License
 */

/*
 * init
 */
$__datatable_table='webdms_dokument';
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
	dokument_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	mandant_id int(11) unsigned NOT NULL DEFAULT 0,
	dokument_datum varchar(8) NOT NULL DEFAULT \'\',
	status_id int(11) unsigned NOT NULL DEFAULT 0,
	typ_id int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_1 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_2 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_3 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_4 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_5 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_6 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_7 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_8 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_9 int(11) unsigned NOT NULL DEFAULT 0,
	ordner_id_10 int(11) unsigned NOT NULL DEFAULT 0,
	dokument_file varchar(255) NOT NULL DEFAULT \'\',
	dokument_file_name varchar(255) NOT NULL DEFAULT \'\',
	dokument_file_type varchar(64) NOT NULL DEFAULT \'\',
	dokument_file_size int(11) NOT NULL DEFAULT 0,
	dokument_file_md5 varchar(32) NOT NULL DEFAULT \'\',
	dokument_file_ocr varchar(255) NOT NULL DEFAULT \'\',
	dokument_file_ocr_name varchar(255) NOT NULL DEFAULT \'\',
	dokument_file_ocr_type varchar(64) NOT NULL DEFAULT \'\',
	dokument_file_ocr_size int(11) NOT NULL DEFAULT 0,
	dokument_file_ocr_md5 varchar(32) NOT NULL DEFAULT \'\',
	dokument_titel varchar(255) NOT NULL DEFAULT \'\',
	dokument_beschreibung text NOT NULL DEFAULT \'\',
	dokument_index_1 mediumtext NOT NULL DEFAULT \'\',
	dokument_index_2 mediumtext NOT NULL DEFAULT \'\',
	dokument_index_3 mediumtext NOT NULL DEFAULT \'\',
	dokument_index_time int(11) unsigned NOT NULL DEFAULT 0,
	dokument_ispublic int(11) unsigned NOT NULL DEFAULT 0,
	dokument_create_time int(11) unsigned NOT NULL DEFAULT 0,
	dokument_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	dokument_update_time int(11) unsigned NOT NULL DEFAULT 0,
	dokument_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (dokument_id),
	KEY mandant_id (mandant_id),
	KEY dokument_datum (dokument_datum),
	KEY status_id (status_id),
	KEY typ_id (typ_id),
	KEY ordner_id_1 (ordner_id_1),
	KEY ordner_id_2 (ordner_id_2),
	KEY ordner_id_3 (ordner_id_3),
	KEY ordner_id_4 (ordner_id_4),
	KEY ordner_id_5 (ordner_id_5),
	KEY ordner_id_6 (ordner_id_6),
	KEY ordner_id_7 (ordner_id_7),
	KEY ordner_id_8 (ordner_id_8),
	KEY ordner_id_9 (ordner_id_9),
	KEY ordner_id_10 (ordner_id_10),
	FULLTEXT KEY dokument_index_1 (dokument_index_1),
	FULLTEXT KEY dokument_index_2 (dokument_index_2),
	FULLTEXT KEY dokument_index_3 (dokument_index_3),
	KEY dokument_index_time (dokument_index_time),
	KEY dokument_ispublic (dokument_ispublic),
	KEY dokument_create_time (dokument_create_time),
	KEY dokument_create_user_id (dokument_create_user_id),
	KEY dokument_update_time (dokument_update_time),
	KEY dokument_update_user_id (dokument_update_user_id)
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