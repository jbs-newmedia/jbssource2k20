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

$VIS2_Mandant->directEmptyMandant($osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getDefaultPage()));

/*
 * DDM4 initialisieren
 */
$ddm4_object=[];
$ddm4_object['general']=[];
$ddm4_object['general']['engine']='vis2_datatables';
$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page']=50;
$ddm4_object['general']['enable_log']=true;
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS2_User->getId();
$ddm4_object['data']['mandant_id']=$VIS2_Mandant->getId();
$ddm4_object['data']['tool']=$VIS2_Main->getTool();
$ddm4_object['data']['page']=$VIS2_Navigation->getPage();
$ddm4_object['messages']=[];
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen';
$ddm4_object['messages']['data_noresults']='Kein Lohn vorhanden';
$ddm4_object['messages']['search_title']='Lohn durchsuchen';
$ddm4_object['messages']['add_title']='Neuen Lohn anlegen';
$ddm4_object['messages']['add_success_title']='Lohn wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Lohn konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Lohn editieren';
$ddm4_object['messages']['edit_load_error_title']='Lohn wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Lohn wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Lohn konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Lohn löschen';
$ddm4_object['messages']['delete_load_error_title']='Lohn wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Lohn wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Lohn konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_lohn';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='lohn_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['lohn_jahr']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_lohn', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(lohn_id) AS counter FROM :table_weberp_lohn: WHERE mandant_id=:mandant_id: AND lohn_jahr=:lohn_jahr:');
$QselectCount->bindTable(':table_weberp_lohn:', 'weberp_lohn');
$QselectCount->bindInt(':lohn_jahr:', date('Y'));
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>date('Y'), 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(lohn_id) AS counter FROM :table_weberp_lohn: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_lohn:', 'weberp_lohn');
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Alle', 'counter'=>clone $QselectCount];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [1])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'lohn_jahr', 'operator'=>'=', 'value'=>date('Y')], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

/*
 * PreView: VIS2_Navigation
 */
$ddm4_elements['preview']['vis2_navigation']=[];
$ddm4_elements['preview']['vis2_navigation']['module']='vis2_navigation';
$ddm4_elements['preview']['vis2_navigation']['options']=[];
$ddm4_elements['preview']['vis2_navigation']['options']['data']=$navigation_links;

/*
 * View: VIS2_Datatables
 */
$ddm4_elements['view']['vis2_datatables']=[];
$ddm4_elements['view']['vis2_datatables']['module']='vis2_datatables';

/*
 * Data: Jahr
 */
$ddm4_elements['data']['lohn_jahr']=[];
$ddm4_elements['data']['lohn_jahr']['module']='select';
$ddm4_elements['data']['lohn_jahr']['title']='Jahr';
$ddm4_elements['data']['lohn_jahr']['name']='lohn_jahr';
$ddm4_elements['data']['lohn_jahr']['options']=[];
$ddm4_elements['data']['lohn_jahr']['options']['order']=true;
$ddm4_elements['data']['lohn_jahr']['options']['search']=true;
$ddm4_elements['data']['lohn_jahr']['options']['required']=true;
$ddm4_elements['data']['lohn_jahr']['options']['data']=$VIS2_WebERP_Verwaltung->getYears();
$ddm4_elements['data']['lohn_jahr']['validation']=[];
$ddm4_elements['data']['lohn_jahr']['validation']['module']='integer';
$ddm4_elements['data']['lohn_jahr']['validation']['length_min']=4;
$ddm4_elements['data']['lohn_jahr']['validation']['length_max']=4;
$ddm4_elements['data']['lohn_jahr']['validation']['value_min']=\JBSNewMedia\WebERP\Verwaltung::getBeginningYear();
$ddm4_elements['data']['lohn_jahr']['validation']['value_max']=\JBSNewMedia\WebERP\Verwaltung::getFutureYear();

/*
 * Data: Mitarbeiter
 */
