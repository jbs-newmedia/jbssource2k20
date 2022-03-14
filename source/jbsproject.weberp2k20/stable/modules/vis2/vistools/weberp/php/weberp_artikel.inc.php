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
$ddm4_object['messages']['data_noresults']='Keine Artikel vorhanden';
$ddm4_object['messages']['search_title']='Artikel durchsuchen';
$ddm4_object['messages']['add_title']='Neuen Artikel anlegen';
$ddm4_object['messages']['add_success_title']='Artikel wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Artikel konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Artikel editieren';
$ddm4_object['messages']['edit_load_error_title']='Artikel wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Artikel wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Artikel konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Artikel löschen';
$ddm4_object['messages']['delete_load_error_title']='Artikel wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Artikel wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Artikel konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_artikel';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='artikel_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['artikel_nr']='asc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_artikel', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(artikel_id) AS counter FROM :table_weberp_artikel: WHERE mandant_id=:mandant_id: AND artikel_ispublic=:artikel_ispublic:');
$QselectCount->bindTable(':table_weberp_artikel:', 'weberp_artikel');
$QselectCount->bindInt(':artikel_ispublic:', 1);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Aktiviert', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(artikel_id) AS counter FROM :table_weberp_artikel: WHERE mandant_id=:mandant_id: AND artikel_ispublic=:artikel_ispublic:');
$QselectCount->bindTable(':table_weberp_artikel:', 'weberp_artikel');
$QselectCount->bindInt(':artikel_ispublic:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Deaktiviert', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(artikel_id) AS counter FROM :table_weberp_artikel: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_artikel:', 'weberp_artikel');
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Alle', 'counter'=>clone $QselectCount];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [1])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'artikel_ispublic', 'operator'=>'=', 'value'=>1], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'artikel_ispublic', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['artikel_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);
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
 * Data: ArtNr
 */
$ddm4_elements['data']['artikel_nr']=[];
$ddm4_elements['data']['artikel_nr']['module']='autovalue';
$ddm4_elements['data']['artikel_nr']['title']='ArtNr';
$ddm4_elements['data']['artikel_nr']['name']='artikel_nr';
$ddm4_elements['data']['artikel_nr']['options']=[];
$ddm4_elements['data']['artikel_nr']['options']['order']=true;
$ddm4_elements['data']['artikel_nr']['options']['search']=true;
$ddm4_elements['data']['artikel_nr']['options']['label']='wird automatisch vergeben';
$ddm4_elements['data']['artikel_nr']['options']['default_value']=1;
$ddm4_elements['data']['artikel_nr']['options']['filter_use']=false;
$ddm4_elements['data']['artikel_nr']['validation']=[];
$ddm4_elements['data']['artikel_nr']['validation']['length_min']=1;
$ddm4_elements['data']['artikel_nr']['validation']['length_max']=6;

/*
 * Data: Kurzform
 */
$ddm4_elements['data']['artikel_kurz']=[];
$ddm4_elements['data']['artikel_kurz']['module']='text';
$ddm4_elements['data']['artikel_kurz']['title']='Kurzform';
$ddm4_elements['data']['artikel_kurz']['name']='artikel_kurz';
$ddm4_elements['data']['artikel_kurz']['options']=[];
$ddm4_elements['data']['artikel_kurz']['options']['order']=true;
$ddm4_elements['data']['artikel_kurz']['options']['search']=true;
$ddm4_elements['data']['artikel_kurz']['options']['required']=true;
$ddm4_elements['data']['artikel_kurz']['validation']=[];
$ddm4_elements['data']['artikel_kurz']['validation']['length_min']=2;
$ddm4_elements['data']['artikel_kurz']['validation']['length_max']=4;

/*
 * Data: Beschreibung
 */
$ddm4_elements['data']['artikel_beschreibung']=[];
$ddm4_elements['data']['artikel_beschreibung']['module']='textarea';
$ddm4_elements['data']['artikel_beschreibung']['title']='Beschreibung';
$ddm4_elements['data']['artikel_beschreibung']['name']='artikel_beschreibung';
$ddm4_elements['data']['artikel_beschreibung']['options']=[];
$ddm4_elements['data']['artikel_beschreibung']['options']['order']=true;
$ddm4_elements['data']['artikel_beschreibung']['options']['search']=true;
$ddm4_elements['data']['artikel_beschreibung']['options']['required']=true;
$ddm4_elements['data']['artikel_beschreibung']['validation']=[];
$ddm4_elements['data']['artikel_beschreibung']['validation']['length_min']=6;
$ddm4_elements['data']['artikel_beschreibung']['validation']['length_max']=10000;

