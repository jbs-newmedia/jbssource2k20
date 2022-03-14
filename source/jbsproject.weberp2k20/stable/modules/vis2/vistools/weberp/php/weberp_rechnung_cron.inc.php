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

if (\osWFrame\Core\Settings::getAction()=='getArtikelDetails') {
	\osWFrame\Core\Network::dieJSON($VIS2_WebERP_Verwaltung->getArtikelById(\osWFrame\Core\Settings::catchIntValue('artikel_id')));
}

$element_values_all=['anzahl', 'zusatz', 'beschreibung', 'beschreibung_ausblenden', 'preis', 'typ', 'mwst', 'nr', 'kurz',];
$element_values=['header', 'id',];

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
$ddm4_object['messages']['data_noresults']='Keine Positionen vorhanden';
$ddm4_object['messages']['search_title']='Positionen durchsuchen';
$ddm4_object['messages']['add_title']='Neue Position anlegen';
$ddm4_object['messages']['add_success_title']='Position wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Position konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Position editieren';
$ddm4_object['messages']['edit_load_error_title']='Position wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Position wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Position konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Position löschen';
$ddm4_object['messages']['delete_load_error_title']='Position wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Position wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Position konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_rechnung_cron';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='cron_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['cron_id']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_rechnung_cron', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(cron_id) AS counter FROM :table_weberp_rechnung_cron: WHERE mandant_id=:mandant_id: AND cron_ispublic=:cron_ispublic:');
$QselectCount->bindTable(':table_weberp_rechnung_cron:', 'weberp_rechnung_cron');
$QselectCount->bindInt(':cron_ispublic:', 1);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Aktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(cron_id) AS counter FROM :table_weberp_rechnung_cron: WHERE mandant_id=:mandant_id: AND cron_ispublic=:cron_ispublic:');
$QselectCount->bindTable(':table_weberp_rechnung_cron:', 'weberp_rechnung_cron');
$QselectCount->bindInt(':cron_ispublic:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Inaktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(cron_id) AS counter FROM :table_weberp_rechnung_cron: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_rechnung_cron:', 'weberp_rechnung_cron');
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
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'cron_ispublic', 'operator'=>'=', 'value'=>1], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'cron_ispublic', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['cron_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);
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
 * Data: Datum
 */
$ddm4_elements['data']['cron_date']=[];
$ddm4_elements['data']['cron_date']['module']='datepicker';
$ddm4_elements['data']['cron_date']['title']='Datum';
$ddm4_elements['data']['cron_date']['name']='cron_date';
$ddm4_elements['data']['cron_date']['options']=[];
$ddm4_elements['data']['cron_date']['options']['order']=true;
$ddm4_elements['data']['cron_date']['options']['required']=true;
$ddm4_elements['data']['cron_date']['options']['year_min']=\JBSNewMedia\WebERP\Verwaltung::getBeginningYear();
$ddm4_elements['data']['cron_date']['options']['default_value']=date('Ymd');
$ddm4_elements['data']['cron_date']['_search']=[];
$ddm4_elements['data']['cron_date']['_list']['enabled']=false;

/*
 * Data: Monate
 */
$ddm4_elements['data']['cron_months']=[];
$ddm4_elements['data']['cron_months']['module']='bitmask';
$ddm4_elements['data']['cron_months']['title']='Monate';
$ddm4_elements['data']['cron_months']['name']='cron_months';
$ddm4_elements['data']['cron_months']['options']=[];
$ddm4_elements['data']['cron_months']['options']['data']=[];
$ddm4_elements['data']['cron_months']['options']['data']['0']='Januar';
$ddm4_elements['data']['cron_months']['options']['data']['1']='Februar';
$ddm4_elements['data']['cron_months']['options']['data']['2']='März';
$ddm4_elements['data']['cron_months']['options']['data']['3']='April';
$ddm4_elements['data']['cron_months']['options']['data']['4']='Mai';
$ddm4_elements['data']['cron_months']['options']['data']['5']='Juni';
$ddm4_elements['data']['cron_months']['options']['data']['6']='Juli';
$ddm4_elements['data']['cron_months']['options']['data']['7']='August';
$ddm4_elements['data']['cron_months']['options']['data']['8']='September';
$ddm4_elements['data']['cron_months']['options']['data']['9']='Oktober';
$ddm4_elements['data']['cron_months']['options']['data']['10']='November';
$ddm4_elements['data']['cron_months']['options']['data']['11']='Dezember';
$ddm4_elements['data']['cron_months']['_list']=[];
$ddm4_elements['data']['cron_months']['_list']['enabled']=false;

/*
 * Data: Leistung von
 */
$ddm4_elements['data']['cron_leistung_von']=[];
$ddm4_elements['data']['cron_leistung_von']['module']='text';
$ddm4_elements['data']['cron_leistung_von']['title']='Leistung von';
$ddm4_elements['data']['cron_leistung_von']['name']='cron_leistung_von';
$ddm4_elements['data']['cron_leistung_von']['_list']=[];
$ddm4_elements['data']['cron_leistung_von']['_list']['enabled']=false;

/*
 * Data: Leistung bis
 */
$ddm4_elements['data']['cron_leistung_bis']=[];
$ddm4_elements['data']['cron_leistung_bis']['module']='text';
$ddm4_elements['data']['cron_leistung_bis']['title']='Leistung bis';
$ddm4_elements['data']['cron_leistung_bis']['name']='cron_leistung_bis';
$ddm4_elements['data']['cron_leistung_bis']['_list']=[];
$ddm4_elements['data']['cron_leistung_bis']['_list']['enabled']=false;

/*
 * Data: Aktiviert
 */
$ddm4_elements['data']['cron_ispublic']=[];
$ddm4_elements['data']['cron_ispublic']['module']='yesno';
$ddm4_elements['data']['cron_ispublic']['title']='Aktiviert';
$ddm4_elements['data']['cron_ispublic']['name']='cron_ispublic';
$ddm4_elements['data']['cron_ispublic']['options']=[];
$ddm4_elements['data']['cron_ispublic']['options']['order']=true;
$ddm4_elements['data']['cron_ispublic']['options']['required']=true;
$ddm4_elements['data']['cron_ispublic']['options']['default_value']=1;

/*
 * Data: Header
 */
$ddm4_elements['data']['cron_artikel_1_header']=[];
$ddm4_elements['data']['cron_artikel_1_header']['module']='header';
$ddm4_elements['data']['cron_artikel_1_header']['title']='Position';
$ddm4_elements['data']['cron_artikel_1_header']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_header']['_search']['enabled']=false;

/*
 * Data: Artikel
 */
$ddm4_elements['data']['cron_artikel_1_id']=[];
$ddm4_elements['data']['cron_artikel_1_id']['module']='select';
$ddm4_elements['data']['cron_artikel_1_id']['title']='Artikel';
$ddm4_elements['data']['cron_artikel_1_id']['name']='cron_artikel_1_id';
$ddm4_elements['data']['cron_artikel_1_id']['options']=[];
$ddm4_elements['data']['cron_artikel_1_id']['options']['search']=true;
$ddm4_elements['data']['cron_artikel_1_id']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikel(false);
$ddm4_elements['data']['cron_artikel_1_id']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_id']['validation']['module']='integer';
$ddm4_elements['data']['cron_artikel_1_id']['validation']['length_min']=0;
$ddm4_elements['data']['cron_artikel_1_id']['validation']['length_max']=11;
$ddm4_elements['data']['cron_artikel_1_id']['_add']=[];
$ddm4_elements['data']['cron_artikel_1_id']['_add']['options']=[];
$ddm4_elements['data']['cron_artikel_1_id']['_add']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikel();

/*
 * Data: cron_artikel_nr
 */
$ddm4_elements['data']['cron_artikel_1_nr']=[];
$ddm4_elements['data']['cron_artikel_1_nr']['module']='hidden';
$ddm4_elements['data']['cron_artikel_1_nr']['title']='cron_artikel_1_nr';
$ddm4_elements['data']['cron_artikel_1_nr']['name']='cron_artikel_1_nr';
$ddm4_elements['data']['cron_artikel_1_nr']['options']=[];
$ddm4_elements['data']['cron_artikel_1_nr']['options']['search']=true;
$ddm4_elements['data']['cron_artikel_1_nr']['options']['default_value']=\osWFrame\Core\Settings::catchIntValue('cron_artikel_1_nr', 0, 'pg');
$ddm4_elements['data']['cron_artikel_1_nr']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_nr']['validation']['module']='integer';
$ddm4_elements['data']['cron_artikel_1_nr']['validation']['length_min']=1;
$ddm4_elements['data']['cron_artikel_1_nr']['validation']['length_max']=6;
$ddm4_elements['data']['cron_artikel_1_nr']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_nr']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_nr']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_nr']['_search']['enabled']=false;

