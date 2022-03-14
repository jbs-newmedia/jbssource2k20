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
	$current_rechnung=new \JBSNewMedia\WebERP\Rechnung($VIS2_Mandant->getId(), \osWFrame\Core\Settings::catchIntValue('rechnung_id'));
	$current_rechnung->setUserId($VIS2_User->getId());
	$current_rechnung->setVerwaltung($VIS2_WebERP_Verwaltung);

	if (in_array(\osWFrame\Core\Settings::getAction(), ['downloadpdfdruck', 'downloadpdfemail'])) {
		if ($current_rechnung->getLoaded()===true) {
			if (in_array(\osWFrame\Core\Settings::getAction(), ['downloadpdfdruck'])) {
				$file=$current_rechnung->getPath().'rechnung_'.$current_rechnung->getKundeValue('kunde_nr').'_'.$current_rechnung->getDetailValue('rechnung_nr').'_print.pdf';
			} else {
				$file=$current_rechnung->getPath().'rechnung_'.$current_rechnung->getKundeValue('kunde_nr').'_'.$current_rechnung->getDetailValue('rechnung_nr').'.pdf';
			}
			\osWFrame\Core\Network::diePDF($file);
		}
		\osWFrame\Core\Settings::dieScript();
	}

	if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck', 'createpdfemail', 'sendpdfemail'])) {
		if ($current_rechnung->getLoaded()===true) {
			$PDFRechnung=new \JBSNewMedia\WebERP\PDF();
			$PDFRechnung_pages=new \JBSNewMedia\WebERP\PDF();
			if (in_array(\osWFrame\Core\Settings::getAction(), ['createpdfdruck'])) {
				$PDFRechnung->setJBSPrint(true);
				$PDFRechnung_pages->setJBSPrint(true);
			}

			$PDFRechnung->setVerwaltung($VIS2_WebERP_Verwaltung);
			$PDFRechnung_pages->setVerwaltung($VIS2_WebERP_Verwaltung);
			$PDFRechnung->setKundeDetails($current_rechnung->getKunde());
			$PDFRechnung_pages->setKundeDetails($current_rechnung->getKunde());
			$PDFRechnung->setVorgangDetails($current_rechnung->getDetails());
			$PDFRechnung_pages->setVorgangDetails($current_rechnung->getDetails());
			$PDFRechnung->setPositionenDetails($current_rechnung->getPositionen());
			$PDFRechnung_pages->setPositionenDetails($current_rechnung->getPositionen());

			if ($PDFRechnung_pages->generateRechnung()!==true) {
				$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
				\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Kann PDF nicht erzeugen.', 'error', [], false));
			}

			$PDFRechnung->setJBSPages($PDFRechnung_pages->getJBSPage());

			if ($PDFRechnung->generateRechnung()!==true) {
				$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
				\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Kann PDF nicht erzeugen.', 'error', [], false));
			}

			if ($PDFRechnung->getJBSPrint()===true) {
				$file=$current_rechnung->getPath().'rechnung_'.$current_rechnung->getKundeValue('kunde_nr').'_'.$current_rechnung->getDetailValue('rechnung_nr').'_print.pdf';
			} else {
				$file=$current_rechnung->getPath().'rechnung_'.$current_rechnung->getKundeValue('kunde_nr').'_'.$current_rechnung->getDetailValue('rechnung_nr').'.pdf';
			}

			$PDFRechnung->Output($file, 'F');

			if (in_array(\osWFrame\Core\Settings::getAction(), ['sendpdfemail'])) {
				if ($current_rechnung->getDetailValue('rechnung_gesendet')==1) {
					$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
					\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Rechnung wurde bereits versendet.', 'error', [], false));
				}
				$mail=new \JBSNewMedia\WebERP\Mail($VIS2_WebERP_Verwaltung);
				$mail->setKundeDetails($current_rechnung->getKunde());
				$mail->setVorgangDetails($current_rechnung->getDetails());
				$mail->addAttachment($file);
				if ($mail->sendRechnung()===true) {
					$current_rechnung->updateIntValue('rechnung_gesendet', 1, $VIS2_User->getId());
					$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
					\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Rechnung per E-Mail versendet.', 'success', [], false));

				}
				$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
				\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode($mail->getErrorMessage(), 'error', [], false));
			} else {
				$url=$osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage().'&action='.str_replace('create', 'download', \osWFrame\Core\Settings::getAction()).'&rechnung_id='.$current_rechnung->getRechnungId(), false);
				\osWFrame\Core\Settings::dieScript('window.open("'.$url.'", "_blank");');
			}
		} else {
			$osW_Bootstrap5_Notify=new \osWFrame\Core\Bootstrap5_Notify($osW_Template);
			\osWFrame\Core\Settings::dieScript($osW_Bootstrap5_Notify->getNotifyCode('Rechnung nicht gefunden.', 'error', [], false));
		}
	}
}

if (in_array(\osWFrame\Core\Settings::getAction(), ['add'])) {
	$current_angebot=new \JBSNewMedia\WebERP\Angebot(\osWFrame\Core\Settings::catchIntValue('preload_id'));
	if ($current_angebot->getAngebotId()>0) {
		$angebot_details=$current_angebot->getDetails();
		$_POST['kunde_id']=$angebot_details['kunde_id'];
		foreach (['kunde_id', 'angebot_nr', 'angebot_datum', 'angebot_leistung_von', 'angebot_leistung_bis', 'angebot_bezahlt', 'angebot_storniert', 'angebot_gesendet', 'angebot_gesamt_brutto', 'angebot_gesamt_netto', 'angebot_gesamt_mwst', 'angebot_create_time', 'angebot_create_user_id', 'angebot_update_time', 'angebot_update_user_id'] as $element) {
			unset($angebot_details[$element]);
		}

		foreach ($angebot_details as $key=>$value) {
			$_POST['rechnung_'.substr($key, 8)]=$value;
		}

		foreach ($current_angebot->getPositionen() as $element) {
			$_POST['position_artikel_'.$element['position_pos'].'_anzahl']=$element['position_artikel_anzahl'];
			$_POST['position_artikel_'.$element['position_pos'].'_id']=intval($element['position_artikel_id']);
			$_POST['position_artikel_'.$element['position_pos'].'_zusatz']=$element['position_artikel_zusatz'];
			$_POST['position_artikel_'.$element['position_pos'].'_nr']=$element['position_artikel_nr'];
			$_POST['position_artikel_'.$element['position_pos'].'_kurz']=$element['position_artikel_kurz'];
			$_POST['position_artikel_'.$element['position_pos'].'_beschreibung']=$element['position_artikel_beschreibung'];
			$_POST['position_artikel_'.$element['position_pos'].'_beschreibung_ausblenden']=$element['position_artikel_beschreibung_ausblenden'];
			$_POST['position_artikel_'.$element['position_pos'].'_preis']=$element['position_artikel_preis'];
			$_POST['position_artikel_'.$element['position_pos'].'_typ']=$element['position_artikel_typ'];
			$_POST['position_artikel_'.$element['position_pos'].'_mwst']=$element['position_artikel_mwst'];
		}
	}
}