$ddm4_elements['data']['mitarbeiter_id']=[];
$ddm4_elements['data']['mitarbeiter_id']['module']='select';
$ddm4_elements['data']['mitarbeiter_id']['title']='Mitarbeiter';
$ddm4_elements['data']['mitarbeiter_id']['name']='mitarbeiter_id';
$ddm4_elements['data']['mitarbeiter_id']['options']=[];
$ddm4_elements['data']['mitarbeiter_id']['options']['order']=true;
$ddm4_elements['data']['mitarbeiter_id']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_id']['options']['required']=true;
$ddm4_elements['data']['mitarbeiter_id']['options']['data']=$VIS2_WebERP_Verwaltung->getMitarbeiter();
$ddm4_elements['data']['mitarbeiter_id']['validation']=[];
$ddm4_elements['data']['mitarbeiter_id']['validation']['module']='integer';
$ddm4_elements['data']['mitarbeiter_id']['validation']['length_min']=1;
$ddm4_elements['data']['mitarbeiter_id']['validation']['length_max']=11;


for ($i=1; $i<=12; $i++) {
	/*
	 * Data: Monat (Brutto)
	 */
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['module']='text';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['title']=strftime('%B', mktime(12, 0, 0, $i, 15, 2000)).' (Brutto)';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['name']='lohn_'.sprintf('%02d', $i).'_brutto';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['options']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['options']['order']=true;
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['options']['search']=true;
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['options']['default_value']=0;
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['validation']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['validation']['module']='float';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['_list']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_brutto']['_list']['enabled']=false;

	/*
	 * Data: Monat (Netto)
	 */
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['module']='text';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['title']=strftime('%B', mktime(12, 0, 0, $i, 15, 2000)).' (Netto)';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['name']='lohn_'.sprintf('%02d', $i).'_netto';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['options']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['options']['order']=true;
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['options']['search']=true;
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['options']['default_value']=0;
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['validation']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['validation']['module']='float';
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['_list']=[];
	$ddm4_elements['data']['lohn_'.sprintf('%02d', $i).'_netto']['_list']['enabled']=false;
}

/*
 * Data: Gesamt (Brutto)
 */
$ddm4_elements['data']['lohn_gesamt_brutto']=[];
$ddm4_elements['data']['lohn_gesamt_brutto']['module']='text';
$ddm4_elements['data']['lohn_gesamt_brutto']['title']='Gesamt (Brutto)';
$ddm4_elements['data']['lohn_gesamt_brutto']['name']='lohn_gesamt_brutto';
$ddm4_elements['data']['lohn_gesamt_brutto']['options']=[];
$ddm4_elements['data']['lohn_gesamt_brutto']['options']['order']=true;
$ddm4_elements['data']['lohn_gesamt_brutto']['options']['search']=true;
$ddm4_elements['data']['lohn_gesamt_brutto']['options']['read_only']=true;
$ddm4_elements['data']['lohn_gesamt_brutto']['options']['default_value']=0;
$ddm4_elements['data']['lohn_gesamt_brutto']['validation']=[];
$ddm4_elements['data']['lohn_gesamt_brutto']['validation']['module']='float';

/*
 * Data: Gesamt (Netto)
 */
$ddm4_elements['data']['lohn_gesamt_netto']=[];
$ddm4_elements['data']['lohn_gesamt_netto']['module']='text';
$ddm4_elements['data']['lohn_gesamt_netto']['title']='Gesamt (Netto)';
$ddm4_elements['data']['lohn_gesamt_netto']['name']='lohn_gesamt_netto';
$ddm4_elements['data']['lohn_gesamt_netto']['options']=[];
$ddm4_elements['data']['lohn_gesamt_netto']['options']['order']=true;
$ddm4_elements['data']['lohn_gesamt_netto']['options']['search']=true;
$ddm4_elements['data']['lohn_gesamt_netto']['options']['read_only']=true;
$ddm4_elements['data']['lohn_gesamt_netto']['options']['default_value']=0;
$ddm4_elements['data']['lohn_gesamt_netto']['validation']=[];
$ddm4_elements['data']['lohn_gesamt_netto']['validation']['module']='float';