/*
 * Data: cron_artikel_kurz
 */
$ddm4_elements['data']['cron_artikel_1_kurz']=[];
$ddm4_elements['data']['cron_artikel_1_kurz']['module']='hidden';
$ddm4_elements['data']['cron_artikel_1_kurz']['title']='cron_artikel_1_kurz';
$ddm4_elements['data']['cron_artikel_1_kurz']['name']='cron_artikel_1_kurz';
$ddm4_elements['data']['cron_artikel_1_kurz']['options']=[];
$ddm4_elements['data']['cron_artikel_1_kurz']['options']['search']=true;
$ddm4_elements['data']['cron_artikel_1_kurz']['options']['default_value']=\osWFrame\Core\Settings::catchStringValue('cron_artikel_1_kurz', '', 'pg');
$ddm4_elements['data']['cron_artikel_1_kurz']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_kurz']['validation']['module']='string';
$ddm4_elements['data']['cron_artikel_1_kurz']['validation']['length_min']=2;
$ddm4_elements['data']['cron_artikel_1_kurz']['validation']['length_max']=4;
$ddm4_elements['data']['cron_artikel_1_kurz']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_kurz']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_kurz']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_kurz']['_search']['enabled']=false;

/*
 * Data: Anzahl
 */
$ddm4_elements['data']['cron_artikel_1_anzahl']=[];
$ddm4_elements['data']['cron_artikel_1_anzahl']['module']='text';
$ddm4_elements['data']['cron_artikel_1_anzahl']['title']='Anzahl';
$ddm4_elements['data']['cron_artikel_1_anzahl']['name']='cron_artikel_1_anzahl';
$ddm4_elements['data']['cron_artikel_1_anzahl']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_anzahl']['validation']['module']='float';
$ddm4_elements['data']['cron_artikel_1_anzahl']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_anzahl']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_anzahl']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_anzahl']['_search']['enabled']=false;

