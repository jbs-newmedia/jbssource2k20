<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
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
$ddm4_object['database']['table']='weberp_config';
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
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_config', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Allgemein'];
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Geschäftlich'];
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Server'];
$navigation_links[4]=['navigation_id'=>4, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Kunden [Anreden]'];
$navigation_links[5]=['navigation_id'=>5, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Kunden [Länder]'];
$navigation_links[6]=['navigation_id'=>6, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Artikel [Typen]'];
$navigation_links[7]=['navigation_id'=>7, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Artikel [MwSt]'];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [4,5,6,7])) {
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
	 * Send: Firma
	 */
	$ddm4_elements['send']['vis2_weberp_firma']=[];
	$ddm4_elements['send']['vis2_weberp_firma']['module']='text';
	$ddm4_elements['send']['vis2_weberp_firma']['title']='Firma';
	$ddm4_elements['send']['vis2_weberp_firma']['name']='vis2_weberp_firma';
	$ddm4_elements['send']['vis2_weberp_firma']['options']=[];
	$ddm4_elements['send']['vis2_weberp_firma']['options']['required']=true;
	$ddm4_elements['send']['vis2_weberp_firma']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('firma');
	$ddm4_elements['send']['vis2_weberp_firma']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_firma']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_firma']['validation']['length_min']=1;
	$ddm4_elements['send']['vis2_weberp_firma']['validation']['length_max']=128;

	/*
	 * Send: Strasse
	 */
	$ddm4_elements['send']['vis2_weberp_strasse']=[];
	$ddm4_elements['send']['vis2_weberp_strasse']['module']='text';
	$ddm4_elements['send']['vis2_weberp_strasse']['title']='Strasse';
	$ddm4_elements['send']['vis2_weberp_strasse']['name']='vis2_weberp_strasse';
	$ddm4_elements['send']['vis2_weberp_strasse']['options']=[];
	$ddm4_elements['send']['vis2_weberp_strasse']['options']['required']=true;
	$ddm4_elements['send']['vis2_weberp_strasse']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('strasse');
	$ddm4_elements['send']['vis2_weberp_strasse']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_strasse']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_strasse']['validation']['length_min']=1;
	$ddm4_elements['send']['vis2_weberp_strasse']['validation']['length_max']=128;

	/*
	 * Send: Postleitzahl
	 */
	$ddm4_elements['send']['vis2_weberp_plz']=[];
	$ddm4_elements['send']['vis2_weberp_plz']['module']='text';
	$ddm4_elements['send']['vis2_weberp_plz']['title']='Postleitzahl';
	$ddm4_elements['send']['vis2_weberp_plz']['name']='vis2_weberp_plz';
	$ddm4_elements['send']['vis2_weberp_plz']['options']=[];
	$ddm4_elements['send']['vis2_weberp_plz']['options']['required']=true;
	$ddm4_elements['send']['vis2_weberp_plz']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('plz');
	$ddm4_elements['send']['vis2_weberp_plz']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_plz']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_plz']['validation']['length_min']=4;
	$ddm4_elements['send']['vis2_weberp_plz']['validation']['length_max']=6;

	/*
	 * Send: Ort
	 */
	$ddm4_elements['send']['vis2_weberp_ort']=[];
	$ddm4_elements['send']['vis2_weberp_ort']['module']='text';
	$ddm4_elements['send']['vis2_weberp_ort']['title']='Ort';
	$ddm4_elements['send']['vis2_weberp_ort']['name']='vis2_weberp_ort';
	$ddm4_elements['send']['vis2_weberp_ort']['options']=[];
	$ddm4_elements['send']['vis2_weberp_ort']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('ort');
	$ddm4_elements['send']['vis2_weberp_ort']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_ort']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_ort']['validation']['length_max']=128;

	/*
	 * Send: Land
	 */
	$ddm4_elements['send']['vis2_weberp_land']=[];
	$ddm4_elements['send']['vis2_weberp_land']['module']='text';
	$ddm4_elements['send']['vis2_weberp_land']['title']='Land';
	$ddm4_elements['send']['vis2_weberp_land']['name']='vis2_weberp_land';
	$ddm4_elements['send']['vis2_weberp_land']['options']=[];
	$ddm4_elements['send']['vis2_weberp_land']['options']['required']=true;
	$ddm4_elements['send']['vis2_weberp_land']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('land');
	$ddm4_elements['send']['vis2_weberp_land']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_land']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_land']['validation']['length_min']=1;
	$ddm4_elements['send']['vis2_weberp_land']['validation']['length_max']=128;

	/*
	 * Send: Telefon
	 */
	$ddm4_elements['send']['vis2_weberp_telefon']=[];
	$ddm4_elements['send']['vis2_weberp_telefon']['module']='text';
	$ddm4_elements['send']['vis2_weberp_telefon']['title']='Telefon';
	$ddm4_elements['send']['vis2_weberp_telefon']['name']='vis2_weberp_telefon';
	$ddm4_elements['send']['vis2_weberp_telefon']['options']=[];
	$ddm4_elements['send']['vis2_weberp_telefon']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('telefon');
	$ddm4_elements['send']['vis2_weberp_telefon']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_telefon']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_telefon']['validation']['length_max']=128;

	/*
	 * Send: Fax
	 */
	$ddm4_elements['send']['vis2_weberp_fax']=[];
	$ddm4_elements['send']['vis2_weberp_fax']['module']='text';
	$ddm4_elements['send']['vis2_weberp_fax']['title']='Fax';
	$ddm4_elements['send']['vis2_weberp_fax']['name']='vis2_weberp_fax';
	$ddm4_elements['send']['vis2_weberp_fax']['options']=[];
	$ddm4_elements['send']['vis2_weberp_fax']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('fax');
	$ddm4_elements['send']['vis2_weberp_fax']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_fax']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_fax']['validation']['length_max']=128;

	/*
	 * Send: Mobil
	 */
	$ddm4_elements['send']['vis2_weberp_mobil']=[];
	$ddm4_elements['send']['vis2_weberp_mobil']['module']='text';
	$ddm4_elements['send']['vis2_weberp_mobil']['title']='Mobil';
	$ddm4_elements['send']['vis2_weberp_mobil']['name']='vis2_weberp_mobil';
	$ddm4_elements['send']['vis2_weberp_mobil']['options']=[];
	$ddm4_elements['send']['vis2_weberp_mobil']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('mobil');
	$ddm4_elements['send']['vis2_weberp_mobil']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_mobil']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_mobil']['validation']['length_max']=128;

	/*
	 * Send: E-Mail
	 */
	$ddm4_elements['send']['vis2_weberp_email']=[];
	$ddm4_elements['send']['vis2_weberp_email']['module']='text';
	$ddm4_elements['send']['vis2_weberp_email']['title']='E-Mail';
	$ddm4_elements['send']['vis2_weberp_email']['name']='vis2_weberp_email';
	$ddm4_elements['send']['vis2_weberp_email']['options']=[];
	$ddm4_elements['send']['vis2_weberp_email']['options']['required']=true;
	$ddm4_elements['send']['vis2_weberp_email']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('email');
	$ddm4_elements['send']['vis2_weberp_email']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_email']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_email']['validation']['length_min']=1;
	$ddm4_elements['send']['vis2_weberp_email']['validation']['length_max']=128;
	$ddm4_elements['send']['vis2_weberp_email']['validation']['filter']=[];
	$ddm4_elements['send']['vis2_weberp_email']['validation']['filter']['email_idna']=[];

	/*
	 * Send: E-Mail Buchhaltung
	 */
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']=[];
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['module']='text';
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['title']='E-Mail Buchhaltung';
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['name']='vis2_weberp_email_buchhaltung';
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['options']=[];
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('email_buchhaltung');
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['validation']['length_max']=128;
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['validation']['filter']=[];
	$ddm4_elements['send']['vis2_weberp_email_buchhaltung']['validation']['filter']['email_idna']=[];

	/*
	 * Send: Homepage
	 */
	$ddm4_elements['send']['vis2_weberp_homepage']=[];
	$ddm4_elements['send']['vis2_weberp_homepage']['module']='text';
	$ddm4_elements['send']['vis2_weberp_homepage']['title']='Homepage';
	$ddm4_elements['send']['vis2_weberp_homepage']['name']='vis2_weberp_homepage';
	$ddm4_elements['send']['vis2_weberp_homepage']['options']=[];
	$ddm4_elements['send']['vis2_weberp_homepage']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('homepage');
	$ddm4_elements['send']['vis2_weberp_homepage']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_homepage']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_homepage']['validation']['length_max']=128;
	$ddm4_elements['send']['vis2_weberp_homepage']['validation']['filter']=[];
	$ddm4_elements['send']['vis2_weberp_homepage']['validation']['filter']['url_idna']=[];

	/*
	 * Send: Druckprofil
	 */
	$ddm4_elements['send']['vis2_weberp_profile']=[];
	$ddm4_elements['send']['vis2_weberp_profile']['module']='select';
	$ddm4_elements['send']['vis2_weberp_profile']['title']='Druckprofil';
	$ddm4_elements['send']['vis2_weberp_profile']['name']='vis2_weberp_profile';
	$ddm4_elements['send']['vis2_weberp_profile']['options']=[];
	$ddm4_elements['send']['vis2_weberp_profile']['options']['required']=true;
	$ddm4_elements['send']['vis2_weberp_profile']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('profile');
	$ddm4_elements['send']['vis2_weberp_profile']['options']['data']=$VIS2_WebERP_Verwaltung->getProfiles();
	$ddm4_elements['send']['vis2_weberp_profile']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_profile']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_profile']['validation']['length_min']=1;
	$ddm4_elements['send']['vis2_weberp_profile']['validation']['length_max']=32;

	/*
	 * Send: Cronuser
	 */
	$ddm4_elements['send']['vis2_weberp_cronuser']=[];
	$ddm4_elements['send']['vis2_weberp_cronuser']['module']='select';
	$ddm4_elements['send']['vis2_weberp_cronuser']['title']='Benutzer bei Cronjobs';
	$ddm4_elements['send']['vis2_weberp_cronuser']['name']='vis2_weberp_cronuser';
	$ddm4_elements['send']['vis2_weberp_cronuser']['options']=[];
	$ddm4_elements['send']['vis2_weberp_cronuser']['options']['required']=true;
	$ddm4_elements['send']['vis2_weberp_cronuser']['options']['default_value']=$VIS2_WebERP_Verwaltung->getIntVar('cronuser');
	$ddm4_elements['send']['vis2_weberp_cronuser']['options']['data']=\VIS2\Core\Manager::getUsers();
	$ddm4_elements['send']['vis2_weberp_cronuser']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_cronuser']['validation']['module']='integer';
	$ddm4_elements['send']['vis2_weberp_cronuser']['validation']['length_min']=1;
	$ddm4_elements['send']['vis2_weberp_cronuser']['validation']['length_max']=11;

	/*
	 * Send: Submit
	 */
	$ddm4_elements['send']['submit']=[];
	$ddm4_elements['send']['submit']['module']='submit';

	/*
	 * Finish: VIS2_WebERP_Settings
	 */
	$ddm4_elements['finish']['vis2_weberp_settings']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['module']='vis2_weberp_settings';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['key']='firma';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['value']='vis2_weberp_firma';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['key']='strasse';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['value']='vis2_weberp_strasse';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['key']='plz';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['value']='vis2_weberp_plz';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['key']='ort';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['value']='vis2_weberp_ort';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['key']='land';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['value']='vis2_weberp_land';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['key']='telefon';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['value']='vis2_weberp_telefon';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['key']='fax';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['value']='vis2_weberp_fax';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]['key']='mobil';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]['value']='vis2_weberp_mobil';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]['key']='email';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]['value']='vis2_weberp_email';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]['key']='email_buchhaltung';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]['value']='vis2_weberp_email_buchhaltung';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]['key']='homepage';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]['value']='vis2_weberp_homepage';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]['key']='profile';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]['value']='vis2_weberp_profile';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][12]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][12]['key']='cronuser';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][12]['value']='vis2_weberp_cronuser';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][12]['type']='int';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Geschäftlich
 */
