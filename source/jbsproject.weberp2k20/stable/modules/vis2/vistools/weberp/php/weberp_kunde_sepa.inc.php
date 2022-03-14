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

if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck', 'createpdfemail', 'downloadpdfdruck', 'downloadpdfemail', 'sendpdfemail'])) {
	$current_sepa=new \JBSNewMedia\WebERP\Sepa($VIS2_Mandant->getId(), \osWFrame\Core\Settings::catchStringValue('sepa_id', 0, 'g'));
	$current_sepa->setVerwaltung($VIS2_WebERP_Verwaltung);

	if (in_array(\osWFrame\Core\Settings::getAction(), ['downloadpdfdruck', 'downloadpdfemail'])) {
		if ($current_sepa->getLoaded()===true) {
			if (in_array(\osWFrame\Core\Settings::getAction(), ['downloadpdfdruck'])) {
				$file=$current_sepa->getPath().'sepa_basis_'.$current_sepa->getKundeValue('kunde_nr').'_'.$current_sepa->getDetailValue('sepa_id').'_print.pdf';
			} else {
				$file=$current_sepa->getPath().'sepa_basis_'.$current_sepa->getKundeValue('kunde_nr').'_'.$current_sepa->getDetailValue('sepa_id').'.pdf';
			}
			\osWFrame\Core\Network::diePDF($file);
		}
		\osWFrame\Core\Settings::dieScript();
	}

	if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck', 'createpdfemail', 'sendpdfemail'])) {
		if ($current_sepa->getLoaded()===true) {
			$PDFSepa=new \JBSNewMedia\WebERP\PDF();
			if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck'])) {
				$PDFSepa->setJBSPrint(true);
			}
			$PDFSepa->setVerwaltung($VIS2_WebERP_Verwaltung);
			$PDFSepa->setKundeDetails($current_sepa->getKunde());
			$PDFSepa->setKontoDetails($current_sepa->getKonto());
			$PDFSepa->setSepaDetails($current_sepa->getDetails());

			if ($PDFSepa->generateSepa()!==true) {
				$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
				\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Kann PDF nicht erzeugen.', 'error', [], false));
			}

			if ($PDFSepa->getJBSPrint()===true) {
				$file=$current_sepa->getPath().'sepa_basis_'.$current_sepa->getKundeValue('kunde_nr').'_'.$current_sepa->getDetailValue('sepa_id').'_print.pdf';
			} else {
				$file=$current_sepa->getPath().'sepa_basis_'.$current_sepa->getKundeValue('kunde_nr').'_'.$current_sepa->getDetailValue('sepa_id').'.pdf';
			}
			$PDFSepa->Output($file, 'F');

			if (in_array(\osWFrame\Core\Settings::getAction(), ['sendpdfemail'])) {
				$mail=new \JBSNewMedia\WebERP\Mail($VIS2_WebERP_Verwaltung);
				$mail->setKundeDetails($current_sepa->getKunde());
				$mail->setKontoDetails($current_sepa->getKonto());
				$mail->setSepaDetails($current_sepa->getDetails());
				$mail->addAttachment($file);
				if ($mail->sendSepa()===true) {
					$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
					\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('SEPA-Basis-Lastschriftmandat per E-Mail versendet.', 'success', [], false));
				}
				$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
				\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode($mail->getErrorMessage(), 'error', [], false));
			} else {
				$url=$osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&action='.str_replace('create', 'download', \osWFrame\Core\Settings::getAction()).'&sepa_id='.$current_sepa->getSepaId(), false);
				\osWFrame\Core\Settings::dieScript('window.open("'.$url.'", "_blank");');
			}
		} else {
			$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
			\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('SEPA-Basis-Lastschriftmandat nicht gefunden.', 'error', [], false));
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
$ddm4_object['messages']['data_noresults']='Keine SEPA-Basis-Lastschriftmandate vorhanden';
$ddm4_object['messages']['search_title']='SEPA-Basis-Lastschriftmandate durchsuchen';
$ddm4_object['messages']['add_title']='Neues SEPA-Basis-Lastschriftmandat anlegen';
$ddm4_object['messages']['add_success_title']='SEPA-Basis-Lastschriftmandat wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='SEPA-Basis-Lastschriftmandat konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='SEPA-Basis-Lastschriftmandat editieren';
$ddm4_object['messages']['edit_load_error_title']='SEPA-Basis-Lastschriftmandat wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='SEPA-Basis-Lastschriftmandat wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='SEPA-Basis-Lastschriftmandat konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='SEPA-Basis-Lastschriftmandat löschen';
$ddm4_object['messages']['delete_load_error_title']='SEPA-Basis-Lastschriftmandat wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='SEPA-Basis-Lastschriftmandat wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='SEPA-Basis-Lastschriftmandat konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_kunde_sepa';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='sepa_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['sepa_id']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_kunde_sepa', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(sepa_id) AS counter FROM :table_weberp_kunde_sepa: WHERE mandant_id=:mandant_id: AND sepa_ispublic=:sepa_ispublic:');
$QselectCount->bindTable(':table_weberp_kunde_sepa:', 'weberp_kunde_sepa');
$QselectCount->bindInt(':sepa_ispublic:', 1);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Aktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(sepa_id) AS counter FROM :table_weberp_kunde_sepa: WHERE mandant_id=:mandant_id: AND sepa_ispublic=:sepa_ispublic:');
$QselectCount->bindTable(':table_weberp_kunde_sepa:', 'weberp_kunde_sepa');
$QselectCount->bindInt(':sepa_ispublic:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Inaktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(sepa_id) AS counter FROM :table_weberp_kunde_sepa: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_kunde_sepa:', 'weberp_kunde_sepa');
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
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'sepa_ispublic', 'operator'=>'=', 'value'=>1], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'sepa_ispublic', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['sepa_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);
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
 * Data: Konto
 */
$ddm4_elements['data']['konto_id']=[];
$ddm4_elements['data']['konto_id']['module']='select';
$ddm4_elements['data']['konto_id']['title']='Konto';
$ddm4_elements['data']['konto_id']['name']='konto_id';
$ddm4_elements['data']['konto_id']['options']=[];
$ddm4_elements['data']['konto_id']['options']['order']=true;
$ddm4_elements['data']['konto_id']['options']['required']=true;
$ddm4_elements['data']['konto_id']['options']['search']=true;
$ddm4_elements['data']['konto_id']['options']['data']=$VIS2_WebERP_Verwaltung->getKundenKonten();
$ddm4_elements['data']['konto_id']['validation']=[];
$ddm4_elements['data']['konto_id']['validation']['module']='integer';
$ddm4_elements['data']['konto_id']['validation']['length_min']=1;
$ddm4_elements['data']['konto_id']['validation']['length_max']=11;
$ddm4_elements['data']['konto_id']['validation']['search_like']=false;

/*
 * Data: SEPA Mandat
 */
$ddm4_elements['data']['sepa_mandat']=[];
$ddm4_elements['data']['sepa_mandat']['module']='text';
$ddm4_elements['data']['sepa_mandat']['title']='SEPA Mandat';
$ddm4_elements['data']['sepa_mandat']['name']='sepa_mandat';
$ddm4_elements['data']['sepa_mandat']['options']=[];
$ddm4_elements['data']['sepa_mandat']['options']['order']=true;
$ddm4_elements['data']['sepa_mandat']['options']['required']=true;
$ddm4_elements['data']['sepa_mandat']['options']['search']=true;
$ddm4_elements['data']['sepa_mandat']['validation']=[];
$ddm4_elements['data']['sepa_mandat']['validation']['length_min']=0;
$ddm4_elements['data']['sepa_mandat']['validation']['length_max']=64;

/*
 * Data: SEPA Erste
 */
$ddm4_elements['data']['sepa_erste']=[];
$ddm4_elements['data']['sepa_erste']['module']='datepicker';
$ddm4_elements['data']['sepa_erste']['title']='SEPA Erste';
$ddm4_elements['data']['sepa_erste']['name']='sepa_erste';
$ddm4_elements['data']['sepa_erste']['options']=[];
$ddm4_elements['data']['sepa_erste']['options']['order']=true;

/*
 * Data: SEPA Letzte
 */
$ddm4_elements['data']['sepa_letzte']=[];
$ddm4_elements['data']['sepa_letzte']['module']='datepicker';
$ddm4_elements['data']['sepa_letzte']['title']='SEPA Letzte';
$ddm4_elements['data']['sepa_letzte']['name']='sepa_letzte';
$ddm4_elements['data']['sepa_letzte']['options']=[];
$ddm4_elements['data']['sepa_letzte']['options']['order']=true;

/*
 * Data: Aktiviert
 */
$ddm4_elements['data']['sepa_ispublic']=[];
$ddm4_elements['data']['sepa_ispublic']['module']='yesno';
$ddm4_elements['data']['sepa_ispublic']['title']='Aktiviert';
$ddm4_elements['data']['sepa_ispublic']['name']='sepa_ispublic';
$ddm4_elements['data']['sepa_ispublic']['options']=[];
$ddm4_elements['data']['sepa_ispublic']['options']['order']=true;
$ddm4_elements['data']['sepa_ispublic']['options']['required']=true;
$ddm4_elements['data']['sepa_ispublic']['options']['default_value']=1;

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='sepa_';
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
$ddm4_elements['data']['options']['options']['links']['2']=[];
$ddm4_elements['data']['options']['options']['links']['2']['module']=$osW_DDM4->getDirectModule();
$ddm4_elements['data']['options']['options']['links']['2']['parameter']='action=sendpdfemail&'.$osW_DDM4->getDirectParameters();
$ddm4_elements['data']['options']['options']['links']['2']['notify']=true;
$ddm4_elements['data']['options']['options']['links']['2']['text']='Senden (E-Mail)';
$ddm4_elements['data']['options']['options']['links']['2']['content']='<i class="fas fa-envelope fa-fw"></i>';

/*
* Finish: VIS2_Store_Form_Data
*/
$ddm4_elements['finish']['vis2_store_form_data']=[];
$ddm4_elements['finish']['vis2_store_form_data']['module']='vis2_store_form_data';
$ddm4_elements['finish']['vis2_store_form_data']['options']=[];
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='sepa_';

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