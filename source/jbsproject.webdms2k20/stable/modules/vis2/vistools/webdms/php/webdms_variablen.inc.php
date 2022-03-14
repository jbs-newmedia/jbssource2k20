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
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen';
$ddm4_object['messages']['data_noresults']='Keine Variablen vorhanden';
$ddm4_object['messages']['search_title']='Variablen durchsuchen';
$ddm4_object['messages']['add_title']='Neue Variable anlegen';
$ddm4_object['messages']['add_success_title']='Variable wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Variable konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Variable editieren';
$ddm4_object['messages']['edit_load_error_title']='Variable wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Variable wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Variable konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Variable löschen';
$ddm4_object['messages']['delete_load_error_title']='Variable wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Variable wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Variable konnte nicht gelöscht werden';
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
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'webdms_variablen', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Allgemein'];
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Text'];
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'String'];
$navigation_links[4]=['navigation_id'=>4, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Int'];
$navigation_links[5]=['navigation_id'=>5, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Float'];
$navigation_links[6]=['navigation_id'=>6, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Bool'];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()], ['key'=>'config_type', 'operator'=>'=', 'value'=>$osW_DDM4->getConnection()->escapeString('text')]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()], ['key'=>'config_type', 'operator'=>'=', 'value'=>$osW_DDM4->getConnection()->escapeString('string')]]]], 'database');
}

if (in_array($ddm_navigation_id, [4])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()], ['key'=>'config_type', 'operator'=>'=', 'value'=>$osW_DDM4->getConnection()->escapeString('int')]]]], 'database');
}

if (in_array($ddm_navigation_id, [5])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()], ['key'=>'config_type', 'operator'=>'=', 'value'=>$osW_DDM4->getConnection()->escapeString('float')]]]], 'database');
}

if (in_array($ddm_navigation_id, [6])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()], ['key'=>'config_type', 'operator'=>'=', 'value'=>$osW_DDM4->getConnection()->escapeString('bool')]]]], 'database');
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
	 * Send: Vorsicht
	 */
	$ddm4_elements['send']['vis2_weberp_strasse']=[];
	$ddm4_elements['send']['vis2_weberp_strasse']['module']='label';
	$ddm4_elements['send']['vis2_weberp_strasse']['title']='Vorsicht';
	$ddm4_elements['send']['vis2_weberp_strasse']['options']=[];
	$ddm4_elements['send']['vis2_weberp_strasse']['options']['label']='In diesem Bereich nur arbeiten, wenn sie wissen was sie tun! Sie können mit falschen Einstellungen die Software zerstören!';
}
/*
 * Text | String | Int | Float | Bool
 */