if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_formular');

	/*
	 * PreView: VIS2_Navigation
	 */
	$ddm4_elements['send']['vis2_navigation']=[];
	$ddm4_elements['send']['vis2_navigation']['module']='vis2_navigation';
	$ddm4_elements['send']['vis2_navigation']['options']=[];
	$ddm4_elements['send']['vis2_navigation']['options']['data']=$navigation_links;

	/*
	 * Send: Sitz der Gesellschaft
	 */
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']=[];
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['module']='text';
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['title']='Sitz der Gesellschaft';
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['name']='vis2_weberp_gesellschaftssitz';
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['options']=[];
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('gesellschaftssitz');
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_gesellschaftssitz']['validation']['length_max']=128;

	/*
	 * Send: Geschäftsführer
	 */
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']=[];
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['module']='text';
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['title']='Geschäftsführer';
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['name']='vis2_weberp_geschaeftsfuehrer';
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['options']=[];
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('geschaeftsfuehrer');
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_geschaeftsfuehrer']['validation']['length_max']=128;

	/*
	 * Send: Registergericht
	 */
	$ddm4_elements['send']['vis2_weberp_registergericht']=[];
	$ddm4_elements['send']['vis2_weberp_registergericht']['module']='text';
	$ddm4_elements['send']['vis2_weberp_registergericht']['title']='Registergericht';
	$ddm4_elements['send']['vis2_weberp_registergericht']['name']='vis2_weberp_registergericht';
	$ddm4_elements['send']['vis2_weberp_registergericht']['options']=[];
	$ddm4_elements['send']['vis2_weberp_registergericht']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('registergericht');
	$ddm4_elements['send']['vis2_weberp_registergericht']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_registergericht']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_registergericht']['validation']['length_max']=128;

	/*
	 * Send: Handelsregisterabteilung
	 */
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']=[];
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['module']='text';
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['title']='Handelsregisterabteilung';
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['name']='vis2_weberp_handelsregister_abteilung';
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['options']=[];
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('handelsregister_abteilung');
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_handelsregister_abteilung']['validation']['length_max']=128;

	/*
	 * Send: Handelsregisternummer
	 */
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']=[];
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['module']='text';
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['title']='Handelsregisternummer';
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['name']='vis2_weberp_handelsregister_nummer';
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['options']=[];
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('handelsregister_nummer');
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_handelsregister_nummer']['validation']['length_max']=128;

	/*
	 * Send: Umsatzsteuer-Identifikationsnummer
	 */
	$ddm4_elements['send']['vis2_weberp_ustidnr']=[];
	$ddm4_elements['send']['vis2_weberp_ustidnr']['module']='text';
	$ddm4_elements['send']['vis2_weberp_ustidnr']['title']='Umsatzsteuer-Identifikationsnummer';
	$ddm4_elements['send']['vis2_weberp_ustidnr']['name']='vis2_weberp_ustidnr';
	$ddm4_elements['send']['vis2_weberp_ustidnr']['options']=[];
	$ddm4_elements['send']['vis2_weberp_ustidnr']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('ustidnr');
	$ddm4_elements['send']['vis2_weberp_ustidnr']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_ustidnr']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_ustidnr']['validation']['length_max']=128;

	/*
	 * Send: Bank
	 */
	$ddm4_elements['send']['vis2_weberp_bank']=[];
	$ddm4_elements['send']['vis2_weberp_bank']['module']='text';
	$ddm4_elements['send']['vis2_weberp_bank']['title']='Bank';
	$ddm4_elements['send']['vis2_weberp_bank']['name']='vis2_weberp_bank';
	$ddm4_elements['send']['vis2_weberp_bank']['options']=[];
	$ddm4_elements['send']['vis2_weberp_bank']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('bank');
	$ddm4_elements['send']['vis2_weberp_bank']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_bank']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_bank']['validation']['length_max']=128;

	/*
	 * Send: IBAN
	 */
	$ddm4_elements['send']['vis2_weberp_iban']=[];
	$ddm4_elements['send']['vis2_weberp_iban']['module']='text';
	$ddm4_elements['send']['vis2_weberp_iban']['title']='IBAN';
	$ddm4_elements['send']['vis2_weberp_iban']['name']='vis2_weberp_iban';
	$ddm4_elements['send']['vis2_weberp_iban']['options']=[];
	$ddm4_elements['send']['vis2_weberp_iban']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('iban');
	$ddm4_elements['send']['vis2_weberp_iban']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_iban']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_iban']['validation']['length_max']=128;

	/*
	 * Send: BIC
	 */
	$ddm4_elements['send']['vis2_weberp_bic']=[];
	$ddm4_elements['send']['vis2_weberp_bic']['module']='text';
	$ddm4_elements['send']['vis2_weberp_bic']['title']='BIC';
	$ddm4_elements['send']['vis2_weberp_bic']['name']='vis2_weberp_bic';
	$ddm4_elements['send']['vis2_weberp_bic']['options']=[];
	$ddm4_elements['send']['vis2_weberp_bic']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('bic');
	$ddm4_elements['send']['vis2_weberp_bic']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_bic']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_bic']['validation']['length_max']=128;

	/*
	 * Send: Gläubiger-Identifikationsnummer
	 */
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']=[];
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['module']='text';
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['title']='Gläubiger-Identifikationsnummer';
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['name']='vis2_weberp_glaeubigerid';
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['options']=[];
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('glaeubigerid');
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_glaeubigerid']['validation']['length_max']=128;

	/*
	 * Send: Cronjob Eingang
	 */
	$ddm4_elements['send']['vis2_weberp_cronein']=[];
	$ddm4_elements['send']['vis2_weberp_cronein']['module']='textarea';
	$ddm4_elements['send']['vis2_weberp_cronein']['title']='Cronjob Eingang';
	$ddm4_elements['send']['vis2_weberp_cronein']['name']='vis2_weberp_cronein';
	$ddm4_elements['send']['vis2_weberp_cronein']['options']=[];
	$ddm4_elements['send']['vis2_weberp_cronein']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('cronein');
	$ddm4_elements['send']['vis2_weberp_cronein']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_cronein']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_cronein']['validation']['length_max']=5000;

	/*
	 * Send: Cronjob Ausgang
	 */
	$ddm4_elements['send']['vis2_weberp_cronaus']=[];
	$ddm4_elements['send']['vis2_weberp_cronaus']['module']='textarea';
	$ddm4_elements['send']['vis2_weberp_cronaus']['title']='Cronjob Ausgang';
	$ddm4_elements['send']['vis2_weberp_cronaus']['name']='vis2_weberp_cronaus';
	$ddm4_elements['send']['vis2_weberp_cronaus']['options']=[];
	$ddm4_elements['send']['vis2_weberp_cronaus']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('cronaus');
	$ddm4_elements['send']['vis2_weberp_cronaus']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_cronaus']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_cronaus']['validation']['length_max']=5000;

	/*
	 * Send: Submit
	 */
	$ddm4_elements['send']['submit']=[];
	$ddm4_elements['send']['submit']['module']='submit';

	/*
	 * Finish: VIS2_WebERP_Settings
	 */
	$ddm4_elements['finish']['vis2_weberp_settings']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['module']='vis2_weberp_settings';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['key']='gesellschaftssitz';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['value']='vis2_weberp_gesellschaftssitz';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['key']='geschaeftsfuehrer';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['value']='vis2_weberp_geschaeftsfuehrer';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['key']='registergericht';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['value']='vis2_weberp_registergericht';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['key']='handelsregister_abteilung';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['value']='vis2_weberp_handelsregister_abteilung';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['key']='handelsregister_nummer';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['value']='vis2_weberp_handelsregister_nummer';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['key']='ustidnr';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['value']='vis2_weberp_ustidnr';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['key']='bank';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['value']='vis2_weberp_bank';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]['key']='iban';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]['value']='vis2_weberp_iban';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][7]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]['key']='bic';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]['value']='vis2_weberp_bic';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][8]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]['key']='glaeubigerid';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]['value']='vis2_weberp_glaeubigerid';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][9]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]['key']='cronein';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]['value']='vis2_weberp_cronein';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][10]['type']='text';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]['key']='cronaus';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]['value']='vis2_weberp_cronaus';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][11]['type']='text';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Server
 */