if (\osWFrame\Core\Settings::getAction()=='getKundeDetails') {
	\osWFrame\Core\Network::dieJSON($VIS2_WebERP_Verwaltung->getKundeById(\osWFrame\Core\Settings::catchIntValue('kunde_id')));
}

if (\osWFrame\Core\Settings::getAction()=='getArtikelDetails') {
	\osWFrame\Core\Network::dieJSON($VIS2_WebERP_Verwaltung->getArtikelById(\osWFrame\Core\Settings::catchIntValue('artikel_id')));
}

$element_values_all=['anzahl', 'zusatz', 'beschreibung', 'beschreibung_ausblenden', 'preis', 'typ', 'mwst', 'nr', 'kurz',];
$element_values=['header', 'id',];

$rechnung_kunde_nr=0;
if (in_array(\osWFrame\Core\Settings::getAction(), ['edit', 'doedit'])) {
	$current_rechnung=new \JBSNewMedia\WebERP\Rechnung($VIS2_Mandant->getId(), \osWFrame\Core\Settings::catchIntValue('rechnung_id'));
} else {
	$current_rechnung=new \JBSNewMedia\WebERP\Rechnung($VIS2_Mandant->getId());
}
$current_rechnung->setUserId($VIS2_User->getId());

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
$ddm4_object['messages']['data_noresults']='Keine Rechnungen vorhanden';
$ddm4_object['messages']['search_title']='Rechnungen durchsuchen';
$ddm4_object['messages']['add_title']='Neue Rechnung anlegen';
$ddm4_object['messages']['add_success_title']='Rechnung wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Rechnung konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Rechnung editieren';
$ddm4_object['messages']['edit_load_error_title']='Rechnung wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Rechnung wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Rechnung konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Rechnung löschen';
$ddm4_object['messages']['delete_load_error_title']='Rechnung wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Rechnung wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Rechnung konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_rechnung';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='rechnung_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['rechnung_nr']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_rechnung', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(rechnung_id) AS counter FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_bezahlt=:rechnung_bezahlt: AND rechnung_storniert=:rechnung_storniert:');
$QselectCount->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
$QselectCount->bindInt(':rechnung_bezahlt:', 0);
$QselectCount->bindInt(':rechnung_storniert:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Offen', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(rechnung_id) AS counter FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_bezahlt=:rechnung_bezahlt: AND rechnung_storniert=:rechnung_storniert:');
$QselectCount->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
$QselectCount->bindInt(':rechnung_bezahlt:', 1);
$QselectCount->bindInt(':rechnung_storniert:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Bezahlt', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(rechnung_id) AS counter FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id: AND rechnung_storniert=:rechnung_storniert:');
$QselectCount->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
$QselectCount->bindInt(':rechnung_storniert:', 1);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Storniert', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(rechnung_id) AS counter FROM :table_weberp_rechnung: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_rechnung:', 'weberp_rechnung');
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[4]=['navigation_id'=>4, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Alle', 'counter'=>clone $QselectCount];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [1])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'rechnung_bezahlt', 'operator'=>'=', 'value'=>0], ['key'=>'rechnung_storniert', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'rechnung_bezahlt', 'operator'=>'=', 'value'=>1], ['key'=>'rechnung_storniert', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'rechnung_storniert', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [4])) {
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
 * Data: ReNr
 */
$ddm4_elements['data']['rechnung_nr']=[];
$ddm4_elements['data']['rechnung_nr']['module']='autovalue';
$ddm4_elements['data']['rechnung_nr']['title']='ReNr';
$ddm4_elements['data']['rechnung_nr']['name']='rechnung_nr';
$ddm4_elements['data']['rechnung_nr']['options']=[];
$ddm4_elements['data']['rechnung_nr']['options']['order']=true;
$ddm4_elements['data']['rechnung_nr']['options']['search']=true;
$ddm4_elements['data']['rechnung_nr']['options']['label']='wird automatisch vergeben';
$ddm4_elements['data']['rechnung_nr']['options']['default_value']=((date('y')*1000)+1);
$ddm4_elements['data']['rechnung_nr']['options']['filter_use']=false;
$ddm4_elements['data']['rechnung_nr']['validation']=[];
$ddm4_elements['data']['rechnung_nr']['validation']['length_min']=5;
$ddm4_elements['data']['rechnung_nr']['validation']['length_max']=5;
$ddm4_elements['data']['rechnung_nr']['validation']['search_like']=false;

/*
 * Data: Datum
 */
$ddm4_elements['data']['rechnung_datum']=[];
$ddm4_elements['data']['rechnung_datum']['module']='datepicker';
$ddm4_elements['data']['rechnung_datum']['title']='Datum';
$ddm4_elements['data']['rechnung_datum']['name']='rechnung_datum';
$ddm4_elements['data']['rechnung_datum']['options']=[];
$ddm4_elements['data']['rechnung_datum']['options']['order']=true;
$ddm4_elements['data']['rechnung_datum']['options']['required']=true;
$ddm4_elements['data']['rechnung_datum']['options']['year_min']=\JBSNewMedia\WebERP\Verwaltung::getBeginningYear();
$ddm4_elements['data']['rechnung_datum']['options']['default_value']=date('Ymd');

/*
 * Data: Leistung von
 */
$ddm4_elements['data']['rechnung_leistung_von']=[];
$ddm4_elements['data']['rechnung_leistung_von']['module']='datepicker';
$ddm4_elements['data']['rechnung_leistung_von']['title']='Leistung von';
$ddm4_elements['data']['rechnung_leistung_von']['name']='rechnung_leistung_von';
$ddm4_elements['data']['rechnung_leistung_von']['options']=[];
$ddm4_elements['data']['rechnung_leistung_von']['options']['order']=true;
$ddm4_elements['data']['rechnung_leistung_von']['options']['required']=true;
$ddm4_elements['data']['rechnung_leistung_von']['options']['year_min']=\JBSNewMedia\WebERP\Verwaltung::getBeginningYear();
$ddm4_elements['data']['rechnung_leistung_von']['options']['default_value']=date('Ymd');
$ddm4_elements['data']['rechnung_leistung_von']['_list']=[];
$ddm4_elements['data']['rechnung_leistung_von']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_leistung_von']['_search']=[];
$ddm4_elements['data']['rechnung_leistung_von']['_search']['enabled']=false;

/*
 * Data: Leistung bis
 */
$ddm4_elements['data']['rechnung_leistung_bis']=[];
$ddm4_elements['data']['rechnung_leistung_bis']['module']='datepicker';
$ddm4_elements['data']['rechnung_leistung_bis']['title']='Leistung bis';
$ddm4_elements['data']['rechnung_leistung_bis']['name']='rechnung_leistung_bis';
$ddm4_elements['data']['rechnung_leistung_bis']['options']=[];
$ddm4_elements['data']['rechnung_leistung_bis']['options']['order']=true;
$ddm4_elements['data']['rechnung_leistung_bis']['options']['required']=true;
$ddm4_elements['data']['rechnung_leistung_bis']['options']['year_min']=\JBSNewMedia\WebERP\Verwaltung::getBeginningYear();
$ddm4_elements['data']['rechnung_leistung_bis']['options']['default_value']=date('Ymd');
$ddm4_elements['data']['rechnung_leistung_bis']['_list']=[];
$ddm4_elements['data']['rechnung_leistung_bis']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_leistung_bis']['_search']=[];
$ddm4_elements['data']['rechnung_leistung_bis']['_search']['enabled']=false;

/*
 * Data: Bezahlt
 */
$ddm4_elements['data']['rechnung_bezahlt']=[];
$ddm4_elements['data']['rechnung_bezahlt']['module']='yesno';
$ddm4_elements['data']['rechnung_bezahlt']['title']='Bezahlt';
$ddm4_elements['data']['rechnung_bezahlt']['name']='rechnung_bezahlt';
$ddm4_elements['data']['rechnung_bezahlt']['options']=[];
$ddm4_elements['data']['rechnung_bezahlt']['options']['order']=true;
$ddm4_elements['data']['rechnung_bezahlt']['options']['required']=true;
$ddm4_elements['data']['rechnung_bezahlt']['options']['default_value']=0;

/*
 * Data: Storniert
 */
$ddm4_elements['data']['rechnung_storniert']=[];
$ddm4_elements['data']['rechnung_storniert']['module']='yesno';
$ddm4_elements['data']['rechnung_storniert']['title']='Storniert';
$ddm4_elements['data']['rechnung_storniert']['name']='rechnung_storniert';
$ddm4_elements['data']['rechnung_storniert']['options']=[];
$ddm4_elements['data']['rechnung_storniert']['options']['order']=true;
$ddm4_elements['data']['rechnung_storniert']['options']['required']=true;
$ddm4_elements['data']['rechnung_storniert']['options']['default_value']=0;

/*
 * Data: Gesendet
 */
$ddm4_elements['data']['rechnung_gesendet']=[];
$ddm4_elements['data']['rechnung_gesendet']['module']='yesno';
$ddm4_elements['data']['rechnung_gesendet']['title']='Gesendet';
$ddm4_elements['data']['rechnung_gesendet']['name']='rechnung_gesendet';
$ddm4_elements['data']['rechnung_gesendet']['options']=[];
$ddm4_elements['data']['rechnung_gesendet']['options']['order']=true;
$ddm4_elements['data']['rechnung_gesendet']['options']['required']=true;
$ddm4_elements['data']['rechnung_gesendet']['options']['default_value']=0;

/*
 * Data: Kunde
 */
$ddm4_elements['data']['header_kunde']=[];
$ddm4_elements['data']['header_kunde']['module']='header';
$ddm4_elements['data']['header_kunde']['title']='Kunde';
$ddm4_elements['data']['header_kunde']['_search']=[];
$ddm4_elements['data']['header_kunde']['_search']['enabled']=false;

/*
 * Data: Kunde
 */
$ddm4_elements['data']['kunde_id']=[];
$ddm4_elements['data']['kunde_id']['module']='select';
$ddm4_elements['data']['kunde_id']['title']='Kunde';
$ddm4_elements['data']['kunde_id']['name']='kunde_id';
$ddm4_elements['data']['kunde_id']['options']=[];
$ddm4_elements['data']['kunde_id']['options']['order']=true;
$ddm4_elements['data']['kunde_id']['options']['required']=true;
$ddm4_elements['data']['kunde_id']['options']['search']=true;
$ddm4_elements['data']['kunde_id']['options']['data']=$VIS2_WebERP_Verwaltung->getKunden();
$ddm4_elements['data']['kunde_id']['validation']=[];
$ddm4_elements['data']['kunde_id']['validation']['module']='integer';
$ddm4_elements['data']['kunde_id']['validation']['length_min']=1;
$ddm4_elements['data']['kunde_id']['validation']['length_max']=11;
$ddm4_elements['data']['kunde_id']['validation']['search_like']=false;

/*
 * Data: rechnung_kunde_nr
 */
$ddm4_elements['data']['rechnung_kunde_nr']=[];
$ddm4_elements['data']['rechnung_kunde_nr']['module']='hidden';
$ddm4_elements['data']['rechnung_kunde_nr']['title']='rechnung_kunde_nr';
$ddm4_elements['data']['rechnung_kunde_nr']['name']='rechnung_kunde_nr';
$ddm4_elements['data']['rechnung_kunde_nr']['options']=[];
$ddm4_elements['data']['rechnung_kunde_nr']['options']['search']=true;
$ddm4_elements['data']['rechnung_kunde_nr']['options']['default_value']=intval($current_rechnung->getKundeValue('kunde_nr'));
$ddm4_elements['data']['rechnung_kunde_nr']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_nr']['validation']['module']='integer';
$ddm4_elements['data']['rechnung_kunde_nr']['validation']['length_min']=5;
$ddm4_elements['data']['rechnung_kunde_nr']['validation']['length_max']=5;
$ddm4_elements['data']['rechnung_kunde_nr']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_nr']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_nr']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_nr']['_search']['enabled']=false;

/*
 * Data: Gewerblich
 */
$ddm4_elements['data']['rechnung_kunde_gewerblich']=[];
$ddm4_elements['data']['rechnung_kunde_gewerblich']['module']='yesno';
$ddm4_elements['data']['rechnung_kunde_gewerblich']['title']='Gewerblich';
$ddm4_elements['data']['rechnung_kunde_gewerblich']['name']='rechnung_kunde_gewerblich';
$ddm4_elements['data']['rechnung_kunde_gewerblich']['options']=[];
$ddm4_elements['data']['rechnung_kunde_gewerblich']['options']['order']=true;
$ddm4_elements['data']['rechnung_kunde_gewerblich']['options']['required']=true;
$ddm4_elements['data']['rechnung_kunde_gewerblich']['options']['default_value']=1;
$ddm4_elements['data']['rechnung_kunde_gewerblich']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_gewerblich']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_gewerblich']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_gewerblich']['_search']['enabled']=false;