/*
 * Data: Beschreibung
 */
$ddm4_elements['data']['cron_artikel_1_beschreibung']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung']['module']='textarea';
$ddm4_elements['data']['cron_artikel_1_beschreibung']['name']='cron_artikel_1_beschreibung';
$ddm4_elements['data']['cron_artikel_1_beschreibung']['title']='Beschreibung';
$ddm4_elements['data']['cron_artikel_1_beschreibung']['options']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung']['options']['order']=true;
$ddm4_elements['data']['cron_artikel_1_beschreibung']['options']['search']=true;
$ddm4_elements['data']['cron_artikel_1_beschreibung']['options']['required']=true;
$ddm4_elements['data']['cron_artikel_1_beschreibung']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung']['validation']['length_min']=6;
$ddm4_elements['data']['cron_artikel_1_beschreibung']['validation']['length_max']=10000;
$ddm4_elements['data']['cron_artikel_1_beschreibung']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_beschreibung']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung']['_search']['enabled']=false;

/*
 * Data: Beschreibung ausblenden
 */
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['module']='yesno';
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['title']='Beschreibung ausblenden';
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['name']='cron_artikel_1_beschreibung_ausblenden';
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['options']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['options']['default_value']=0;
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_beschreibung_ausblenden']['_search']['enabled']=false;

/*
 * Data: Zusatzbeschreibung
 */
$ddm4_elements['data']['cron_artikel_1_zusatz']=[];
$ddm4_elements['data']['cron_artikel_1_zusatz']['module']='textarea';
$ddm4_elements['data']['cron_artikel_1_zusatz']['title']='Zusatzbeschreibung';
$ddm4_elements['data']['cron_artikel_1_zusatz']['name']='cron_artikel_1_zusatz';
$ddm4_elements['data']['cron_artikel_1_zusatz']['options']=[];
$ddm4_elements['data']['cron_artikel_1_zusatz']['options']['order']=true;
$ddm4_elements['data']['cron_artikel_1_zusatz']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_zusatz']['validation']['length_min']=0;
$ddm4_elements['data']['cron_artikel_1_zusatz']['validation']['length_max']=10000;
$ddm4_elements['data']['cron_artikel_1_zusatz']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_zusatz']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_zusatz']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_zusatz']['_search']['enabled']=false;

/*
 * Data: Typ
 */
