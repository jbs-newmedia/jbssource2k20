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
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen.';
$ddm4_object['messages']['data_noresults']='Keine Einstellungen vorhanden.';
$ddm4_object['messages']['search_title']='Einstellungen durchsuchen.';
$ddm4_object['messages']['send_title']='Einstellungen editieren.';
$ddm4_object['messages']['send_load_error_title']='Einstellungen wurde nicht gefunden.';
$ddm4_object['messages']['send_success_title']='Einstellungen wurde erfolgreich gespeichert.';
$ddm4_object['messages']['send_error_title']='Einstellungen konnte nicht gespeichert werden.';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='webdms_config';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='config_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['config_name']='asc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'webdms_config', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Allgemein'];
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Ordner'];
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Typ'];
$navigation_links[4]=['navigation_id'=>4, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Status'];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [2,3,4])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

$osW_DDM4->setGroupOption('engine', 'vis2_datatables');

/*
 * Allgemein
 */
if (in_array($ddm_navigation_id, [1])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_formular');

	/*
	 * PreView: VIS2_Navigation
	 */
	$ddm4_elements['send']['vis2_navigation']=[];
	$ddm4_elements['send']['vis2_navigation']['module']='vis2_navigation';
	$ddm4_elements['send']['vis2_navigation']['options']=[];
	$ddm4_elements['send']['vis2_navigation']['options']['data']=$navigation_links;

	/*
	 * Send: Ordnertiefe
	 */
	$ddm4_elements['send']['vis2_webdms_dirlevel']=[];
	$ddm4_elements['send']['vis2_webdms_dirlevel']['module']='text';
	$ddm4_elements['send']['vis2_webdms_dirlevel']['title']='Ordnertiefe';
	$ddm4_elements['send']['vis2_webdms_dirlevel']['name']='vis2_webdms_dirlevel';
	$ddm4_elements['send']['vis2_webdms_dirlevel']['options']=[];
	$ddm4_elements['send']['vis2_webdms_dirlevel']['options']['default_value']=$VIS2_WebDMS_Verwaltung->getIntVar('dirlevel');
	$ddm4_elements['data']['vis2_webdms_dirlevel']['options']['required']=true;
	$ddm4_elements['send']['vis2_webdms_dirlevel']['validation']=[];
	$ddm4_elements['send']['vis2_webdms_dirlevel']['validation']['module']='integer';
	$ddm4_elements['send']['vis2_webdms_dirlevel']['validation']['value_min']=1;
	$ddm4_elements['send']['vis2_webdms_dirlevel']['validation']['value_max']=12;

	/*
	 * Send: API
	 */
	$ddm4_elements['send']['vis2_webdms_api']=[];
	$ddm4_elements['send']['vis2_webdms_api']['module']='text';
	$ddm4_elements['send']['vis2_webdms_api']['title']='API';
	$ddm4_elements['send']['vis2_webdms_api']['name']='vis2_webdms_api';
	$ddm4_elements['send']['vis2_webdms_api']['options']=[];
	$ddm4_elements['send']['vis2_webdms_api']['options']['default_value']=$VIS2_WebDMS_Verwaltung->getStringVar('api');
	$ddm4_elements['send']['vis2_webdms_api']['validation']=[];
	$ddm4_elements['send']['vis2_webdms_api']['validation']['module']='string';
	$ddm4_elements['send']['vis2_webdms_api']['validation']['length_max']=256;

	/*
	 * Send: Cronuser
	 */
	$ddm4_elements['send']['vis2_webdms_cronuser']=[];
	$ddm4_elements['send']['vis2_webdms_cronuser']['module']='select';
	$ddm4_elements['send']['vis2_webdms_cronuser']['title']='Benutzer bei Cronjobs';
	$ddm4_elements['send']['vis2_webdms_cronuser']['name']='vis2_webdms_cronuser';
	$ddm4_elements['send']['vis2_webdms_cronuser']['options']=[];
	$ddm4_elements['send']['vis2_webdms_cronuser']['options']['required']=true;
	$ddm4_elements['send']['vis2_webdms_cronuser']['options']['default_value']=$VIS2_WebDMS_Verwaltung->getIntVar('cronuser');
	$ddm4_elements['send']['vis2_webdms_cronuser']['options']['data']=\VIS2\Core\Manager::getUsers();
	$ddm4_elements['send']['vis2_webdms_cronuser']['validation']=[];
	$ddm4_elements['send']['vis2_webdms_cronuser']['validation']['module']='integer';
	$ddm4_elements['send']['vis2_webdms_cronuser']['validation']['length_min']=1;
	$ddm4_elements['send']['vis2_webdms_cronuser']['validation']['length_max']=11;

	/*
	 * Send: Submit
	 */
	$ddm4_elements['send']['submit']=[];
	$ddm4_elements['send']['submit']['module']='submit';

	/*
	 * Finish: VIS2_WebDMS_Settings
	 */
	$ddm4_elements['finish']['vis2_webdms_settings']=[];
	$ddm4_elements['finish']['vis2_webdms_settings']['module']='vis2_webdms_settings';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']=[];
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][0]=[];
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][0]['key']='dirlevel';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][0]['value']='vis2_webdms_dirlevel';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][0]['type']='int';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][1]=[];
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][1]['key']='api';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][1]['value']='vis2_webdms_api';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][1]['type']='string';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][2]=[];
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][2]['key']='cronuser';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][2]['value']='vis2_webdms_cronuser';
	$ddm4_elements['finish']['vis2_webdms_settings']['options']['data'][2]['type']='int';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Ordner
 */