/*
* Data: MandantId
*/
$ddm4_elements['data']['mandant_id']=[];
$ddm4_elements['data']['mandant_id']['module']='hidden';
$ddm4_elements['data']['mandant_id']['title']='MandantId';
$ddm4_elements['data']['mandant_id']['name']='mandant_id';
$ddm4_elements['data']['mandant_id']['options']=[];
$ddm4_elements['data']['mandant_id']['options']['default_value']=$VIS2_Mandant->getId();
$ddm4_elements['data']['mandant_id']['validation']=[];
$ddm4_elements['data']['mandant_id']['validation']['module']='integer';
$ddm4_elements['data']['mandant_id']['validation']['length_min']=1;
$ddm4_elements['data']['mandant_id']['validation']['length_max']=11;
$ddm4_elements['data']['mandant_id']['_view']=[];
$ddm4_elements['data']['mandant_id']['_view']['enabled']=false;
$ddm4_elements['data']['mandant_id']['_search']=[];
$ddm4_elements['data']['mandant_id']['_search']['enabled']=false;
$ddm4_elements['data']['mandant_id']['_edit']=[];
$ddm4_elements['data']['mandant_id']['_edit']['enabled']=false;
$ddm4_elements['data']['mandant_id']['_delete']=[];
$ddm4_elements['data']['mandant_id']['_delete']['enabled']=false;

/*
* Data: VIS2_CreateUpdate
*/
$ddm4_elements['data']['vis2_createupdatestatus']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['options']['order']=true;
$ddm4_elements['data']['vis2_createupdatestatus']['options']['search']=true;
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='lohn_';
$ddm4_elements['data']['vis2_createupdatestatus']['options']['time']=time();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['user_id']=$VIS2_User->getId();
$ddm4_elements['data']['vis2_createupdatestatus']['options']['text_yes']='Aktiviert';
$ddm4_elements['data']['vis2_createupdatestatus']['options']['text_no']='Deaktiviert';
$ddm4_elements['data']['vis2_createupdatestatus']['_list']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']=[];
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_time']=false;
$ddm4_elements['data']['vis2_createupdatestatus']['_list']['options']['display_create_user']=false;

/*
* Data: Optionen
*/
$ddm4_elements['data']['options']=[];
$ddm4_elements['data']['options']['module']='options';
$ddm4_elements['data']['options']['title']='Optionen';

/*
* Finish: VIS2_Store_Form_Data
*/
$ddm4_elements['finish']['vis2_store_form_data']=[];
$ddm4_elements['finish']['vis2_store_form_data']['module']='vis2_store_form_data';
$ddm4_elements['finish']['vis2_store_form_data']['options']=[];
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='lohn_';

/*
* Finish: VIS2_WebERP_Lohn_Summe
*/
$ddm4_elements['finish']['vis2_weberp_lohn_summe']=[];
$ddm4_elements['finish']['vis2_weberp_lohn_summe']['module']='vis2_weberp_lohn_summe';

/*
* AfterFinish: VIS2_Direct
*/
$ddm4_elements['afterfinish']['vis2_direct']=[];
$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';

/*
* Datenelemente hinzufügen
*/
foreach ($ddm4_elements as $key=>$ddm4_key_elements) {
	if ($ddm4_key_elements!==[]) {
		foreach ($ddm4_key_elements as $element_name=>$element_options) {
			$osW_DDM4->addElement($key, $element_name, $element_options);
		}
	}
}

/**
 * DDM4-Objekt Runtime
 */
$osW_DDM4->runDDMPHP();

/**
 * DDM4-Objekt an Template übergeben
 */
$osW_Template->setVar('osW_DDM4', $osW_DDM4);

?>