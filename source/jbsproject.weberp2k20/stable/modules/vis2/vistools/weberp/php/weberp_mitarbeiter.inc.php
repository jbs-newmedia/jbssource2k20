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
$ddm4_object['messages']['data_noresults']='Keine Mitarbeiter vorhanden';
$ddm4_object['messages']['search_title']='Mitarbeiter durchsuchen';
$ddm4_object['messages']['add_title']='Neuen Mitarbeiter anlegen';
$ddm4_object['messages']['add_success_title']='Mitarbeiter wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Mitarbeiter konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Mitarbeiter editieren';
$ddm4_object['messages']['edit_load_error_title']='Mitarbeiter wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Mitarbeiter wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Mitarbeiter konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Mitarbeiter löschen';
$ddm4_object['messages']['delete_load_error_title']='Mitarbeiter wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Mitarbeiter wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Mitarbeiter konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_mitarbeiter';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='mitarbeiter_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['mitarbeiter_nr']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_mitarbeiter', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(mitarbeiter_id) AS counter FROM :table_weberp_mitarbeiter: WHERE mandant_id=:mandant_id: AND mitarbeiter_ispublic=:mitarbeiter_ispublic:');
$QselectCount->bindTable(':table_weberp_mitarbeiter:', 'weberp_mitarbeiter');
$QselectCount->bindInt(':mitarbeiter_ispublic:', 1);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Aktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(mitarbeiter_id) AS counter FROM :table_weberp_mitarbeiter: WHERE mandant_id=:mandant_id: AND mitarbeiter_ispublic=:mitarbeiter_ispublic:');
$QselectCount->bindTable(':table_weberp_mitarbeiter:', 'weberp_mitarbeiter');
$QselectCount->bindInt(':mitarbeiter_ispublic:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Inaktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(mitarbeiter_id) AS counter FROM :table_weberp_mitarbeiter: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_mitarbeiter:', 'weberp_mitarbeiter');
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
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mitarbeiter_ispublic', 'operator'=>'=', 'value'=>1], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mitarbeiter_ispublic', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['mitarbeiter_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);
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
 * Data: MaNr
 */
$ddm4_elements['data']['mitarbeiter_nr']=[];
$ddm4_elements['data']['mitarbeiter_nr']['module']='autovalue';
$ddm4_elements['data']['mitarbeiter_nr']['title']='MaNr';
$ddm4_elements['data']['mitarbeiter_nr']['name']='mitarbeiter_nr';
$ddm4_elements['data']['mitarbeiter_nr']['options']=[];
$ddm4_elements['data']['mitarbeiter_nr']['options']['order']=true;
$ddm4_elements['data']['mitarbeiter_nr']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_nr']['options']['required']=true;
$ddm4_elements['data']['mitarbeiter_nr']['options']['label']='wird automatisch vergeben';
$ddm4_elements['data']['mitarbeiter_nr']['options']['default_value']=1001;
$ddm4_elements['data']['mitarbeiter_nr']['options']['filter_use']='';
$ddm4_elements['data']['mitarbeiter_nr']['validation']=[];
$ddm4_elements['data']['mitarbeiter_nr']['validation']['length_min']=4;
$ddm4_elements['data']['mitarbeiter_nr']['validation']['length_max']=4;

/*
 * Data: Anrede
 */
$ddm4_elements['data']['mitarbeiter_anrede']=[];
$ddm4_elements['data']['mitarbeiter_anrede']['module']='select';
$ddm4_elements['data']['mitarbeiter_anrede']['title']='Anrede';
$ddm4_elements['data']['mitarbeiter_anrede']['name']='mitarbeiter_anrede';
$ddm4_elements['data']['mitarbeiter_anrede']['options']=[];
$ddm4_elements['data']['mitarbeiter_anrede']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_anrede']['options']['required']=true;
$ddm4_elements['data']['mitarbeiter_anrede']['options']['data']=['Herr'=>'Herr', 'Frau'=>'Frau'];
$ddm4_elements['data']['mitarbeiter_anrede']['validation']=[];
$ddm4_elements['data']['mitarbeiter_anrede']['validation']['length_min']=2;
$ddm4_elements['data']['mitarbeiter_anrede']['validation']['length_max']=32;
$ddm4_elements['data']['mitarbeiter_anrede']['_list']=[];
$ddm4_elements['data']['mitarbeiter_anrede']['_list']['enabled']='';

/*
 * Data: Vorname
 */
$ddm4_elements['data']['mitarbeiter_vorname']=[];
$ddm4_elements['data']['mitarbeiter_vorname']['module']='text';
$ddm4_elements['data']['mitarbeiter_vorname']['title']='Vorname';
$ddm4_elements['data']['mitarbeiter_vorname']['name']='mitarbeiter_vorname';
$ddm4_elements['data']['mitarbeiter_vorname']['options']=[];
$ddm4_elements['data']['mitarbeiter_vorname']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_vorname']['options']['order']=true;
$ddm4_elements['data']['mitarbeiter_vorname']['options']['required']=true;
$ddm4_elements['data']['mitarbeiter_vorname']['validation']=[];
$ddm4_elements['data']['mitarbeiter_vorname']['validation']['length_min']=2;
$ddm4_elements['data']['mitarbeiter_vorname']['validation']['length_max']=128;

/*
 * Data: Nachname
 */
$ddm4_elements['data']['mitarbeiter_nachname']=[];
$ddm4_elements['data']['mitarbeiter_nachname']['module']='text';
$ddm4_elements['data']['mitarbeiter_nachname']['title']='Nachname';
$ddm4_elements['data']['mitarbeiter_nachname']['name']='mitarbeiter_nachname';
$ddm4_elements['data']['mitarbeiter_nachname']['options']=[];
$ddm4_elements['data']['mitarbeiter_nachname']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_nachname']['options']['order']=true;
$ddm4_elements['data']['mitarbeiter_nachname']['options']['required']=true;
$ddm4_elements['data']['mitarbeiter_nachname']['validation']=[];
$ddm4_elements['data']['mitarbeiter_nachname']['validation']['length_min']=2;
$ddm4_elements['data']['mitarbeiter_nachname']['validation']['length_max']=128;

/*
 * Data: E-Mail
 */
$ddm4_elements['data']['mitarbeiter_email']=[];
$ddm4_elements['data']['mitarbeiter_email']['module']='text';
$ddm4_elements['data']['mitarbeiter_email']['title']='E-Mail';
$ddm4_elements['data']['mitarbeiter_email']['name']='mitarbeiter_email';
$ddm4_elements['data']['mitarbeiter_email']['options']=[];
$ddm4_elements['data']['mitarbeiter_email']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_email']['validation']=[];
$ddm4_elements['data']['mitarbeiter_email']['validation']['length_min']=0;
$ddm4_elements['data']['mitarbeiter_email']['validation']['length_max']=128;
$ddm4_elements['data']['mitarbeiter_email']['validation']['filter']=[];
$ddm4_elements['data']['mitarbeiter_email']['validation']['filter']['email']=[];
$ddm4_elements['data']['mitarbeiter_email']['_list']=[];
$ddm4_elements['data']['mitarbeiter_email']['_list']['enabled']='';

/*
 * Data: IBAN
 */
$ddm4_elements['data']['mitarbeiter_iban']=[];
$ddm4_elements['data']['mitarbeiter_iban']['module']='text';
$ddm4_elements['data']['mitarbeiter_iban']['title']='IBAN';
$ddm4_elements['data']['mitarbeiter_iban']['name']='mitarbeiter_iban';
$ddm4_elements['data']['mitarbeiter_iban']['options']=[];
$ddm4_elements['data']['mitarbeiter_iban']['options']['order']=true;
$ddm4_elements['data']['mitarbeiter_iban']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_iban']['validation']=[];
$ddm4_elements['data']['mitarbeiter_iban']['validation']['length_min']=0;
$ddm4_elements['data']['mitarbeiter_iban']['validation']['length_max']=32;
$ddm4_elements['data']['mitarbeiter_iban']['_list']=[];
$ddm4_elements['data']['mitarbeiter_iban']['_list']['enabled']='';

/*
 * Data: BIC
 */
$ddm4_elements['data']['mitarbeiter_bic']=[];
$ddm4_elements['data']['mitarbeiter_bic']['module']='text';
$ddm4_elements['data']['mitarbeiter_bic']['title']='BIC';
$ddm4_elements['data']['mitarbeiter_bic']['name']='mitarbeiter_bic';
$ddm4_elements['data']['mitarbeiter_bic']['options']=[];
$ddm4_elements['data']['mitarbeiter_bic']['options']['search']=true;
$ddm4_elements['data']['mitarbeiter_bic']['options']['order']=true;
$ddm4_elements['data']['mitarbeiter_bic']['validation']=[];
$ddm4_elements['data']['mitarbeiter_bic']['validation']['length_min']=0;
$ddm4_elements['data']['mitarbeiter_bic']['validation']['length_max']=32;
$ddm4_elements['data']['mitarbeiter_bic']['_list']=[];
$ddm4_elements['data']['mitarbeiter_bic']['_list']['enabled']='';

/*
 * Data: Aktiviert
 */
$ddm4_elements['data']['mitarbeiter_ispublic']=[];
$ddm4_elements['data']['mitarbeiter_ispublic']['module']='yesno';
$ddm4_elements['data']['mitarbeiter_ispublic']['title']='Aktiviert';
$ddm4_elements['data']['mitarbeiter_ispublic']['name']='mitarbeiter_ispublic';
$ddm4_elements['data']['mitarbeiter_ispublic']['options']=[];
$ddm4_elements['data']['mitarbeiter_ispublic']['options']['order']=true;
$ddm4_elements['data']['mitarbeiter_ispublic']['options']['required']=true;
$ddm4_elements['data']['mitarbeiter_ispublic']['options']['default_value']=1;

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='mitarbeiter_';
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='mitarbeiter_';

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