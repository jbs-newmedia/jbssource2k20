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

if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck', 'createpdfemail', 'downloadpdfdruck', 'downloadpdfemail'])) {
	$current_schreiben=new \JBSNewMedia\WebERP\Schreiben($VIS2_Mandant->getId(), \osWFrame\Core\Settings::catchIntValue('schreiben_id'));
	$current_schreiben->setUserId($VIS2_User->getId());
	$current_schreiben->setVerwaltung($VIS2_WebERP_Verwaltung);

	if (in_array(\osWFrame\Core\Settings::getAction(), ['downloadpdfdruck', 'downloadpdfemail'])) {
		if ($current_schreiben->getLoaded()===true) {
			if (in_array(\osWFrame\Core\Settings::getAction(), ['downloadpdfdruck'])) {
				$file=$current_schreiben->getPath().'schreiben_'.md5($current_schreiben->getDetailValue('schreiben_titel')).'_'.$current_schreiben->getSchreibenId().'_print.pdf';
			} else {
				$file=$current_schreiben->getPath().'schreiben_'.md5($current_schreiben->getDetailValue('schreiben_titel')).'_'.$current_schreiben->getSchreibenId().'.pdf';
			}
			\osWFrame\Core\Network::diePDF($file);
		}
		\osWFrame\Core\Settings::dieScript();
	}

	if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck', 'createpdfemail'])) {
		if ($current_schreiben->getLoaded()===true) {
			$PDFSchreiben=new \JBSNewMedia\WebERP\PDF();
			$PDFSchreiben_pages=new \JBSNewMedia\WebERP\PDF();
			if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck'])) {
				$PDFSchreiben->setJBSPrint(true);
				$PDFSchreiben_pages->setJBSPrint(true);
			}

			$PDFSchreiben->setVerwaltung($VIS2_WebERP_Verwaltung);
			$PDFSchreiben_pages->setVerwaltung($VIS2_WebERP_Verwaltung);
			$PDFSchreiben->setSchreibenDetails($current_schreiben->getDetails());
			$PDFSchreiben_pages->setSchreibenDetails($current_schreiben->getDetails());

			if ($PDFSchreiben_pages->generateSchreiben()!==true) {
				$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
				\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Kann PDF nicht erzeugen.', 'error', [], false));
			}

			$PDFSchreiben->setJBSPages($PDFSchreiben_pages->getJBSPage());

			if ($PDFSchreiben->generateSchreiben()!==true) {
				$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
				\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Kann PDF nicht erzeugen.', 'error', [], false));
			}

			if ($PDFSchreiben->getJBSPrint()===true) {
				$file=$current_schreiben->getPath().'schreiben_'.md5($current_schreiben->getDetailValue('schreiben_titel')).'_'.$current_schreiben->getSchreibenId().'_print.pdf';
			} else {
				$file=$current_schreiben->getPath().'schreiben_'.md5($current_schreiben->getDetailValue('schreiben_titel')).'_'.$current_schreiben->getSchreibenId().'.pdf';
			}

			$PDFSchreiben->Output($file, 'F');

			$url=$osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&action='.str_replace('create', 'download', \osWFrame\Core\Settings::getAction()).'&schreiben_id='.$current_schreiben->getSchreibenId(), false);
			\osWFrame\Core\Settings::dieScript('window.open("'.$url.'", "_blank");');
		} else {
			$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
			\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Schreiben nicht gefunden.', 'error', [], false));
		}
	}
}

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
$ddm4_object['messages']['data_noresults']='Keine Schreiben vorhanden';
$ddm4_object['messages']['search_title']='Schreiben durchsuchen';
$ddm4_object['messages']['add_title']='Neues Schreiben anlegen';
$ddm4_object['messages']['add_success_title']='Schreiben wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Schreiben konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Schreiben editieren';
$ddm4_object['messages']['edit_load_error_title']='Schreiben wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Schreiben wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Schreiben konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Schreiben löschen';
$ddm4_object['messages']['delete_load_error_title']='Schreiben wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Schreiben wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Schreiben konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_schreiben';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='schreiben_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['schreiben_datum']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_schreiben', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(schreiben_id) AS counter FROM :table_weberp_schreiben: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_schreiben:', 'weberp_schreiben');
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Alle', 'counter'=>clone $QselectCount];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [1])) {
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
 * Data: Adresse Zeile #1
 */