if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_formular');

	/*
	 * PreView: VIS2_Navigation
	 */
	$ddm4_elements['send']['vis2_navigation']=[];
	$ddm4_elements['send']['vis2_navigation']['module']='vis2_navigation';
	$ddm4_elements['send']['vis2_navigation']['options']=[];
	$ddm4_elements['send']['vis2_navigation']['options']['data']=$navigation_links;

	/*
	 * Send: SMTP Server
	 */
	$ddm4_elements['send']['vis2_weberp_smtp_server']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_server']['module']='text';
	$ddm4_elements['send']['vis2_weberp_smtp_server']['title']='SMTP Server';
	$ddm4_elements['send']['vis2_weberp_smtp_server']['name']='vis2_weberp_smtp_server';
	$ddm4_elements['send']['vis2_weberp_smtp_server']['options']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_server']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('smtp_server');
	$ddm4_elements['send']['vis2_weberp_smtp_server']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_server']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_smtp_server']['validation']['length_max']=128;
	$ddm4_elements['send']['vis2_weberp_smtp_server']['validation']['filter']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_server']['validation']['filter']['hostname_idna']=[];

	/*
	 * Send: SMTP Port
	 */
	$ddm4_elements['send']['vis2_weberp_smtp_port']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_port']['module']='text';
	$ddm4_elements['send']['vis2_weberp_smtp_port']['title']='SMTP Port';
	$ddm4_elements['send']['vis2_weberp_smtp_port']['name']='vis2_weberp_smtp_port';
	$ddm4_elements['send']['vis2_weberp_smtp_port']['options']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_port']['options']['default_value']=$VIS2_WebERP_Verwaltung->getIntVar('smtp_port');
	$ddm4_elements['send']['vis2_weberp_smtp_port']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_port']['validation']['module']='integer';
	$ddm4_elements['send']['vis2_weberp_smtp_port']['validation']['length_min']=0;
	$ddm4_elements['send']['vis2_weberp_smtp_port']['validation']['length_max']=5;

	/*
	 * Send: SMTP Secure
	 */
	$ddm4_elements['send']['vis2_weberp_smtp_secure']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['module']='select';
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['title']='SMTP Secure';
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['name']='vis2_weberp_smtp_secure';
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['options']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('smtp_secure');
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['options']['blank_value']=false;
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['options']['data']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['options']['data']['']='';
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['options']['data']['tls']='tls';
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['options']['data']['ssl']='ssl';
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_smtp_secure']['validation']['length_max']=8;

	/*
	 * Send: SMTP Auth
	 */
	$ddm4_elements['send']['vis2_weberp_smtp_auth']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['module']='yesno';
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['title']='SMTP Auth';
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['name']='vis2_weberp_smtp_auth';
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['options']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['options']['default_value']=$VIS2_WebERP_Verwaltung->getIntVar('smtp_auth');
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['validation']['module']='integer';
	$ddm4_elements['send']['vis2_weberp_smtp_auth']['validation']['length_max']=1;

	/*
	 * Send: SMTP AutoTLS
	 */
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['module']='yesno';
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['title']='SMTP AutoTLS';
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['name']='vis2_weberp_smtp_autotls';
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['options']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['options']['default_value']=$VIS2_WebERP_Verwaltung->getIntVar('smtp_autotls');
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['validation']['module']='integer';
	$ddm4_elements['send']['vis2_weberp_smtp_autotls']['validation']['length_max']=1;

	/*
	 * Send: SMTP Username
	 */
	$ddm4_elements['send']['vis2_weberp_smtp_username']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_username']['module']='text';
	$ddm4_elements['send']['vis2_weberp_smtp_username']['title']='SMTP Username';
	$ddm4_elements['send']['vis2_weberp_smtp_username']['name']='vis2_weberp_smtp_username';
	$ddm4_elements['send']['vis2_weberp_smtp_username']['options']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_username']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('smtp_username');
	$ddm4_elements['send']['vis2_weberp_smtp_username']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_username']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_smtp_username']['validation']['length_max']=128;

	/*
	 * Send: SMTP Passwort
	 */
	$ddm4_elements['send']['vis2_weberp_smtp_password']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_password']['module']='text';
	$ddm4_elements['send']['vis2_weberp_smtp_password']['title']='SMTP Passwort';
	$ddm4_elements['send']['vis2_weberp_smtp_password']['name']='vis2_weberp_smtp_password';
	$ddm4_elements['send']['vis2_weberp_smtp_password']['options']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_password']['options']['default_value']=$VIS2_WebERP_Verwaltung->getStringVar('smtp_password');
	$ddm4_elements['send']['vis2_weberp_smtp_password']['validation']=[];
	$ddm4_elements['send']['vis2_weberp_smtp_password']['validation']['module']='string';
	$ddm4_elements['send']['vis2_weberp_smtp_password']['validation']['length_max']=128;

	/*
	 * Send: Submit
	 */
	$ddm4_elements['send']['submit']=[];
	$ddm4_elements['send']['submit']['module']='submit';

	/*
	 * Finish: VIS2_WebERP_Settings
	 */
	$ddm4_elements['finish']['vis2_weberp_settings']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['module']='vis2_weberp_settings';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data']=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['key']='smtp_server';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['value']='vis2_weberp_smtp_server';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][0]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['key']='smtp_port';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['value']='vis2_weberp_smtp_port';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][1]['type']='int';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['key']='smtp_secure';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['value']='vis2_weberp_smtp_secure';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][2]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['key']='smtp_auth';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['value']='vis2_weberp_smtp_auth';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][3]['type']='int';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['key']='smtp_autotls';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['value']='vis2_weberp_smtp_autotls';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][4]['type']='int';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['key']='smtp_username';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['value']='vis2_weberp_smtp_username';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][5]['type']='string';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]=[];
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['key']='smtp_password';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['value']='vis2_weberp_smtp_password';
	$ddm4_elements['finish']['vis2_weberp_settings']['options']['data'][6]['type']='string';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Kunden [Anreden]
 */