/*
 * Data: Anrede Firma
 */
$ddm4_elements['data']['rechnung_kunde_firma_anrede']=[];
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['module']='select';
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['title']='Anrede Firma';
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['name']='rechnung_kunde_firma_anrede';
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['options']=[];
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['options']['data']=$VIS2_WebERP_Verwaltung->getKundenAnreden(false, 'anrede_titel');
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['validation']['length_max']=32;
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_firma_anrede']['_search']['enabled']=false;

/*
 * Data: Firma
 */
$ddm4_elements['data']['rechnung_kunde_firma']=[];
$ddm4_elements['data']['rechnung_kunde_firma']['module']='text';
$ddm4_elements['data']['rechnung_kunde_firma']['title']='Firma';
$ddm4_elements['data']['rechnung_kunde_firma']['name']='rechnung_kunde_firma';
$ddm4_elements['data']['rechnung_kunde_firma']['options']=[];
$ddm4_elements['data']['rechnung_kunde_firma']['options']['order']=true;
$ddm4_elements['data']['rechnung_kunde_firma']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_firma']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_firma']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_firma']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_firma']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_firma']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_firma']['_search']['enabled']=false;

/*
 * Data: Firma2
 */
$ddm4_elements['data']['rechnung_kunde_firma2']=[];
$ddm4_elements['data']['rechnung_kunde_firma2']['module']='text';
$ddm4_elements['data']['rechnung_kunde_firma2']['title']='Firma2';
$ddm4_elements['data']['rechnung_kunde_firma2']['name']='rechnung_kunde_firma2';
$ddm4_elements['data']['rechnung_kunde_firma2']['options']=[];
$ddm4_elements['data']['rechnung_kunde_firma2']['options']['order']=true;
$ddm4_elements['data']['rechnung_kunde_firma2']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_firma2']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_firma2']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_firma2']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_firma2']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_firma2']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_firma2']['_search']['enabled']=false;