$ddm4_elements['data']['schreiben_zeile_1']=[];
$ddm4_elements['data']['schreiben_zeile_1']['module']='text';
$ddm4_elements['data']['schreiben_zeile_1']['title']='Adresse Zeile #1';
$ddm4_elements['data']['schreiben_zeile_1']['name']='schreiben_zeile_1';
$ddm4_elements['data']['schreiben_zeile_1']['options']=[];
$ddm4_elements['data']['schreiben_zeile_1']['options']['order']=true;
$ddm4_elements['data']['schreiben_zeile_1']['validation']=[];
$ddm4_elements['data']['schreiben_zeile_1']['validation']['length_min']=0;
$ddm4_elements['data']['schreiben_zeile_1']['validation']['length_max']=128;
$ddm4_elements['data']['schreiben_zeile_1']['_list']=[];
$ddm4_elements['data']['schreiben_zeile_1']['_list']['enabled']=false;
$ddm4_elements['data']['schreiben_zeile_1']['_search']=[];
$ddm4_elements['data']['schreiben_zeile_1']['_search']['enabled']=false;

/*
 * Data: Adresse Zeile #2
 */
$ddm4_elements['data']['schreiben_zeile_2']=[];
$ddm4_elements['data']['schreiben_zeile_2']['module']='text';
$ddm4_elements['data']['schreiben_zeile_2']['title']='Adresse Zeile #2';
$ddm4_elements['data']['schreiben_zeile_2']['name']='schreiben_zeile_2';
$ddm4_elements['data']['schreiben_zeile_2']['options']=[];
$ddm4_elements['data']['schreiben_zeile_2']['options']['order']=true;
$ddm4_elements['data']['schreiben_zeile_2']['validation']=[];
$ddm4_elements['data']['schreiben_zeile_2']['validation']['length_min']=0;
$ddm4_elements['data']['schreiben_zeile_2']['validation']['length_max']=128;
$ddm4_elements['data']['schreiben_zeile_2']['_list']=[];
$ddm4_elements['data']['schreiben_zeile_2']['_list']['enabled']=false;
$ddm4_elements['data']['schreiben_zeile_2']['_search']=[];
$ddm4_elements['data']['schreiben_zeile_2']['_search']['enabled']=false;

/*
 * Data: Adresse Zeile #3
 */
$ddm4_elements['data']['schreiben_zeile_3']=[];
$ddm4_elements['data']['schreiben_zeile_3']['module']='text';
$ddm4_elements['data']['schreiben_zeile_3']['title']='Adresse Zeile #3';
$ddm4_elements['data']['schreiben_zeile_3']['name']='schreiben_zeile_3';
$ddm4_elements['data']['schreiben_zeile_3']['options']=[];
$ddm4_elements['data']['schreiben_zeile_3']['options']['order']=true;
$ddm4_elements['data']['schreiben_zeile_3']['validation']=[];
$ddm4_elements['data']['schreiben_zeile_3']['validation']['length_min']=0;
$ddm4_elements['data']['schreiben_zeile_3']['validation']['length_max']=128;
$ddm4_elements['data']['schreiben_zeile_3']['_list']=[];
$ddm4_elements['data']['schreiben_zeile_3']['_list']['enabled']=false;
$ddm4_elements['data']['schreiben_zeile_3']['_search']=[];
$ddm4_elements['data']['schreiben_zeile_3']['_search']['enabled']=false;

/*
 * Data: Adresse Zeile #4
 */
$ddm4_elements['data']['schreiben_zeile_4']=[];
$ddm4_elements['data']['schreiben_zeile_4']['module']='text';
$ddm4_elements['data']['schreiben_zeile_4']['title']='Adresse Zeile #4';
$ddm4_elements['data']['schreiben_zeile_4']['name']='schreiben_zeile_4';
$ddm4_elements['data']['schreiben_zeile_4']['options']=[];
$ddm4_elements['data']['schreiben_zeile_4']['options']['order']=true;
$ddm4_elements['data']['schreiben_zeile_4']['validation']=[];
$ddm4_elements['data']['schreiben_zeile_4']['validation']['length_min']=0;
$ddm4_elements['data']['schreiben_zeile_4']['validation']['length_max']=128;
$ddm4_elements['data']['schreiben_zeile_4']['_list']=[];
$ddm4_elements['data']['schreiben_zeile_4']['_list']['enabled']=false;
$ddm4_elements['data']['schreiben_zeile_4']['_search']=[];
$ddm4_elements['data']['schreiben_zeile_4']['_search']['enabled']=false;