$ddm4_elements['data']['cron_artikel_1_typ']=[];
$ddm4_elements['data']['cron_artikel_1_typ']['module']='select';
$ddm4_elements['data']['cron_artikel_1_typ']['title']='Typ';
$ddm4_elements['data']['cron_artikel_1_typ']['name']='cron_artikel_1_typ';
$ddm4_elements['data']['cron_artikel_1_typ']['options']=[];
$ddm4_elements['data']['cron_artikel_1_typ']['options']['order']=true;
$ddm4_elements['data']['cron_artikel_1_typ']['options']['required']=true;
$ddm4_elements['data']['cron_artikel_1_typ']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelTypen(false);
$ddm4_elements['data']['cron_artikel_1_typ']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_typ']['validation']['module']='integer';
$ddm4_elements['data']['cron_artikel_1_typ']['validation']['length_min']=1;
$ddm4_elements['data']['cron_artikel_1_typ']['validation']['length_max']=1;
$ddm4_elements['data']['cron_artikel_1_typ']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_typ']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_typ']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_typ']['_search']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_typ']['_add']=[];
$ddm4_elements['data']['cron_artikel_1_typ']['_add']['options']=[];
$ddm4_elements['data']['cron_artikel_1_typ']['_add']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelTypen();

/*
 * Data: Preis
 */
$ddm4_elements['data']['cron_artikel_1_preis']=[];
$ddm4_elements['data']['cron_artikel_1_preis']['module']='text';
$ddm4_elements['data']['cron_artikel_1_preis']['title']='Preis';
$ddm4_elements['data']['cron_artikel_1_preis']['name']='cron_artikel_1_preis';
$ddm4_elements['data']['cron_artikel_1_preis']['options']=[];
$ddm4_elements['data']['cron_artikel_1_preis']['options']['order']=true;
$ddm4_elements['data']['cron_artikel_1_preis']['options']['required']=true;
$ddm4_elements['data']['cron_artikel_1_preis']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_preis']['validation']['module']='float';
$ddm4_elements['data']['cron_artikel_1_preis']['validation']['length_min']=0;
$ddm4_elements['data']['cron_artikel_1_preis']['validation']['length_max']=11;
$ddm4_elements['data']['cron_artikel_1_preis']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_preis']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_preis']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_preis']['_search']['enabled']=false;

/*
 * Data: MwSt
 */
$ddm4_elements['data']['cron_artikel_1_mwst']=[];
$ddm4_elements['data']['cron_artikel_1_mwst']['module']='select';
$ddm4_elements['data']['cron_artikel_1_mwst']['title']='MwSt';
$ddm4_elements['data']['cron_artikel_1_mwst']['name']='cron_artikel_1_mwst';
$ddm4_elements['data']['cron_artikel_1_mwst']['options']=[];
$ddm4_elements['data']['cron_artikel_1_mwst']['options']['order']=true;
$ddm4_elements['data']['cron_artikel_1_mwst']['options']['required']=true;
$ddm4_elements['data']['cron_artikel_1_mwst']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelMwSt(false, 'mwst_titel');
$ddm4_elements['data']['cron_artikel_1_mwst']['validation']=[];
$ddm4_elements['data']['cron_artikel_1_mwst']['validation']['module']='integer';
$ddm4_elements['data']['cron_artikel_1_mwst']['validation']['length_min']=1;
$ddm4_elements['data']['cron_artikel_1_mwst']['validation']['length_max']=3;
$ddm4_elements['data']['cron_artikel_1_mwst']['_list']=[];
$ddm4_elements['data']['cron_artikel_1_mwst']['_list']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_mwst']['_search']=[];
$ddm4_elements['data']['cron_artikel_1_mwst']['_search']['enabled']=false;
$ddm4_elements['data']['cron_artikel_1_mwst']['_add']=[];
$ddm4_elements['data']['cron_artikel_1_mwst']['_add']['options']=[];
$ddm4_elements['data']['cron_artikel_1_mwst']['_add']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikelMwSt(true, 'mwst_titel');

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='cron_';
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='cron_';

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