/*
 * Data: Beschreibung ausblenden
 */
$ddm4_elements['data']['artikel_beschreibung_ausblenden']=[];
$ddm4_elements['data']['artikel_beschreibung_ausblenden']['module']='yesno';
$ddm4_elements['data']['artikel_beschreibung_ausblenden']['title']='Beschreibung ausblenden';
$ddm4_elements['data']['artikel_beschreibung_ausblenden']['name']='artikel_beschreibung_ausblenden';
$ddm4_elements['data']['artikel_beschreibung_ausblenden']['options']=[];
$ddm4_elements['data']['artikel_beschreibung_ausblenden']['options']['default_value']=0;
$ddm4_elements['data']['artikel_beschreibung_ausblenden']['_list']=[];
$ddm4_elements['data']['artikel_beschreibung_ausblenden']['_list']['enabled']=false;

/*
 * Data: Typ
 */
$ddm4_elements['data']['artikel_typ']=[];
$ddm4_elements['data']['artikel_typ']['module']='select';
$ddm4_elements['data']['artikel_typ']['title']='Typ';
$ddm4_elements['data']['artikel_typ']['name']='artikel_typ';
$ddm4_elements['data']['artikel_typ']['options']=[];
$ddm4_elements['data']['artikel_typ']['options']['order']=true;
$ddm4_elements['data']['artikel_typ']['options']['required']=true;
$ddm4_elements['data']['artikel_typ']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelTypen();
$ddm4_elements['data']['artikel_typ']['validation']=[];
$ddm4_elements['data']['artikel_typ']['validation']['module']='integer';
$ddm4_elements['data']['artikel_typ']['validation']['length_min']=1;
$ddm4_elements['data']['artikel_typ']['validation']['length_max']=1;

/*
 * Data: Preis
 */
$ddm4_elements['data']['artikel_preis']=[];
$ddm4_elements['data']['artikel_preis']['module']='text';
$ddm4_elements['data']['artikel_preis']['title']='Preis';
$ddm4_elements['data']['artikel_preis']['name']='artikel_preis';
$ddm4_elements['data']['artikel_preis']['options']=[];
$ddm4_elements['data']['artikel_preis']['options']['order']=true;
$ddm4_elements['data']['artikel_preis']['options']['search']=true;
$ddm4_elements['data']['artikel_preis']['options']['required']=true;
$ddm4_elements['data']['artikel_preis']['validation']=[];
$ddm4_elements['data']['artikel_preis']['validation']['module']='float';
$ddm4_elements['data']['artikel_preis']['validation']['length_min']=0;
$ddm4_elements['data']['artikel_preis']['validation']['length_max']=11;

/*
 * Data: MwSt
 */
$ddm4_elements['data']['artikel_mwst']=[];
$ddm4_elements['data']['artikel_mwst']['module']='select';
$ddm4_elements['data']['artikel_mwst']['title']='MwSt';
$ddm4_elements['data']['artikel_mwst']['name']='artikel_mwst';
$ddm4_elements['data']['artikel_mwst']['options']=[];
$ddm4_elements['data']['artikel_mwst']['options']['order']=true;
$ddm4_elements['data']['artikel_mwst']['options']['required']=true;
$ddm4_elements['data']['artikel_mwst']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelMwSt(true, 'mwst_titel');
$ddm4_elements['data']['artikel_mwst']['validation']=[];
$ddm4_elements['data']['artikel_mwst']['validation']['module']='integer';
$ddm4_elements['data']['artikel_mwst']['validation']['length_min']=1;
$ddm4_elements['data']['artikel_mwst']['validation']['length_max']=3;

/*
 * Data: Aktiviert
 */
$ddm4_elements['data']['artikel_ispublic']=[];
$ddm4_elements['data']['artikel_ispublic']['module']='yesno';
$ddm4_elements['data']['artikel_ispublic']['title']='Aktiviert';
$ddm4_elements['data']['artikel_ispublic']['name']='artikel_ispublic';
$ddm4_elements['data']['artikel_ispublic']['options']=[];
$ddm4_elements['data']['artikel_ispublic']['options']['order']=true;
$ddm4_elements['data']['artikel_ispublic']['options']['required']=true;
$ddm4_elements['data']['artikel_ispublic']['options']['default_value']=1;

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='artikel_';
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='artikel_';

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