/*
 * Data: Datum
 */
$ddm4_elements['data']['schreiben_datum']=[];
$ddm4_elements['data']['schreiben_datum']['module']='datepicker';
$ddm4_elements['data']['schreiben_datum']['title']='Datum';
$ddm4_elements['data']['schreiben_datum']['name']='schreiben_datum';
$ddm4_elements['data']['schreiben_datum']['options']=[];
$ddm4_elements['data']['schreiben_datum']['options']['order']=true;
$ddm4_elements['data']['schreiben_datum']['options']['required']=true;
$ddm4_elements['data']['schreiben_datum']['options']['year_min']=\JBSNewMedia\WebERP\Verwaltung::getBeginningYear();
$ddm4_elements['data']['schreiben_datum']['options']['default_value']=date('Ymd');

/*
 * Data: Titel
 */
$ddm4_elements['data']['schreiben_titel']=[];
$ddm4_elements['data']['schreiben_titel']['module']='text';
$ddm4_elements['data']['schreiben_titel']['title']='Titel';
$ddm4_elements['data']['schreiben_titel']['name']='schreiben_titel';
$ddm4_elements['data']['schreiben_titel']['options']=[];
$ddm4_elements['data']['schreiben_titel']['options']['order']=true;
$ddm4_elements['data']['schreiben_titel']['validation']=[];
$ddm4_elements['data']['schreiben_titel']['validation']['length_min']=0;
$ddm4_elements['data']['schreiben_titel']['validation']['length_max']=128;

/*
 * Data: Text
 */
$ddm4_elements['data']['schreiben_text']=[];
$ddm4_elements['data']['schreiben_text']['module']='textarea';
$ddm4_elements['data']['schreiben_text']['title']='Text';
$ddm4_elements['data']['schreiben_text']['name']='schreiben_text';

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='schreiben_';
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
$ddm4_elements['data']['options']['options']=[];
$ddm4_elements['data']['options']['options']['links']=[];
$ddm4_elements['data']['options']['options']['links']['0']=[];
$ddm4_elements['data']['options']['options']['links']['0']['module']=$osW_DDM4->getDirectModule();
$ddm4_elements['data']['options']['options']['links']['0']['parameter']='action=createpdfdruck&'.$osW_DDM4->getDirectParameters();
$ddm4_elements['data']['options']['options']['links']['0']['notify']=true;
$ddm4_elements['data']['options']['options']['links']['0']['text']='Download (Druck)';
$ddm4_elements['data']['options']['options']['links']['0']['content']='<i class="fas fa-print fa-fw"></i>';
$ddm4_elements['data']['options']['options']['links']['1']=[];
$ddm4_elements['data']['options']['options']['links']['1']['module']=$osW_DDM4->getDirectModule();
$ddm4_elements['data']['options']['options']['links']['1']['parameter']='action=createpdfemail&'.$osW_DDM4->getDirectParameters();
$ddm4_elements['data']['options']['options']['links']['1']['notify']=true;
$ddm4_elements['data']['options']['options']['links']['1']['text']='Download (E-Mail)';
$ddm4_elements['data']['options']['options']['links']['1']['content']='<i class="fas fa-download fa-fw"></i>';

/*
 * Finish: VIS2_Store_Form_Data
 */
$ddm4_elements['finish']['vis2_store_form_data']=[];
$ddm4_elements['finish']['vis2_store_form_data']['module']='vis2_store_form_data';
$ddm4_elements['finish']['vis2_store_form_data']['options']=[];
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='schreiben_';

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

/*
/**
 * DDM4-Objekt Runtime
 */
$osW_DDM4->runDDMPHP();

/**
 * DDM4-Objekt an Template übergeben
 */
$osW_Template->setVar('osW_DDM4', $osW_DDM4);

?>