/*
 * Data: ASP auf Rechnung
 */
$ddm4_elements['data']['rechnung_kunde_rechungsasp']=[];
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['module']='yesno';
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['title']='ASP auf Rechnung';
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['name']='rechnung_kunde_rechungsasp';
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['options']=[];
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['options']['required']=true;
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['options']['default_value']=1;
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_rechungsasp']['_search']['enabled']=false;

/*
 * Data: Anrede
 */
$ddm4_elements['data']['rechnung_kunde_anrede']=[];
$ddm4_elements['data']['rechnung_kunde_anrede']['module']='select';
$ddm4_elements['data']['rechnung_kunde_anrede']['title']='Anrede';
$ddm4_elements['data']['rechnung_kunde_anrede']['name']='rechnung_kunde_anrede';
$ddm4_elements['data']['rechnung_kunde_anrede']['options']=[];
$ddm4_elements['data']['rechnung_kunde_anrede']['options']['data']=$VIS2_WebERP_Verwaltung->getKundenAnreden(false, 'anrede_titel');
$ddm4_elements['data']['rechnung_kunde_anrede']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_anrede']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_anrede']['validation']['length_max']=32;
$ddm4_elements['data']['rechnung_kunde_anrede']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_anrede']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_anrede']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_anrede']['_search']['enabled']=false;

/*
 * Data: Titel
 */
$ddm4_elements['data']['rechnung_kunde_titel']=[];
$ddm4_elements['data']['rechnung_kunde_titel']['module']='text';
$ddm4_elements['data']['rechnung_kunde_titel']['title']='Titel';
$ddm4_elements['data']['rechnung_kunde_titel']['name']='rechnung_kunde_titel';
$ddm4_elements['data']['rechnung_kunde_titel']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_titel']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_titel']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_titel']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_titel']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_titel']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_titel']['_search']['enabled']=false;

/*
 * Data: Vorname
 */
$ddm4_elements['data']['rechnung_kunde_vorname']=[];
$ddm4_elements['data']['rechnung_kunde_vorname']['module']='text';
$ddm4_elements['data']['rechnung_kunde_vorname']['title']='Vorname';
$ddm4_elements['data']['rechnung_kunde_vorname']['name']='rechnung_kunde_vorname';
$ddm4_elements['data']['rechnung_kunde_vorname']['options']=[];
$ddm4_elements['data']['rechnung_kunde_vorname']['options']['order']=true;
$ddm4_elements['data']['rechnung_kunde_vorname']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_vorname']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_vorname']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_vorname']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_vorname']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_vorname']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_vorname']['_search']['enabled']=false;

/*
 * Data: Nachname
 */
$ddm4_elements['data']['rechnung_kunde_nachname']=[];
$ddm4_elements['data']['rechnung_kunde_nachname']['module']='text';
$ddm4_elements['data']['rechnung_kunde_nachname']['title']='Nachname';
$ddm4_elements['data']['rechnung_kunde_nachname']['name']='rechnung_kunde_nachname';
$ddm4_elements['data']['rechnung_kunde_nachname']['options']=[];
$ddm4_elements['data']['rechnung_kunde_nachname']['options']['order']=true;
$ddm4_elements['data']['rechnung_kunde_nachname']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_nachname']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_nachname']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_nachname']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_nachname']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_nachname']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_nachname']['_search']['enabled']=false;

/*
 * Data: E-Mail
 */
$ddm4_elements['data']['rechnung_kunde_email']=[];
$ddm4_elements['data']['rechnung_kunde_email']['module']='text';
$ddm4_elements['data']['rechnung_kunde_email']['title']='E-Mail';
$ddm4_elements['data']['rechnung_kunde_email']['name']='rechnung_kunde_email';
$ddm4_elements['data']['rechnung_kunde_email']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_email']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_email']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_email']['validation']['filter']=[];
$ddm4_elements['data']['rechnung_kunde_email']['validation']['filter']['email']=[];
$ddm4_elements['data']['rechnung_kunde_email']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_email']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_email']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_email']['_search']['enabled']=false;

/*
 * Data: Strasse/Hausnr.
 */
$ddm4_elements['data']['rechnung_kunde_strasse']=[];
$ddm4_elements['data']['rechnung_kunde_strasse']['module']='text';
$ddm4_elements['data']['rechnung_kunde_strasse']['title']='Strasse/Hausnr.';
$ddm4_elements['data']['rechnung_kunde_strasse']['name']='rechnung_kunde_strasse';
$ddm4_elements['data']['rechnung_kunde_strasse']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_strasse']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_strasse']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_strasse']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_strasse']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_strasse']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_strasse']['_search']['enabled']=false;

/*
 * Data: Land
 */
$ddm4_elements['data']['rechnung_kunde_land']=[];
$ddm4_elements['data']['rechnung_kunde_land']['module']='select';
$ddm4_elements['data']['rechnung_kunde_land']['title']='Land';
$ddm4_elements['data']['rechnung_kunde_land']['name']='rechnung_kunde_land';
$ddm4_elements['data']['rechnung_kunde_land']['options']=[];
$ddm4_elements['data']['rechnung_kunde_land']['options']['data']=$VIS2_WebERP_Verwaltung->getKundenLaender(false, 'land_titel');
$ddm4_elements['data']['rechnung_kunde_land']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_land']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_land']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_land']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_land']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_land']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_land']['_search']['enabled']=false;