if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('index_parent', 'ordner_parent_id');
	if ($VIS2_WebDMS_Verwaltung->getIntVar('dirlevel')!==null) {
		$osW_DDM4->setGroupOption('navigation_level', $VIS2_WebDMS_Verwaltung->getIntVar('dirlevel'));
	} else {
		$osW_DDM4->setGroupOption('navigation_level', 3);
	}
	$osW_DDM4->setGroupOption('table', 'webdms_ordner', 'database');
	$osW_DDM4->setGroupOption('index', 'ordner_id', 'database');
	$osW_DDM4->setGroupOption('order', ['ordner_intern_sortorder'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['status_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);

	$messages=[];
	$messages['data_noresults']='Keine Ordner vorhanden';
	$messages['search_title']='Ordner durchsuchen';
	$messages['add_title']='Neuen Ordner anlegen';
	$messages['add_success_title']='Ordner wurde erfolgreich angelegt';
	$messages['add_error_title']='Ordner konnte nicht angelegt werden';
	$messages['edit_title']='Ordner editieren';
	$messages['edit_load_error_title']='Ordner wurde nicht gefunden';
	$messages['edit_success_title']='Ordner wurde erfolgreich editiert';
	$messages['edit_error_title']='Ordner konnte nicht editiert werden';
	$messages['delete_title']='Ordner löschen';
	$messages['delete_load_error_title']='Ordner wurde nicht gefunden';
	$messages['delete_success_title']='Ordner wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Ordner konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Ordnerliste laden
	 */
	$data=$VIS2_WebDMS_Verwaltung->createOrdnerRecursive(0, 0, 99);
	$data_select=$VIS2_WebDMS_Verwaltung->createOrdnerRecursive(0, 0, $osW_DDM4->getGroupOption('navigation_level'));

	/*
	 * Data: Überseite
	 */
	$ddm4_elements['data']['ordner_parent_id']=[];
	$ddm4_elements['data']['ordner_parent_id']['module']='select';
	$ddm4_elements['data']['ordner_parent_id']['title']='Überseite';
	$ddm4_elements['data']['ordner_parent_id']['name']='ordner_parent_id';
	$ddm4_elements['data']['ordner_parent_id']['options']=[];
	$ddm4_elements['data']['ordner_parent_id']['options']['required']=true;
	$ddm4_elements['data']['ordner_parent_id']['options']['data']=$data_select['title'];
	$ddm4_elements['data']['ordner_parent_id']['options']['blank_value']=false;
	$ddm4_elements['data']['ordner_parent_id']['validation']=[];
	$ddm4_elements['data']['ordner_parent_id']['validation']['module']='integer';
	$ddm4_elements['data']['ordner_parent_id']['validation']['length_min']=0;
	$ddm4_elements['data']['ordner_parent_id']['validation']['length_max']=11;
	$ddm4_elements['data']['ordner_parent_id']['validation']['value_min']=0;
	$ddm4_elements['data']['ordner_parent_id']['validation']['value_max']=999999;
	$ddm4_elements['data']['ordner_parent_id']['_edit']=[];
	$ddm4_elements['data']['ordner_parent_id']['_edit']['validation']=[];
	$ddm4_elements['data']['ordner_parent_id']['_edit']['validation']['filter']=[];
	$ddm4_elements['data']['ordner_parent_id']['_edit']['validation']['filter']['vis2_jbsdms_ordner_check_parent_id']=[];
	$ddm4_elements['data']['ordner_parent_id']['_list']=[];
	$ddm4_elements['data']['ordner_parent_id']['_list']['module']='hidden';

	/*
	 * Data: Titel
	 */
	$ddm4_elements['data']['ordner_titel']=[];
	$ddm4_elements['data']['ordner_titel']['module']='texttree';
	$ddm4_elements['data']['ordner_titel']['title']='Titel';
	$ddm4_elements['data']['ordner_titel']['name']='ordner_titel';
	$ddm4_elements['data']['ordner_titel']['options']=[];
	$ddm4_elements['data']['ordner_titel']['options']['required']=true;
	$ddm4_elements['data']['ordner_titel']['options']['search']=true;
	$ddm4_elements['data']['ordner_titel']['options']['data_level']=$data['level'];
	$ddm4_elements['data']['ordner_titel']['options']['index_key']='ordner_id';
	$ddm4_elements['data']['ordner_titel']['validation']=[];
	$ddm4_elements['data']['ordner_titel']['validation']['module']='string';
	$ddm4_elements['data']['ordner_titel']['validation']['length_min']=2;
	$ddm4_elements['data']['ordner_titel']['validation']['length_max']=32;

	/*
	 * Data: Sortierung
	 */
	$ddm4_elements['data']['ordner_sortorder']=[];
	$ddm4_elements['data']['ordner_sortorder']['module']='text';
	$ddm4_elements['data']['ordner_sortorder']['title']='Sortierung';
	$ddm4_elements['data']['ordner_sortorder']['name']='ordner_sortorder';
	$ddm4_elements['data']['ordner_sortorder']['validation']=[];
	$ddm4_elements['data']['ordner_sortorder']['validation']['module']='string';
	$ddm4_elements['data']['ordner_sortorder']['validation']['length_min']=1;
	$ddm4_elements['data']['ordner_sortorder']['validation']['length_max']=11;

	/*
	 * Data: Status
	 */
	$ddm4_elements['data']['ordner_ispublic']=[];
	$ddm4_elements['data']['ordner_ispublic']['module']='yesno';
	$ddm4_elements['data']['ordner_ispublic']['title']='Status';
	$ddm4_elements['data']['ordner_ispublic']['name']='ordner_ispublic';
	$ddm4_elements['data']['ordner_ispublic']['options']=[];
	$ddm4_elements['data']['ordner_ispublic']['options']['default_value']=1;
	$ddm4_elements['data']['ordner_ispublic']['options']['required']=true;
	$ddm4_elements['data']['ordner_ispublic']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['ordner_ispublic']['options']['text_no']='Deaktiviert';

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
	 * Data: Sortierung
	 */
	$ddm4_elements['data']['ordner_intern_sortorder']=[];
	$ddm4_elements['data']['ordner_intern_sortorder']['module']='text';
	$ddm4_elements['data']['ordner_intern_sortorder']['title']='Sortierung';
	$ddm4_elements['data']['ordner_intern_sortorder']['name']='ordner_intern_sortorder';
	$ddm4_elements['data']['ordner_intern_sortorder']['options']=[];
	$ddm4_elements['data']['ordner_intern_sortorder']['options']['order']=true;
	$ddm4_elements['data']['ordner_intern_sortorder']['options']['search']=true;
	$ddm4_elements['data']['ordner_intern_sortorder']['options']['default_value']=1;
	$ddm4_elements['data']['ordner_intern_sortorder']['options']['required']=true;
	$ddm4_elements['data']['ordner_intern_sortorder']['options']['text_yes']='Aktiviert';
	$ddm4_elements['data']['ordner_intern_sortorder']['options']['text_no']='Deaktiviert';
	$ddm4_elements['data']['ordner_intern_sortorder']['_list']=[];
	$ddm4_elements['data']['ordner_intern_sortorder']['_list']['module']='hidden';
	$ddm4_elements['data']['ordner_intern_sortorder']['_search']=[];
	$ddm4_elements['data']['ordner_intern_sortorder']['_search']['enabled']=false;
	$ddm4_elements['data']['ordner_intern_sortorder']['_add']=[];
	$ddm4_elements['data']['ordner_intern_sortorder']['_add']['enabled']=false;
	$ddm4_elements['data']['ordner_intern_sortorder']['_edit']=[];
	$ddm4_elements['data']['ordner_intern_sortorder']['_edit']['enabled']=false;
	$ddm4_elements['data']['ordner_intern_sortorder']['_delete']=[];
	$ddm4_elements['data']['ordner_intern_sortorder']['_delete']['enabled']=false;

	/*
	 * Data: VIS2_CreateUpdate
	 */
	$ddm4_elements['data']['vis2_createupdatestatus']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
	$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
	$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['order']=true;
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['search']=true;
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='ordner_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='ordner_';

	/*
	 * Finish: vis2_webdms_ordner_sortieren
	 */
	$ddm4_elements['finish']['vis2_webdms_ordner_sortieren']=[];
	$ddm4_elements['finish']['vis2_webdms_ordner_sortieren']['module']='vis2_webdms_ordner_sortieren';

	/*
	 * Finish: vis2_jbsdms_ordner_delete
	 */
	$ddm4_elements['finish']['vis2_jbsdms_ordner_delete']=[];
	$ddm4_elements['finish']['vis2_jbsdms_ordner_delete']['module']='vis2_jbsdms_ordner_delete';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Typ
 */
if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'webdms_typ', 'database');
	$osW_DDM4->setGroupOption('index', 'typ_id', 'database');
	$osW_DDM4->setGroupOption('order', ['typ_titel'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['typ_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Typen vorhanden';
	$messages['search_title']='Typen durchsuchen';
	$messages['add_title']='Neuen Typ anlegen';
	$messages['add_success_title']='Typ wurde erfolgreich angelegt';
	$messages['add_error_title']='Typ konnte nicht angelegt werden';
	$messages['edit_title']='Typ editieren';
	$messages['edit_load_error_title']='Typ wurde nicht gefunden';
	$messages['edit_success_title']='Typ wurde erfolgreich editiert';
	$messages['edit_error_title']='Typ konnte nicht editiert werden';
	$messages['delete_title']='Typ löschen';
	$messages['delete_load_error_title']='Typ wurde nicht gefunden';
	$messages['delete_success_title']='Typ wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Typ konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Data: Titel
	 */
	$ddm4_elements['data']['typ_titel']=[];
	$ddm4_elements['data']['typ_titel']['module']='text';
	$ddm4_elements['data']['typ_titel']['title']='Titel';
	$ddm4_elements['data']['typ_titel']['name']='typ_titel';
	$ddm4_elements['data']['typ_titel']['options']=[];
	$ddm4_elements['data']['typ_titel']['options']['order']=true;
	$ddm4_elements['data']['typ_titel']['options']['search']=true;
	$ddm4_elements['data']['typ_titel']['options']['required']=true;
	$ddm4_elements['data']['typ_titel']['validation']=[];
	$ddm4_elements['data']['typ_titel']['validation']['length_min']=1;
	$ddm4_elements['data']['typ_titel']['validation']['length_max']=32;

	/*
	 * Data: Aktiviert
	 */
	$ddm4_elements['data']['typ_ispublic']=[];
	$ddm4_elements['data']['typ_ispublic']['module']='yesno';
	$ddm4_elements['data']['typ_ispublic']['title']='Aktiviert';
	$ddm4_elements['data']['typ_ispublic']['name']='typ_ispublic';
	$ddm4_elements['data']['typ_ispublic']['options']=[];
	$ddm4_elements['data']['typ_ispublic']['options']['required']=true;
	$ddm4_elements['data']['typ_ispublic']['options']['default_value']=1;

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
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='typ_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='typ_';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Status
 */
if (in_array($ddm_navigation_id, [4])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'webdms_status', 'database');
	$osW_DDM4->setGroupOption('index', 'status_id', 'database');
	$osW_DDM4->setGroupOption('order', ['status_titel'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['status_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Status vorhanden';
	$messages['search_title']='Status durchsuchen';
	$messages['add_title']='Neuen Status anlegen';
	$messages['add_success_title']='Status wurde erfolgreich angelegt';
	$messages['add_error_title']='Status konnte nicht angelegt werden';
	$messages['edit_title']='Status editieren';
	$messages['edit_load_error_title']='Status wurde nicht gefunden';
	$messages['edit_success_title']='Status wurde erfolgreich editiert';
	$messages['edit_error_title']='Status konnte nicht editiert werden';
	$messages['delete_title']='Status löschen';
	$messages['delete_load_error_title']='Status wurde nicht gefunden';
	$messages['delete_success_title']='Status wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Status konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Data: Titel
	 */
	$ddm4_elements['data']['status_titel']=[];
	$ddm4_elements['data']['status_titel']['module']='text';
	$ddm4_elements['data']['status_titel']['title']='Titel';
	$ddm4_elements['data']['status_titel']['name']='status_titel';
	$ddm4_elements['data']['status_titel']['options']=[];
	$ddm4_elements['data']['status_titel']['options']['order']=true;
	$ddm4_elements['data']['status_titel']['options']['search']=true;
	$ddm4_elements['data']['status_titel']['options']['required']=true;
	$ddm4_elements['data']['status_titel']['validation']=[];
	$ddm4_elements['data']['status_titel']['validation']['length_min']=1;
	$ddm4_elements['data']['status_titel']['validation']['length_max']=32;

	/*
	 * Data: Aktiviert
	 */
	$ddm4_elements['data']['status_ispublic']=[];
	$ddm4_elements['data']['status_ispublic']['module']='yesno';
	$ddm4_elements['data']['status_ispublic']['title']='Aktiviert';
	$ddm4_elements['data']['status_ispublic']['name']='status_ispublic';
	$ddm4_elements['data']['status_ispublic']['options']=[];
	$ddm4_elements['data']['status_ispublic']['options']['required']=true;
	$ddm4_elements['data']['status_ispublic']['options']['default_value']=1;

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
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='status_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='status_';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Artikel [MwSt.]
 */
if (in_array($ddm_navigation_id, [7])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'webdms_artikel_mwst', 'database');
	$osW_DDM4->setGroupOption('index', 'mwst_id', 'database');
	$osW_DDM4->setGroupOption('order', ['mwst_titel'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['mwst_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine MwSt. vorhanden';
	$messages['search_title']='MwSt. durchsuchen';
	$messages['add_title']='Neue MwSt. anlegen';
	$messages['add_success_title']='MwSt. wurde erfolgreich angelegt';
	$messages['add_error_title']='MwSt. konnte nicht angelegt werden';
	$messages['edit_title']='MwSt. editieren';
	$messages['edit_load_error_title']='MwSt. wurde nicht gefunden';
	$messages['edit_success_title']='MwSt. wurde erfolgreich editiert';
	$messages['edit_error_title']='MwSt. konnte nicht editiert werden';
	$messages['delete_title']='MwSt. löschen';
	$messages['delete_load_error_title']='MwSt. wurde nicht gefunden';
	$messages['delete_success_title']='MwSt. wurde erfolgreich gelöscht';
	$messages['delete_error_title']='MwSt. konnte nicht gelöscht werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

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
	 * Data: Titel
	 */
	$ddm4_elements['data']['mwst_titel']=[];
	$ddm4_elements['data']['mwst_titel']['module']='text';
	$ddm4_elements['data']['mwst_titel']['title']='Titel';
	$ddm4_elements['data']['mwst_titel']['name']='mwst_titel';
	$ddm4_elements['data']['mwst_titel']['options']=[];
	$ddm4_elements['data']['mwst_titel']['options']['order']=true;
	$ddm4_elements['data']['mwst_titel']['options']['search']=true;
	$ddm4_elements['data']['mwst_titel']['validation']=[];
	$ddm4_elements['data']['mwst_titel']['validation']['module']='integer';
	$ddm4_elements['data']['mwst_titel']['validation']['length_min']=0;
	$ddm4_elements['data']['mwst_titel']['validation']['length_max']=3;
	$ddm4_elements['data']['mwst_titel']['validation']['value_min']=1;
	$ddm4_elements['data']['mwst_titel']['validation']['value_max']=100;

	/*
	 * Data: Aktiviert
	 */
	$ddm4_elements['data']['mwst_ispublic']=[];
	$ddm4_elements['data']['mwst_ispublic']['module']='yesno';
	$ddm4_elements['data']['mwst_ispublic']['title']='Aktiviert';
	$ddm4_elements['data']['mwst_ispublic']['name']='mwst_ispublic';
	$ddm4_elements['data']['mwst_ispublic']['options']=[];
	$ddm4_elements['data']['mwst_ispublic']['options']['required']=true;
	$ddm4_elements['data']['mwst_ispublic']['options']['default_value']=1;

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
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='mwst_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='mwst_';

	/*
	* AfterFinish: VIS2_Direct
	*/
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

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