if (in_array($ddm_navigation_id, [2, 3, 4, 5, 6])) {
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
	 * Data: Variable
	 */
	$ddm4_elements['data']['config_name']=[];
	$ddm4_elements['data']['config_name']['module']='text';
	$ddm4_elements['data']['config_name']['title']='Variable';
	$ddm4_elements['data']['config_name']['name']='config_name';
	$ddm4_elements['data']['config_name']['options']=[];
	$ddm4_elements['data']['config_name']['options']['order']=true;
	$ddm4_elements['data']['config_name']['options']['required']=true;
	$ddm4_elements['data']['config_name']['options']['search']=true;
	$ddm4_elements['data']['config_name']['options']['notice']='Nur a-z und "_". Nach Speichern nicht änderbar.';
	$ddm4_elements['data']['config_name']['validation']=[];
	$ddm4_elements['data']['config_name']['validation']['module']='string';
	$ddm4_elements['data']['config_name']['validation']['length_min']=2;
	$ddm4_elements['data']['config_name']['validation']['length_max']=32;
	$ddm4_elements['data']['config_name']['validation']['preg']='/^[a-z_]+$/';
	$ddm4_elements['data']['config_name']['validation']['filter']=[];
	$ddm4_elements['data']['config_name']['validation']['filter']['unique_filter']=[];
	$ddm4_elements['data']['config_name']['_edit']=[];
	$ddm4_elements['data']['config_name']['_edit']['options']=[];
	$ddm4_elements['data']['config_name']['_edit']['options']['read_only']=true;
	$ddm4_elements['data']['config_name']['_edit']['options']['required']=false;
	$ddm4_elements['data']['config_name']['_edit']['options']['notice']='';
	$ddm4_elements['data']['config_name']['_delete']=[];
	$ddm4_elements['data']['config_name']['_delete']['options']=[];
	$ddm4_elements['data']['config_name']['_delete']['options']['notice']='';

	/*
	 * Data: Beschreibung
	 */
	$ddm4_elements['data']['config_description']=[];
	$ddm4_elements['data']['config_description']['module']='text';
	$ddm4_elements['data']['config_description']['title']='Beschreibung';
	$ddm4_elements['data']['config_description']['name']='config_description';
	$ddm4_elements['data']['config_description']['options']=[];
	$ddm4_elements['data']['config_description']['options']['order']=true;
	$ddm4_elements['data']['config_description']['options']['search']=true;
	$ddm4_elements['data']['config_description']['options']['required']=true;
	$ddm4_elements['data']['config_description']['validation']=[];
	$ddm4_elements['data']['config_description']['validation']['length_min']=1;
	$ddm4_elements['data']['config_description']['validation']['length_max']=128;
	$ddm4_elements['data']['config_description']['_list']=[];
	$ddm4_elements['data']['config_description']['_list']['enabled']=false;

	/*
	 * Variablen [Text]
	 */
	if (in_array($ddm_navigation_id, [2])) {

		/*
		 * Data: Inhalt
		 */
		$ddm4_elements['data']['config_value_text']=[];
		$ddm4_elements['data']['config_value_text']['module']='textarea';
		$ddm4_elements['data']['config_value_text']['title']='Inhalt';
		$ddm4_elements['data']['config_value_text']['name']='config_value_text';
		$ddm4_elements['data']['config_value_text']['options']=[];
		$ddm4_elements['data']['config_value_text']['options']['order']=true;
		$ddm4_elements['data']['config_value_text']['options']['search']=true;
		$ddm4_elements['data']['config_value_text']['options']['required']=true;
		$ddm4_elements['data']['config_value_text']['validation']=[];
		$ddm4_elements['data']['config_value_text']['validation']['length_min']=0;
		$ddm4_elements['data']['config_value_text']['validation']['length_max']=10000;
		$ddm4_elements['data']['config_value_text']['_list']=[];
		$ddm4_elements['data']['config_value_text']['_list']['enabled']=false;

		/*
		 * Data: Type
		 */
		$ddm4_elements['data']['config_type']=[];
		$ddm4_elements['data']['config_type']['module']='hidden';
		$ddm4_elements['data']['config_type']['title']='Typ';
		$ddm4_elements['data']['config_type']['name']='config_type';
		$ddm4_elements['data']['config_type']['options']=[];
		$ddm4_elements['data']['config_type']['options']['default_value']='text';
		$ddm4_elements['data']['config_type']['validation']['length_min']=1;
		$ddm4_elements['data']['config_type']['validation']['length_max']=11;
		$ddm4_elements['data']['config_type']['_view']=[];
		$ddm4_elements['data']['config_type']['_view']['enabled']=false;
		$ddm4_elements['data']['config_type']['_search']=[];
		$ddm4_elements['data']['config_type']['_search']['enabled']=false;
		$ddm4_elements['data']['config_type']['_edit']=[];
		$ddm4_elements['data']['config_type']['_edit']['enabled']=false;
		$ddm4_elements['data']['config_type']['_delete']=[];
		$ddm4_elements['data']['config_type']['_delete']['enabled']=false;
	}

	/*
	 * Variablen [String]
	 */
	if (in_array($ddm_navigation_id, [3])) {

		/*
		 * Data: Inhalt
		 */
		$ddm4_elements['data']['config_value_text']=[];
		$ddm4_elements['data']['config_value_text']['module']='text';
		$ddm4_elements['data']['config_value_text']['title']='Inhalt';
		$ddm4_elements['data']['config_value_text']['name']='config_value_string';
		$ddm4_elements['data']['config_value_text']['options']=[];
		$ddm4_elements['data']['config_value_text']['options']['order']=true;
		$ddm4_elements['data']['config_value_text']['options']['search']=true;
		$ddm4_elements['data']['config_value_text']['options']['required']=true;
		$ddm4_elements['data']['config_value_text']['validation']=[];
		$ddm4_elements['data']['config_value_text']['validation']['length_min']=0;
		$ddm4_elements['data']['config_value_text']['validation']['length_max']=256;

		/*
		 * Data: Type
		 */
		$ddm4_elements['data']['config_type']=[];
		$ddm4_elements['data']['config_type']['module']='hidden';
		$ddm4_elements['data']['config_type']['title']='Typ';
		$ddm4_elements['data']['config_type']['name']='config_type';
		$ddm4_elements['data']['config_type']['options']=[];
		$ddm4_elements['data']['config_type']['options']['default_value']='string';
		$ddm4_elements['data']['config_type']['validation']['length_min']=1;
		$ddm4_elements['data']['config_type']['validation']['length_max']=11;
		$ddm4_elements['data']['config_type']['_view']=[];
		$ddm4_elements['data']['config_type']['_view']['enabled']=false;
		$ddm4_elements['data']['config_type']['_search']=[];
		$ddm4_elements['data']['config_type']['_search']['enabled']=false;
		$ddm4_elements['data']['config_type']['_edit']=[];
		$ddm4_elements['data']['config_type']['_edit']['enabled']=false;
		$ddm4_elements['data']['config_type']['_delete']=[];
		$ddm4_elements['data']['config_type']['_delete']['enabled']=false;
	}

	/*
	 * Variablen [Int]
	 */
	if (in_array($ddm_navigation_id, [4])) {

		/*
		 * Data: Wert
		 */
		$ddm4_elements['data']['config_value_text']=[];
		$ddm4_elements['data']['config_value_text']['module']='text';
		$ddm4_elements['data']['config_value_text']['title']='Wert';
		$ddm4_elements['data']['config_value_text']['name']='config_value_int';
		$ddm4_elements['data']['config_value_text']['options']=[];
		$ddm4_elements['data']['config_value_text']['options']['order']=true;
		$ddm4_elements['data']['config_value_text']['options']['search']=true;
		$ddm4_elements['data']['config_value_text']['options']['required']=true;
		$ddm4_elements['data']['config_value_text']['validation']=[];
		$ddm4_elements['data']['config_value_text']['validation']['module']='integer';
		$ddm4_elements['data']['config_value_text']['validation']['length_min']=0;
		$ddm4_elements['data']['config_value_text']['validation']['length_max']=11;

		/*
		 * Data: Type
		 */
		$ddm4_elements['data']['config_type']=[];
		$ddm4_elements['data']['config_type']['module']='hidden';
		$ddm4_elements['data']['config_type']['title']='Typ';
		$ddm4_elements['data']['config_type']['name']='config_type';
		$ddm4_elements['data']['config_type']['options']=[];
		$ddm4_elements['data']['config_type']['options']['default_value']='int';
		$ddm4_elements['data']['config_type']['validation']['module']='string';
		$ddm4_elements['data']['config_type']['validation']['length_min']=1;
		$ddm4_elements['data']['config_type']['validation']['length_max']=11;
		$ddm4_elements['data']['config_type']['_view']=[];
		$ddm4_elements['data']['config_type']['_view']['enabled']=false;
		$ddm4_elements['data']['config_type']['_search']=[];
		$ddm4_elements['data']['config_type']['_search']['enabled']=false;
		$ddm4_elements['data']['config_type']['_edit']=[];
		$ddm4_elements['data']['config_type']['_edit']['enabled']=false;
		$ddm4_elements['data']['config_type']['_delete']=[];
		$ddm4_elements['data']['config_type']['_delete']['enabled']=false;
	}

	/*
	 * Variablen [Float]
	 */
	if (in_array($ddm_navigation_id, [5])) {

		/*
		 * Data: Wert
		 */
		$ddm4_elements['data']['config_value_text']=[];
		$ddm4_elements['data']['config_value_text']['module']='text';
		$ddm4_elements['data']['config_value_text']['title']='Wert';
		$ddm4_elements['data']['config_value_text']['name']='config_value_float';
		$ddm4_elements['data']['config_value_text']['options']=[];
		$ddm4_elements['data']['config_value_text']['options']['order']=true;
		$ddm4_elements['data']['config_value_text']['options']['search']=true;
		$ddm4_elements['data']['config_value_text']['options']['required']=true;
		$ddm4_elements['data']['config_value_text']['validation']=[];
		$ddm4_elements['data']['config_value_text']['validation']['module']='float';
		$ddm4_elements['data']['config_value_text']['validation']['length_min']=0;
		$ddm4_elements['data']['config_value_text']['validation']['length_max']=11;

		/*
		 * Data: Type
		 */
		$ddm4_elements['data']['config_type']=[];
		$ddm4_elements['data']['config_type']['module']='hidden';
		$ddm4_elements['data']['config_type']['title']='Typ';
		$ddm4_elements['data']['config_type']['name']='config_type';
		$ddm4_elements['data']['config_type']['options']=[];
		$ddm4_elements['data']['config_type']['options']['default_value']='float';
		$ddm4_elements['data']['config_type']['validation']['length_min']=1;
		$ddm4_elements['data']['config_type']['validation']['length_max']=11;
		$ddm4_elements['data']['config_type']['_view']=[];
		$ddm4_elements['data']['config_type']['_view']['enabled']=false;
		$ddm4_elements['data']['config_type']['_search']=[];
		$ddm4_elements['data']['config_type']['_search']['enabled']=false;
		$ddm4_elements['data']['config_type']['_edit']=[];
		$ddm4_elements['data']['config_type']['_edit']['enabled']=false;
		$ddm4_elements['data']['config_type']['_delete']=[];
		$ddm4_elements['data']['config_type']['_delete']['enabled']=false;
	}

	/*
	 * Variablen [Bool]
	 */
	if (in_array($ddm_navigation_id, [6])) {

		/*
		 * Data: Wert
		 */
		$ddm4_elements['data']['config_value_text']=[];
		$ddm4_elements['data']['config_value_text']['module']='yesno';
		$ddm4_elements['data']['config_value_text']['title']='Wert';
		$ddm4_elements['data']['config_value_text']['name']='config_value_bool';
		$ddm4_elements['data']['config_value_text']['options']=[];
		$ddm4_elements['data']['config_value_text']['options']['order']=true;
		$ddm4_elements['data']['config_value_text']['options']['search']=true;
		$ddm4_elements['data']['config_value_text']['options']['required']=true;
		$ddm4_elements['data']['config_value_text']['validation']=[];
		$ddm4_elements['data']['config_value_text']['validation']['module']='integer';
		$ddm4_elements['data']['config_value_text']['validation']['length_min']=1;
		$ddm4_elements['data']['config_value_text']['validation']['length_max']=1;

		/*
		 * Data: Type
		 */
		$ddm4_elements['data']['config_type']=[];
		$ddm4_elements['data']['config_type']['module']='hidden';
		$ddm4_elements['data']['config_type']['title']='Typ';
		$ddm4_elements['data']['config_type']['name']='config_type';
		$ddm4_elements['data']['config_type']['options']=[];
		$ddm4_elements['data']['config_type']['options']['default_value']='bool';
		$ddm4_elements['data']['config_type']['validation']['length_min']=1;
		$ddm4_elements['data']['config_type']['validation']['length_max']=11;
		$ddm4_elements['data']['config_type']['_view']=[];
		$ddm4_elements['data']['config_type']['_view']['enabled']=false;
		$ddm4_elements['data']['config_type']['_search']=[];
		$ddm4_elements['data']['config_type']['_search']['enabled']=false;
		$ddm4_elements['data']['config_type']['_edit']=[];
		$ddm4_elements['data']['config_type']['_edit']['enabled']=false;
		$ddm4_elements['data']['config_type']['_delete']=[];
		$ddm4_elements['data']['config_type']['_delete']['enabled']=false;
	}

	/*
	 * Data: Aktiviert
	 */
	$ddm4_elements['data']['config_ispublic']=[];
	$ddm4_elements['data']['config_ispublic']['module']='yesno';
	$ddm4_elements['data']['config_ispublic']['title']='Aktiviert';
	$ddm4_elements['data']['config_ispublic']['name']='config_ispublic';
	$ddm4_elements['data']['config_ispublic']['options']=[];
	$ddm4_elements['data']['config_ispublic']['options']['required']=true;
	$ddm4_elements['data']['config_ispublic']['options']['default_value']=1;
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
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='config_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='config_';

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