/*
 * Data: PLZ
 */
$ddm4_elements['data']['rechnung_kunde_plz']=[];
$ddm4_elements['data']['rechnung_kunde_plz']['module']='text';
$ddm4_elements['data']['rechnung_kunde_plz']['title']='PLZ';
$ddm4_elements['data']['rechnung_kunde_plz']['name']='rechnung_kunde_plz';
$ddm4_elements['data']['rechnung_kunde_plz']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_plz']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_plz']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_plz']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_plz']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_plz']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_plz']['_search']['enabled']=false;

/*
 * Data: Ort
 */
$ddm4_elements['data']['rechnung_kunde_ort']=[];
$ddm4_elements['data']['rechnung_kunde_ort']['module']='text';
$ddm4_elements['data']['rechnung_kunde_ort']['title']='Ort';
$ddm4_elements['data']['rechnung_kunde_ort']['name']='rechnung_kunde_ort';
$ddm4_elements['data']['rechnung_kunde_ort']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_ort']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_ort']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_ort']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_ort']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_ort']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_ort']['_search']['enabled']=false;

/*
 * Data: Telefon
 */
$ddm4_elements['data']['rechnung_kunde_telefon']=[];
$ddm4_elements['data']['rechnung_kunde_telefon']['module']='text';
$ddm4_elements['data']['rechnung_kunde_telefon']['title']='Telefon';
$ddm4_elements['data']['rechnung_kunde_telefon']['name']='rechnung_kunde_telefon';
$ddm4_elements['data']['rechnung_kunde_telefon']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_telefon']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_telefon']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_telefon']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_telefon']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_telefon']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_telefon']['_search']['enabled']=false;

/*
 * Data: Fax
 */
$ddm4_elements['data']['rechnung_kunde_fax']=[];
$ddm4_elements['data']['rechnung_kunde_fax']['module']='text';
$ddm4_elements['data']['rechnung_kunde_fax']['title']='Fax';
$ddm4_elements['data']['rechnung_kunde_fax']['name']='rechnung_kunde_fax';
$ddm4_elements['data']['rechnung_kunde_fax']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_fax']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_fax']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_fax']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_fax']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_fax']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_fax']['_search']['enabled']=false;

/*
 * Data: Mobil
 */
$ddm4_elements['data']['rechnung_kunde_mobil']=[];
$ddm4_elements['data']['rechnung_kunde_mobil']['module']='text';
$ddm4_elements['data']['rechnung_kunde_mobil']['title']='Mobil';
$ddm4_elements['data']['rechnung_kunde_mobil']['name']='rechnung_kunde_mobil';
$ddm4_elements['data']['rechnung_kunde_mobil']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_mobil']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_mobil']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_mobil']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_mobil']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_mobil']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_mobil']['_search']['enabled']=false;

/*
 * Data: Homepage
 */
$ddm4_elements['data']['rechnung_kunde_homepage']=[];
$ddm4_elements['data']['rechnung_kunde_homepage']['module']='text';
$ddm4_elements['data']['rechnung_kunde_homepage']['title']='Homepage';
$ddm4_elements['data']['rechnung_kunde_homepage']['name']='rechnung_kunde_homepage';
$ddm4_elements['data']['rechnung_kunde_homepage']['validation']=[];
$ddm4_elements['data']['rechnung_kunde_homepage']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_kunde_homepage']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_kunde_homepage']['validation']['filter']=[];
$ddm4_elements['data']['rechnung_kunde_homepage']['validation']['filter']['url']=[];
$ddm4_elements['data']['rechnung_kunde_homepage']['_list']=[];
$ddm4_elements['data']['rechnung_kunde_homepage']['_list']['enabled']=false;
$ddm4_elements['data']['rechnung_kunde_homepage']['_search']=[];
$ddm4_elements['data']['rechnung_kunde_homepage']['_search']['enabled']=false;

/*
 * Data: Gesamt (Nt)
 */
$ddm4_elements['data']['rechnung_gesamt_netto']=[];
$ddm4_elements['data']['rechnung_gesamt_netto']['module']='vis2_weberp_text_price';
$ddm4_elements['data']['rechnung_gesamt_netto']['enabled']=false;
$ddm4_elements['data']['rechnung_gesamt_netto']['title']='Gesamt (Nt)';
$ddm4_elements['data']['rechnung_gesamt_netto']['name']='rechnung_gesamt_netto';
$ddm4_elements['data']['rechnung_gesamt_netto']['validation']=[];
$ddm4_elements['data']['rechnung_gesamt_netto']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_gesamt_netto']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_gesamt_netto']['validation']['filter']=[];
$ddm4_elements['data']['rechnung_gesamt_netto']['validation']['filter']['url']=[];
$ddm4_elements['data']['rechnung_gesamt_netto']['_list']=[];
$ddm4_elements['data']['rechnung_gesamt_netto']['_list']['enabled']=true;

/*
 * Data: Gesamt (Bt)
 */
$ddm4_elements['data']['rechnung_gesamt_brutto']=[];
$ddm4_elements['data']['rechnung_gesamt_brutto']['module']='vis2_weberp_text_price';
$ddm4_elements['data']['rechnung_gesamt_brutto']['enabled']=false;
$ddm4_elements['data']['rechnung_gesamt_brutto']['title']='Gesamt (Bt)';
$ddm4_elements['data']['rechnung_gesamt_brutto']['name']='rechnung_gesamt_brutto';
$ddm4_elements['data']['rechnung_gesamt_brutto']['validation']=[];
$ddm4_elements['data']['rechnung_gesamt_brutto']['validation']['length_min']=0;
$ddm4_elements['data']['rechnung_gesamt_brutto']['validation']['length_max']=128;
$ddm4_elements['data']['rechnung_gesamt_brutto']['validation']['filter']=[];
$ddm4_elements['data']['rechnung_gesamt_brutto']['validation']['filter']['url']=[];
$ddm4_elements['data']['rechnung_gesamt_brutto']['_list']=[];
$ddm4_elements['data']['rechnung_gesamt_brutto']['_list']['enabled']=true;

/*
 * Positionen
 */