if (in_array($ddm_navigation_id, [4])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'weberp_kunde_anrede', 'database');
	$osW_DDM4->setGroupOption('index', 'anrede_id', 'database');
	$osW_DDM4->setGroupOption('order', ['anrede_titel'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['anrede_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Anreden vorhanden';
	$messages['search_title']='Anreden durchsuchen';
	$messages['add_title']='Neue Anrede anlegen';
	$messages['add_success_title']='Anrede wurde erfolgreich angelegt';
	$messages['add_error_title']='Anrede konnte nicht angelegt werden';
	$messages['edit_title']='Anrede editieren';
	$messages['edit_load_error_title']='Anrede wurde nicht gefunden';
	$messages['edit_success_title']='Anrede wurde erfolgreich editiert';
	$messages['edit_error_title']='Anrede konnte nicht editiert werden';
	$messages['delete_title']='Anrede löschen';
	$messages['delete_load_error_title']='Anrede wurde nicht gefunden';
	$messages['delete_success_title']='Anrede wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Anrede konnte nicht gelöscht werden';
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
	$ddm4_elements['data']['anrede_titel']=[];
	$ddm4_elements['data']['anrede_titel']['module']='text';
	$ddm4_elements['data']['anrede_titel']['title']='Titel';
	$ddm4_elements['data']['anrede_titel']['name']='anrede_titel';
	$ddm4_elements['data']['anrede_titel']['options']=[];
	$ddm4_elements['data']['anrede_titel']['options']['order']=true;
	$ddm4_elements['data']['anrede_titel']['options']['search']=true;
	$ddm4_elements['data']['anrede_titel']['validation']=[];
	$ddm4_elements['data']['anrede_titel']['validation']['length_min']=0;
	$ddm4_elements['data']['anrede_titel']['validation']['length_max']=16;

	/*
	 * Data: Aktiviert
	 */
	$ddm4_elements['data']['anrede_ispublic']=[];
	$ddm4_elements['data']['anrede_ispublic']['module']='yesno';
	$ddm4_elements['data']['anrede_ispublic']['title']='Aktiviert';
	$ddm4_elements['data']['anrede_ispublic']['name']='anrede_ispublic';
	$ddm4_elements['data']['anrede_ispublic']['options']=[];
	$ddm4_elements['data']['anrede_ispublic']['options']['required']=true;
	$ddm4_elements['data']['anrede_ispublic']['options']['default_value']=1;

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
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='anrede_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='anrede_';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Kunden [Länder]
 */
if (in_array($ddm_navigation_id, [5])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'weberp_kunde_land', 'database');
	$osW_DDM4->setGroupOption('index', 'land_id', 'database');
	$osW_DDM4->setGroupOption('order', ['land_titel'=>'asc'], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['land_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);

	$messages=[];
	$messages['createupdate_title']='Datensatzinformationen';
	$messages['data_noresults']='Keine Länder vorhanden';
	$messages['search_title']='Länder durchsuchen';
	$messages['add_title']='Neues Land anlegen';
	$messages['add_success_title']='Land wurde erfolgreich angelegt';
	$messages['add_error_title']='Land konnte nicht angelegt werden';
	$messages['edit_title']='Land editieren';
	$messages['edit_load_error_title']='Land wurde nicht gefunden';
	$messages['edit_success_title']='Land wurde erfolgreich editiert';
	$messages['edit_error_title']='Land konnte nicht editiert werden';
	$messages['delete_title']='Land löschen';
	$messages['delete_load_error_title']='Land wurde nicht gefunden';
	$messages['delete_success_title']='Land wurde erfolgreich gelöscht';
	$messages['delete_error_title']='Land konnte nicht gelöscht werden';
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
	$ddm4_elements['data']['land_titel']=[];
	$ddm4_elements['data']['land_titel']['module']='text';
	$ddm4_elements['data']['land_titel']['title']='Titel';
	$ddm4_elements['data']['land_titel']['name']='land_titel';
	$ddm4_elements['data']['land_titel']['options']=[];
	$ddm4_elements['data']['land_titel']['options']['order']=true;
	$ddm4_elements['data']['land_titel']['options']['search']=true;
	$ddm4_elements['data']['land_titel']['validation']=[];
	$ddm4_elements['data']['land_titel']['validation']['length_min']=0;
	$ddm4_elements['data']['land_titel']['validation']['length_max']=16;

	/*
	 * Data: Aktiviert
	 */
	$ddm4_elements['data']['land_ispublic']=[];
	$ddm4_elements['data']['land_ispublic']['module']='yesno';
	$ddm4_elements['data']['land_ispublic']['title']='Aktiviert';
	$ddm4_elements['data']['land_ispublic']['name']='land_ispublic';
	$ddm4_elements['data']['land_ispublic']['options']=[];
	$ddm4_elements['data']['land_ispublic']['options']['required']=true;
	$ddm4_elements['data']['land_ispublic']['options']['default_value']=1;

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
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='land_';
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='land_';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

/*
 * Artikel [Typen]
 */
if (in_array($ddm_navigation_id, [6])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'weberp_artikel_typ', 'database');
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
	 * Data: Titel (Einzahl)
	 */
	$ddm4_elements['data']['typ_titel_einzahl']=[];
	$ddm4_elements['data']['typ_titel_einzahl']['module']='text';
	$ddm4_elements['data']['typ_titel_einzahl']['title']='Titel (Einzahl)';
	$ddm4_elements['data']['typ_titel_einzahl']['name']='typ_titel_einzahl';
	$ddm4_elements['data']['typ_titel_einzahl']['options']=[];
	$ddm4_elements['data']['typ_titel_einzahl']['options']['order']=true;
	$ddm4_elements['data']['typ_titel_einzahl']['options']['search']=true;
	$ddm4_elements['data']['typ_titel_einzahl']['options']['required']=true;
	$ddm4_elements['data']['typ_titel_einzahl']['validation']=[];
	$ddm4_elements['data']['typ_titel_einzahl']['validation']['length_min']=1;
	$ddm4_elements['data']['typ_titel_einzahl']['validation']['length_max']=32;
	$ddm4_elements['data']['typ_titel_einzahl']['_list']=[];
	$ddm4_elements['data']['typ_titel_einzahl']['_list']['enabled']=false;

	/*
	 * Data: Titel (Mehrzahl)
	 */
	$ddm4_elements['data']['typ_titel_mehrzahl']=[];
	$ddm4_elements['data']['typ_titel_mehrzahl']['module']='text';
	$ddm4_elements['data']['typ_titel_mehrzahl']['title']='Titel (Mehrzahl)';
	$ddm4_elements['data']['typ_titel_mehrzahl']['name']='typ_titel_mehrzahl';
	$ddm4_elements['data']['typ_titel_mehrzahl']['options']=[];
	$ddm4_elements['data']['typ_titel_mehrzahl']['options']['order']=true;
	$ddm4_elements['data']['typ_titel_mehrzahl']['options']['search']=true;
	$ddm4_elements['data']['typ_titel_mehrzahl']['options']['required']=true;
	$ddm4_elements['data']['typ_titel_mehrzahl']['validation']=[];
	$ddm4_elements['data']['typ_titel_mehrzahl']['validation']['length_min']=1;
	$ddm4_elements['data']['typ_titel_mehrzahl']['validation']['length_max']=32;
	$ddm4_elements['data']['typ_titel_mehrzahl']['_list']=[];
	$ddm4_elements['data']['typ_titel_mehrzahl']['_list']['enabled']=false;

	/*
	 * Data: Kategorie
	 */
	$ddm4_elements['data']['typ_category']=[];
	$ddm4_elements['data']['typ_category']['module']='select';
	$ddm4_elements['data']['typ_category']['title']='Kategorie';
	$ddm4_elements['data']['typ_category']['name']='typ_category';
	$ddm4_elements['data']['typ_category']['options']=[];
	$ddm4_elements['data']['typ_category']['options']['order']=true;
	$ddm4_elements['data']['typ_category']['options']['search']=true;
	$ddm4_elements['data']['typ_category']['options']['required']=true;
	$ddm4_elements['data']['typ_category']['options']['data']=[];
	$ddm4_elements['data']['typ_category']['options']['data']['']='';
	$ddm4_elements['data']['typ_category']['options']['data']['1']='Ware';
	$ddm4_elements['data']['typ_category']['options']['data']['2']='Dienstleistung';
	$ddm4_elements['data']['typ_category']['validation']=[];
	$ddm4_elements['data']['typ_category']['validation']['module']='integer';
	$ddm4_elements['data']['typ_category']['validation']['length_min']=1;
	$ddm4_elements['data']['typ_category']['validation']['length_max']=11;
	$ddm4_elements['data']['typ_category']['validation']['value_min']=1;

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
 * Artikel [MwSt.]
 */
if (in_array($ddm_navigation_id, [7])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_datatables');
	$osW_DDM4->setGroupOption('table', 'weberp_artikel_mwst', 'database');
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
	$ddm4_elements['data']['mwst_titel']['validation']['value_min']=0;
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