if (in_array(\osWFrame\Core\Settings::getAction(), ['add', 'doadd', 'edit', 'doedit', 'delete', 'dodelete'])) {
	$css=[];
	$ajax=[];

	foreach ($element_values_all as $element) {
		$css[]='.ddm_element_cron_artikel_1_'.$element.' {display:none;}';
	}

	$_ajax='
$(window).on("load", function() {
	ddm3_formular_'.$osW_DDM4->getName().'_artikel("");
';
	$_ajax.='	$("select[name=\'cron_artikel_1_id\']").change(function(){ddm3_formular_'.$osW_DDM4->getName().'_artikel(1, true);});
';
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
			if ($("select[name=\'cron_artikel_"+position+"_id\']").val()>0) {
				$(".ddm_element_cron_artikel_"+position+"_'.implode('").fadeIn(0); $(".ddm_element_cron_artikel_"+position+"_', $element_values_all).'").fadeIn(0);
				$.ajax({
					url: "'.$osW_DDM4->getTemplate()->buildhrefLink($osW_DDM4->getDirectModule(), $osW_DDM4->getDirectParameters()).'",
					type: "POST",
					cache: false,
					async: false,
					dataType: "json",
					data: {
						action: "getArtikelDetails",
						artikel_id: $("select[name=\'cron_artikel_"+position+"_id\']").val()
					}
				})
				.done(function(data) {
					if (data.artikel_nr) {
						$("input[name=\'cron_artikel_"+position+"_nr\']").val(data.artikel_nr);
					}
					if (data.artikel_kurz) {
						$("input[name=\'cron_artikel_"+position+"_kurz\']").val(data.artikel_kurz);
					}
					if (data.artikel_beschreibung) {
						$("#cron_artikel_"+position+"_beschreibung").val(data.artikel_beschreibung);
					}
					if (data.artikel_beschreibung_ausblenden) {
						if (data.artikel_beschreibung_ausblenden==1) {
							$("#cron_artikel_"+position+"_beschreibung_ausblenden0").prop("checked", true);
							$("#cron_artikel_"+position+"_beschreibung_ausblenden1").prop("checked", false);
						} else {
							$("#cron_artikel_"+position+"_beschreibung_ausblenden0").prop("checked", false);
							$("#cron_artikel_"+position+"_beschreibung_ausblenden1").prop("checked", true);
						}
					}
					if (data.artikel_preis) {
						$("#cron_artikel_"+position+"_preis").val(data.artikel_preis);
					}
					if (data.artikel_typ) {
						$("#cron_artikel_"+position+"_typ").selectpicker("val", data.artikel_typ);
					}
					if (data.artikel_mwst) {
						$("#cron_artikel_"+position+"_mwst").selectpicker("val", data.artikel_mwst);
					}
				});
			} else {
				$(".ddm_element_cron_artikel_"+position+"_'.implode('").fadeOut(0); $(".ddm_element_cron_artikel_"+position+"_', $element_values_all).'").fadeOut(0);
			}
		}
	}
';
	/*
		for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
			for ($i=1; $i<=\JBSNewMedia\WebERP\Verwaltung::getPositionsMax(); $i++) {
				$_ajax.='	cron_artikel=$("select[name=\'cron_artikel_'.$i.'_id\']").val();
		values=["cron_artikel_'.$i.'_'.implode('", "cron_artikel_'.$i.'_', $element_values).'"];
		set_values=[];
	';
				foreach ($_ajax_data as $element=>$values) {
					$_ajax.='	if (cron_artikel=="'.$element.'") {
			set_values=["'.implode('_'.$i.'","', $values).'_'.$i.'"];
		}
	';
				}
				$_ajax.='	$.each(values, function( key, value ) {
				$(".ddm_element_"+value).fadeOut(0);
		});
		$.each(set_values, function( key, value ) {
				$(".ddm_element_"+value).fadeIn(0);
		});
	';
			}
		}
	*/
	$_ajax.='
	lastregion=0;
	if ($("select[name=\'cron_artikel_1_id\']").val()>0) {
		lastregion=1;
	}
	lastregion=lastregion+1;
	if (1<=lastregion) {
		$(".ddm_element_cron_artikel_1_'.implode('").fadeIn(0); $(".ddm_element_cron_artikel_1_', $element_values).'").fadeIn(0);
		if ($("select[name=\'cron_artikel_1_id\']").val()>0) {
			$(".ddm_element_cron_artikel_1_'.implode('").fadeIn(0); $(".ddm_element_cron_artikel_1_', $element_values_all).'").fadeIn(0);
		}
	} else {
		$(".ddm_element_cron_artikel_1_'.implode('").fadeOut(0); $(".ddm_element_cron_artikel_1_', $element_values).'").fadeOut(0);
	}
}';
	$ajax[]=$_ajax;

	foreach ($ajax_data['kunde_id'] as $element) {
		$css[]='.ddm_element_'.$element.' {display:none;}';
	}

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