$element_vars=[];
$element_stored=$current_rechnung->getPositionen(true);
foreach ($element_stored as $i=>$element) {
	/*
	 * Data: Header
	 */
	$ddm4_elements['data']['position_artikel_'.$i.'_header']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_header']['module']='header';
	$ddm4_elements['data']['position_artikel_'.$i.'_header']['title']='Position #'.$i;
	$ddm4_elements['data']['position_artikel_'.$i.'_header']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_header']['_search']['enabled']=false;

	/*
	 * Data: Artikel
	 */
	$element_vars['position_artikel_'.$i.'_id']='integer';
	if (!isset($_POST['position_artikel_'.$i.'_id'])) {
		$_POST['position_artikel_'.$i.'_id']=$element['position_artikel_id'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_id']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['module']='select';
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['title']='Artikel';
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['options']['search']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikel(false);
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['validation']['module']='integer';
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['validation']['length_min']=0;
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['validation']['length_max']=11;
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['_search']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['_add']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['_add']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_id']['_add']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikel();

	/*
	 * Data: position_artikel_nr
	 */
	$element_vars['position_artikel_'.$i.'_nr']='integer';
	if (!isset($_POST['position_artikel_'.$i.'_nr'])) {
		$_POST['position_artikel_'.$i.'_nr']=$element['position_artikel_nr'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['module']='hidden';
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['title']='position_artikel_'.$i.'_nr';
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['options']['search']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['options']['default_value']=\osWFrame\Core\Settings::catchIntValue('position_artikel_'.$i.'_nr', 0, 'pg');
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['validation']['module']='integer';
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['validation']['length_min']=1;
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['validation']['length_max']=6;
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_nr']['_search']['enabled']=false;

	/*
	 * Data: position_artikel_kurz
	 */
	$element_vars['position_artikel_'.$i.'_kurz']='string';
	if (!isset($_POST['position_artikel_'.$i.'_kurz'])) {
		$_POST['position_artikel_'.$i.'_kurz']=$element['position_artikel_kurz'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['module']='hidden';
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['title']='position_artikel_'.$i.'_kurz';
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['options']['search']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['options']['default_value']=\osWFrame\Core\Settings::catchStringValue('position_artikel_'.$i.'_kurz', '', 'pg');
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['validation']['module']='string';
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['validation']['length_min']=2;
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['validation']['length_max']=4;
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_kurz']['_search']['enabled']=false;

	/*
	 * Data: Anzahl
	 */
	$element_vars['position_artikel_'.$i.'_anzahl']='float';
	if (!isset($_POST['position_artikel_'.$i.'_anzahl'])) {
		$_POST['position_artikel_'.$i.'_anzahl']=$element['position_artikel_anzahl'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['module']='text';
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['title']='Anzahl';
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['validation']['module']='float';
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_anzahl']['_search']['enabled']=false;

	/*
	 * Data: Beschreibung
	 */
	$element_vars['position_artikel_'.$i.'_beschreibung']='string';
	if (!isset($_POST['position_artikel_'.$i.'_beschreibung'])) {
		$_POST['position_artikel_'.$i.'_beschreibung']=$element['position_artikel_beschreibung'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['module']='textarea';
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['title']='Beschreibung';
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['options']['order']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['options']['search']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['options']['required']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['validation']['length_min']=6;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['validation']['length_max']=10000;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung']['_search']['enabled']=false;

	/*
	 * Data: Beschreibung ausblenden
	 */
	$element_vars['position_artikel_'.$i.'_beschreibung_ausblenden']='integer';
	if (!isset($_POST['position_artikel_'.$i.'_beschreibung_ausblenden'])) {
		$_POST['position_artikel_'.$i.'_beschreibung_ausblenden']=$element['position_artikel_beschreibung_ausblenden'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['module']='yesno';
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['title']='Beschreibung ausblenden';
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['options']['default_value']=0;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_beschreibung_ausblenden']['_search']['enabled']=false;

	/*
	 * Data: Zusatzbeschreibung
	 */
	$element_vars['position_artikel_'.$i.'_zusatz']='string';
	if (!isset($_POST['position_artikel_'.$i.'_zusatz'])) {
		$_POST['position_artikel_'.$i.'_zusatz']=$element['position_artikel_zusatz'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['module']='textarea';
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['title']='Zusatzbeschreibung';
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['options']['order']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['validation']['length_min']=0;
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['validation']['length_max']=10000;
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_zusatz']['_search']['enabled']=false;

	/*
	 * Data: Typ
	 */
	$element_vars['position_artikel_'.$i.'_typ']='integer';
	if (!isset($_POST['position_artikel_'.$i.'_typ'])) {
		$_POST['position_artikel_'.$i.'_typ']=$element['position_artikel_typ'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['module']='select';
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['title']='Typ';
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['options']['order']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['options']['required']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelTypen(false);
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['validation']['module']='integer';
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['validation']['length_min']=1;
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['validation']['length_max']=1;
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['_search']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['_add']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['_add']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_typ']['_add']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelTypen();

	/*
	 * Data: Preis
	 */
	$element_vars['position_artikel_'.$i.'_preis']='float';
	if (!isset($_POST['position_artikel_'.$i.'_preis'])) {
		$_POST['position_artikel_'.$i.'_preis']=$element['position_artikel_preis'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['module']='text';
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['title']='Preis';
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['options']['order']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['options']['required']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['validation']['module']='float';
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['validation']['length_min']=0;
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['validation']['length_max']=11;
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_preis']['_search']['enabled']=false;

	/*
	 * Data: MwSt
	 */
	$element_vars['position_artikel_'.$i.'_mwst']='integer';
	if (!isset($_POST['position_artikel_'.$i.'_mwst'])) {
		$_POST['position_artikel_'.$i.'_mwst']=$element['position_artikel_mwst'];
	}
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['module']='select';
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['title']='MwSt';
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['options']['order']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['options']['required']=true;
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelMwSt(false, 'mwst_titel');
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['validation']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['validation']['module']='integer';
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['validation']['length_min']=1;
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['validation']['length_max']=3;
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['_list']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['_list']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['_search']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['_search']['enabled']=false;
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['_add']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['_add']['options']=[];
	$ddm4_elements['data']['position_artikel_'.$i.'_mwst']['_add']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelMwSt(true, 'mwst_titel');
}

/*
 * Data: Artikel
 */
$ddm4_elements['data']['position_artikel_id']=[];
$ddm4_elements['data']['position_artikel_id']['module']='select';
$ddm4_elements['data']['position_artikel_id']['title']='Artikel';
$ddm4_elements['data']['position_artikel_id']['name']='position_artikel_id';
$ddm4_elements['data']['position_artikel_id']['options']=[];
$ddm4_elements['data']['position_artikel_id']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikel(false);
$ddm4_elements['data']['position_artikel_id']['validation']=[];
$ddm4_elements['data']['position_artikel_id']['validation']['module']='vis2_weberp_search_rechnung_artikel';
$ddm4_elements['data']['position_artikel_id']['validation']['search_like']=false;
$ddm4_elements['data']['position_artikel_id']['_list']=[];
$ddm4_elements['data']['position_artikel_id']['_list']['enabled']=false;
$ddm4_elements['data']['position_artikel_id']['_add']=[];
$ddm4_elements['data']['position_artikel_id']['_add']['enabled']=false;
$ddm4_elements['data']['position_artikel_id']['_edit']=[];
$ddm4_elements['data']['position_artikel_id']['_edit']['enabled']=false;
$ddm4_elements['data']['position_artikel_id']['_delete']=[];
$ddm4_elements['data']['position_artikel_id']['_delete']['enabled']=false;

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='rechnung_';
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='rechnung_';

/*
 * AfterFinish: vis2_weberp_rechnung_store_positions
 */
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_store_positions']=[];
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_store_positions']['module']='vis2_weberp_rechnung_store_positions';
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_store_positions']['options']=[];
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_store_positions']['options']['object']=$current_rechnung;

/*
 * AfterFinish: vis2_weberp_rechnung_summe
 */
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_summe']=[];
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_summe']['module']='vis2_weberp_rechnung_summe';
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_summe']['options']=[];
$ddm4_elements['afterfinish']['vis2_weberp_rechnung_summe']['options']['object']=$current_rechnung;

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
 * Ajax
 */
$ajax_data=[];
$ajax_data['kunde_id']=['rechnung_kunde_gewerblich', 'rechnung_kunde_firma_anrede', 'rechnung_kunde_firma', 'rechnung_kunde_firma2', 'rechnung_kunde_rechungsasp', 'rechnung_kunde_anrede', 'rechnung_kunde_titel', 'rechnung_kunde_vorname', 'rechnung_kunde_nachname', 'rechnung_kunde_email', 'rechnung_kunde_strasse', 'rechnung_kunde_land', 'rechnung_kunde_plz', 'rechnung_kunde_ort', 'rechnung_kunde_telefon', 'rechnung_kunde_fax', 'rechnung_kunde_mobil', 'rechnung_kunde_homepage',];

for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
	$ajax_data['position_artikel_'.$i.'_id']=[];
	foreach ($element_values_all as $value) {
		$ajax_data['position_artikel_'.$i.'_id'][]='position_artikel_'.$i.'_'.$value;
	}
}

if (in_array(\osWFrame\Core\Settings::getAction(), ['doadd', 'doedit'])) {
	for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
		$set=[];
		$position_artikel_id=\osWFrame\Core\Settings::catchIntValue('position_artikel_'.$i.'_id', 0, 'pg');
		$position_artikel='position_artikel_'.$i.'_id';
		if ((isset($ajax_data[$position_artikel]))&&($position_artikel_id>0)) {
			$set=$ajax_data[$position_artikel];
		}
		foreach ($element_values_all as $element) {
			$element='position_artikel_'.$i.'_'.$element;
			if (!in_array($element, $set)) {
				$osW_DDM4->removeAddElementValue($element, 'validation');
				$osW_DDM4->removeEditElementValue($element, 'validation');
				if (isset($_POST[$element])) {
					unset($_POST[$element]);
				}
			}
		}
	}
}

if (in_array(\osWFrame\Core\Settings::getAction(), ['add', 'doadd', 'edit', 'doedit', 'delete', 'dodelete'])) {
	$css=[];
	$ajax=[];

	for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
		foreach ($element_values_all as $element) {
			$css[]='.ddm_element_position_artikel_'.$i.'_'.$element.' {display:none;}';
		}
	}

	$_ajax='
$(window).on("load", function() {
	ddm3_formular_'.$osW_DDM4->getName().'_artikel("");
';
	for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
		$_ajax.='	$("select[name=\'position_artikel_'.$i.'_id\']").change(function(){ddm3_formular_'.$osW_DDM4->getName().'_artikel('.$i.', true);});
';
	}
	$_ajax.='});
';

	$ajax[]=$_ajax;

	$_ajax='
function ddm3_formular_'.$osW_DDM4->getName().'_artikel(position, load) {
	if (!position) {
		position=0;
	}

	if (!load) {
		load=false;
	}

	if (load===true) {
		if (position>0) {
			if ($("select[name=\'position_artikel_"+position+"_id\']").val()>0) {
				$(".ddm_element_position_artikel_"+position+"_'.implode('").fadeIn(0); $(".ddm_element_position_artikel_"+position+"_', $element_values_all).'").fadeIn(0);
				$.ajax({
					url: "'.$osW_DDM4->getTemplate()->buildhrefLink($osW_DDM4->getDirectModule(), $osW_DDM4->getDirectParameters()).'",
					type: "POST",
					cache: false,
					async: false,
					dataType: "json",
					data: {
						action: "getArtikelDetails",
						artikel_id: $("select[name=\'position_artikel_"+position+"_id\']").val()
					}
				})
				.done(function(data) {
					if (data.artikel_nr) {
						$("input[name=\'position_artikel_"+position+"_nr\']").val(data.artikel_nr);
					} else {
						$("input[name=\'position_artikel_"+position+"_nr\']").val("");
					}
					if (data.artikel_kurz) {
						$("input[name=\'position_artikel_"+position+"_kurz\']").val(data.artikel_kurz);
					} else {
						$("input[name=\'position_artikel_"+position+"_kurz\']").val("");
					}
					if (data.artikel_beschreibung) {
						$("#position_artikel_"+position+"_beschreibung").val(data.artikel_beschreibung);
					} else {
						$("#position_artikel_"+position+"_beschreibung").val("");
					}
					if (data.artikel_beschreibung_ausblenden) {
						if (data.artikel_beschreibung_ausblenden==1) {
							$("#position_artikel_"+position+"_beschreibung_ausblenden0").prop("checked", true);
							$("#position_artikel_"+position+"_beschreibung_ausblenden1").prop("checked", false);
						} else {
							$("#position_artikel_"+position+"_beschreibung_ausblenden0").prop("checked", false);
							$("#position_artikel_"+position+"_beschreibung_ausblenden1").prop("checked", true);
						}
					}
					if (data.artikel_preis) {
						$("#position_artikel_"+position+"_preis").val(data.artikel_preis);
					} else {
						$("#position_artikel_"+position+"_preis").val("");
					}
					if (data.artikel_typ) {
						$("#position_artikel_"+position+"_typ").selectpicker("val", data.artikel_typ);
					} else {
						$("#position_artikel_"+position+"_typ").val("");
					}
					if (data.artikel_mwst) {
						$("#position_artikel_"+position+"_mwst").selectpicker("val", data.artikel_mwst);
					} else {
						$("#position_artikel_"+position+"_mwst").val("");
					}
				});
			} else {
				$(".ddm_element_position_artikel_"+position+"_'.implode('").fadeOut(0); $(".ddm_element_position_artikel_"+position+"_', $element_values_all).'").fadeOut(0);
			}
		}
	}
';

	for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
		for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
			$_ajax.='	position_artikel=$("select[name=\'position_artikel_'.$i.'_id\']").val();
	values=["position_artikel_'.$i.'_'.implode('", "position_artikel_'.$i.'_', $element_values).'"];
	set_values=[];
';
			$_ajax.='	$.each(values, function( key, value ) {
			$(".ddm_element_"+value).fadeOut(0);
	});
	$.each(set_values, function( key, value ) {
			$(".ddm_element_"+value).fadeIn(0);
	});
';
		}
	}

	$_ajax.='
	lastregion=0;
	for (i=1;i<='.\JBSNewMedia\WebERP\Verwaltung::getPositionsMax().';i++) {
		if ($("select[name=\'position_artikel_"+i+"_id\']").val()>0) {
			lastregion=i;
		}
	}
	lastregion=lastregion+1;
	for (i=1;i<='.\JBSNewMedia\WebERP\Verwaltung::getPositionsMax().';i++) {
		if (i<=lastregion) {
			$(".ddm_element_position_artikel_"+i+"_'.implode('").fadeIn(0); $(".ddm_element_position_artikel_"+i+"_', $element_values).'").fadeIn(0);
			if ($("select[name=\'position_artikel_"+i+"_id\']").val()>0) {
				$(".ddm_element_position_artikel_"+i+"_'.implode('").fadeIn(0); $(".ddm_element_position_artikel_"+i+"_', $element_values_all).'").fadeIn(0);
			}
		} else {
			$(".ddm_element_position_artikel_"+i+"_'.implode('").fadeOut(0); $(".ddm_element_position_artikel_"+i+"_', $element_values).'").fadeOut(0);
		}
	}
}';
	$ajax[]=$_ajax;

	foreach ($ajax_data['kunde_id'] as $element) {
		$css[]='.ddm_element_'.$element.' {display:none;}';
	}

	$_ajax='
$(window).on("load", function() {
	ddm3_formular_'.$osW_DDM4->getName().'_kunde("");
	$("select[name=\'kunde_id\']").change(function(){ddm3_formular_'.$osW_DDM4->getName().'_kunde(true);});
';
	$_ajax.='});
';

	$ajax[]=$_ajax;

	$_ajax='
function ddm3_formular_'.$osW_DDM4->getName().'_kunde(load) {
	if (!load) {
		load=false;
	}

	if ($("select[name=\'kunde_id\']").val()!=0) {
		$(".ddm_element_'.implode('").fadeIn(0);$(".ddm_element_', $ajax_data['kunde_id']).'").fadeIn(0);
		$.ajax({
			url: "'.$osW_DDM4->getTemplate()->buildhrefLink($osW_DDM4->getDirectModule(), $osW_DDM4->getDirectParameters()).'",
			type: "POST",
			cache: false,
			async: false,
			dataType: "json",
			data: {
				action: "getKundeDetails",
				kunde_id: $("select[name=\'kunde_id\']").val()
			}
		})
		.done(function(data) {
			if (load===true) {
				if (data.kunde_nr) {
					$("input[name=\'rechnung_kunde_nr\']").val(data.kunde_nr);
				}
				if (data.kunde_gewerblich) {
					if (data.kunde_gewerblich==1) {
						$("#rechnung_kunde_gewerblich0").prop("checked", true);
						$("#rechnung_kunde_gewerblich1").prop("checked", false);
					} else {
						$("#rechnung_kunde_gewerblich0").prop("checked", false);
						$("#rechnung_kunde_gewerblich1").prop("checked", true);
					}
				}
				if (data.kunde_firma_anrede) {
					$("#rechnung_kunde_firma_anrede").selectpicker("val", data.kunde_firma_anrede);
				} else {
					$("#rechnung_kunde_firma_anrede").selectpicker("val", "");
				}
				if (data.kunde_firma) {
					$("#rechnung_kunde_firma").val(data.kunde_firma);
				} else {
					$("#rechnung_kunde_firma").val("");
				}
				if (data.kunde_firma2) {
					$("#rechnung_kunde_firma2").val(data.kunde_firma2);
				} else {
					$("#rechnung_kunde_firma2").val("");
				}
				if (data.kunde_rechungsasp) {
					if (data.kunde_rechungsasp==1) {
						$("#rechnung_kunde_rechungsasp0").prop("checked", true);
						$("#rechnung_kunde_rechungsasp1").prop("checked", false);
					} else {
						$("#rechnung_kunde_rechungsasp0").prop("checked", false);
						$("#rechnung_kunde_rechungsasp1").prop("checked", true);
					}
				}
				if (data.kunde_anrede) {
					$("#rechnung_kunde_anrede").selectpicker("val", data.kunde_anrede);
				} else {
					$("#rechnung_kunde_anrede").selectpicker("val", "");
				}
				if (data.kunde_titel) {
					$("#rechnung_kunde_titel").val(data.kunde_titel);
				} else {
					$("#rechnung_kunde_titel").val("");
				}
				if (data.kunde_vorname) {
					$("#rechnung_kunde_vorname").val(data.kunde_vorname);
				} else {
					$("#rechnung_kunde_vorname").val("");
				}
				if (data.kunde_nachname) {
					$("#rechnung_kunde_nachname").val(data.kunde_nachname);
				} else {
					$("#rechnung_kunde_nachname").val("");
				}
				if (data.kunde_email) {
					$("#rechnung_kunde_email").val(data.kunde_email);
				} else {
					$("#rechnung_kunde_email").val("");
				}
				if (data.kunde_strasse) {
					$("#rechnung_kunde_strasse").val(data.kunde_strasse);
				} else {
					$("#rechnung_kunde_strasse").val("");
				}
				if (data.kunde_land) {
					$("#rechnung_kunde_land").selectpicker("val", data.kunde_land);
				} else {
					$("#rechnung_kunde_land").selectpicker("val", "");
				}
				if (data.kunde_plz) {
					$("#rechnung_kunde_plz").val(data.kunde_plz);
				} else {
					$("#rechnung_kunde_plz").val("");
				}
				if (data.kunde_ort) {
					$("#rechnung_kunde_ort").val(data.kunde_ort);
				} else {
					$("#rechnung_kunde_ort").val("");
				}
				if (data.kunde_telefon) {
					$("#rechnung_kunde_telefon").val(data.kunde_telefon);
				} else {
					$("#rechnung_kunde_telefon").val("");
				}
				if (data.kunde_fax) {
					$("#rechnung_kunde_fax").val(data.kunde_fax);
				} else {
					$("#rechnung_kunde_fax").val("");
				}
				if (data.kunde_mobil) {
					$("#rechnung_kunde_mobil").val(data.kunde_mobil);
				} else {
					$("#rechnung_kunde_mobil").val("");
				}
				if (data.kunde_homepage) {
					$("#rechnung_kunde_homepage").val(data.kunde_homepage);
				} else {
					$("#rechnung_kunde_homepage").val("");
				}
			}
		});
	} else {
		$(".ddm_element_'.implode('").fadeOut(0);$(".ddm_element_', $ajax_data['kunde_id']).'").fadeOut(0);
	}

}';
	$ajax[]=$_ajax;

	$osW_DDM4->getTemplate()->addJSCodeHead(implode("\n", $ajax));
	$osW_DDM4->getTemplate()->addCSSCodeHead(implode("\n", $css));
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