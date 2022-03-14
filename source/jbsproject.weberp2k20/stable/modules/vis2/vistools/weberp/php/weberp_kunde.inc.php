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
$ddm4_object['messages']['data_noresults']='Keine Kunden vorhanden';
$ddm4_object['messages']['search_title']='Kunden durchsuchen';
$ddm4_object['messages']['add_title']='Neuen Kunde anlegen';
$ddm4_object['messages']['add_success_title']='Kunde wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Kunde konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Kunde editieren';
$ddm4_object['messages']['edit_load_error_title']='Kunde wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Kunde wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Kunde konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Kunde löschen';
$ddm4_object['messages']['delete_load_error_title']='Kunde wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Kunde wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Kunde konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_kunde';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='kunde_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['kunde_nr']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_kunde', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(kunde_id) AS counter FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id: AND kunde_ispublic=:kunde_ispublic:');
$QselectCount->bindTable(':table_weberp_kunde:', 'weberp_kunde');
$QselectCount->bindInt(':kunde_ispublic:', 1);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Aktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(kunde_id) AS counter FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id: AND kunde_ispublic=:kunde_ispublic:');
$QselectCount->bindTable(':table_weberp_kunde:', 'weberp_kunde');
$QselectCount->bindInt(':kunde_ispublic:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Inaktiv', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(kunde_id) AS counter FROM :table_weberp_kunde: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_kunde:', 'weberp_kunde');
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
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'kunde_ispublic', 'operator'=>'=', 'value'=>1], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'kunde_ispublic', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
	$osW_DDM4->setGroupOption('status_keys', ['kunde_ispublic'=>[['value'=>'Nein', 'class'=>'danger']]]);
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
 * Data: KdNr
 */
$ddm4_elements['data']['kunde_nr']=[];
$ddm4_elements['data']['kunde_nr']['module']='autovalue';
$ddm4_elements['data']['kunde_nr']['title']='KdNr';
$ddm4_elements['data']['kunde_nr']['name']='kunde_nr';
$ddm4_elements['data']['kunde_nr']['options']=[];
$ddm4_elements['data']['kunde_nr']['options']['order']=true;
$ddm4_elements['data']['kunde_nr']['options']['search']=true;
$ddm4_elements['data']['kunde_nr']['options']['label']='wird automatisch vergeben';
$ddm4_elements['data']['kunde_nr']['options']['default_value']=40001;
$ddm4_elements['data']['kunde_nr']['options']['filter_use']=false;
$ddm4_elements['data']['kunde_nr']['validation']=[];
$ddm4_elements['data']['kunde_nr']['validation']['length_min']=5;
$ddm4_elements['data']['kunde_nr']['validation']['length_max']=5;

/*
 * Data: Gewerblich
 */
$ddm4_elements['data']['kunde_gewerblich']=[];
$ddm4_elements['data']['kunde_gewerblich']['module']='yesno';
$ddm4_elements['data']['kunde_gewerblich']['title']='Gewerblich';
$ddm4_elements['data']['kunde_gewerblich']['name']='kunde_gewerblich';
$ddm4_elements['data']['kunde_gewerblich']['options']=[];
$ddm4_elements['data']['kunde_gewerblich']['options']['order']=true;
$ddm4_elements['data']['kunde_gewerblich']['options']['required']=true;
$ddm4_elements['data']['kunde_gewerblich']['options']['default_value']=1;

/*
 * Data: Anrede Firma
 */
$ddm4_elements['data']['kunde_firma_anrede']=[];
$ddm4_elements['data']['kunde_firma_anrede']['module']='select';
$ddm4_elements['data']['kunde_firma_anrede']['title']='Anrede Firma';
$ddm4_elements['data']['kunde_firma_anrede']['name']='kunde_firma_anrede';
$ddm4_elements['data']['kunde_firma_anrede']['options']=[];
$ddm4_elements['data']['kunde_firma_anrede']['options']['data']=$VIS2_WebERP_Verwaltung->getKundenAnreden(false, 'anrede_titel');
$ddm4_elements['data']['kunde_firma_anrede']['validation']=[];
$ddm4_elements['data']['kunde_firma_anrede']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_firma_anrede']['validation']['length_max']=32;
$ddm4_elements['data']['kunde_firma_anrede']['_list']=[];
$ddm4_elements['data']['kunde_firma_anrede']['_list']['enabled']=false;

/*
 * Data: Firma
 */
$ddm4_elements['data']['kunde_firma']=[];
$ddm4_elements['data']['kunde_firma']['module']='text';
$ddm4_elements['data']['kunde_firma']['title']='Firma';
$ddm4_elements['data']['kunde_firma']['name']='kunde_firma';
$ddm4_elements['data']['kunde_firma']['options']=[];
$ddm4_elements['data']['kunde_firma']['options']['order']=true;
$ddm4_elements['data']['kunde_firma']['options']['search']=true;
$ddm4_elements['data']['kunde_firma']['validation']=[];
$ddm4_elements['data']['kunde_firma']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_firma']['validation']['length_max']=128;

/*
 * Data: Firma2
 */
$ddm4_elements['data']['kunde_firma2']=[];
$ddm4_elements['data']['kunde_firma2']['module']='text';
$ddm4_elements['data']['kunde_firma2']['title']='Firma2';
$ddm4_elements['data']['kunde_firma2']['name']='kunde_firma2';
$ddm4_elements['data']['kunde_firma2']['options']=[];
$ddm4_elements['data']['kunde_firma2']['options']['order']=true;
$ddm4_elements['data']['kunde_firma2']['options']['search']=true;
$ddm4_elements['data']['kunde_firma2']['validation']=[];
$ddm4_elements['data']['kunde_firma2']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_firma2']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_firma2']['_list']=[];
$ddm4_elements['data']['kunde_firma2']['_list']['enabled']=false;

/*
 * Data: ASP auf Rechnung
 */
$ddm4_elements['data']['kunde_rechungsasp']=[];
$ddm4_elements['data']['kunde_rechungsasp']['module']='yesno';
$ddm4_elements['data']['kunde_rechungsasp']['title']='ASP auf Rechnung';
$ddm4_elements['data']['kunde_rechungsasp']['name']='kunde_rechungsasp';
$ddm4_elements['data']['kunde_rechungsasp']['options']=[];
$ddm4_elements['data']['kunde_rechungsasp']['options']['required']=true;
$ddm4_elements['data']['kunde_rechungsasp']['options']['default_value']=1;
$ddm4_elements['data']['kunde_rechungsasp']['_list']=[];
$ddm4_elements['data']['kunde_rechungsasp']['_list']['enabled']=false;

/*
 * Data: Anrede
 */
$ddm4_elements['data']['kunde_anrede']=[];
$ddm4_elements['data']['kunde_anrede']['module']='select';
$ddm4_elements['data']['kunde_anrede']['title']='Anrede';
$ddm4_elements['data']['kunde_anrede']['name']='kunde_anrede';
$ddm4_elements['data']['kunde_anrede']['options']=[];
$ddm4_elements['data']['kunde_anrede']['options']['data']=$VIS2_WebERP_Verwaltung->getKundenAnreden(false, 'anrede_titel');;
$ddm4_elements['data']['kunde_anrede']['validation']=[];
$ddm4_elements['data']['kunde_anrede']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_anrede']['validation']['length_max']=32;
$ddm4_elements['data']['kunde_anrede']['_list']=[];
$ddm4_elements['data']['kunde_anrede']['_list']['enabled']=false;

/*
 * Data: Titel
 */
$ddm4_elements['data']['kunde_titel']=[];
$ddm4_elements['data']['kunde_titel']['module']='text';
$ddm4_elements['data']['kunde_titel']['title']='Titel';
$ddm4_elements['data']['kunde_titel']['name']='kunde_titel';
$ddm4_elements['data']['kunde_titel']['validation']=[];
$ddm4_elements['data']['kunde_titel']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_titel']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_titel']['_list']=[];
$ddm4_elements['data']['kunde_titel']['_list']['enabled']=false;

/*
 * Data: Vorname
 */
$ddm4_elements['data']['kunde_vorname']=[];
$ddm4_elements['data']['kunde_vorname']['module']='text';
$ddm4_elements['data']['kunde_vorname']['title']='Vorname';
$ddm4_elements['data']['kunde_vorname']['name']='kunde_vorname';
$ddm4_elements['data']['kunde_vorname']['options']=[];
$ddm4_elements['data']['kunde_vorname']['options']['order']=true;
$ddm4_elements['data']['kunde_vorname']['options']['search']=true;
$ddm4_elements['data']['kunde_vorname']['validation']=[];
$ddm4_elements['data']['kunde_vorname']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_vorname']['validation']['length_max']=128;

/*
 * Data: Nachname
 */
$ddm4_elements['data']['kunde_nachname']=[];
$ddm4_elements['data']['kunde_nachname']['module']='text';
$ddm4_elements['data']['kunde_nachname']['title']='Nachname';
$ddm4_elements['data']['kunde_nachname']['name']='kunde_nachname';
$ddm4_elements['data']['kunde_nachname']['options']=[];
$ddm4_elements['data']['kunde_nachname']['options']['order']=true;
$ddm4_elements['data']['kunde_nachname']['options']['search']=true;
$ddm4_elements['data']['kunde_nachname']['validation']=[];
$ddm4_elements['data']['kunde_nachname']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_nachname']['validation']['length_max']=128;

/*
 * Data: E-Mail
 */
$ddm4_elements['data']['kunde_email']=[];
$ddm4_elements['data']['kunde_email']['module']='text';
$ddm4_elements['data']['kunde_email']['title']='E-Mail';
$ddm4_elements['data']['kunde_email']['name']='kunde_email';
$ddm4_elements['data']['kunde_nachname']['options']=[];
$ddm4_elements['data']['kunde_nachname']['options']['search']=true;
$ddm4_elements['data']['kunde_email']['validation']=[];
$ddm4_elements['data']['kunde_email']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_email']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_email']['validation']['filter']=[];
$ddm4_elements['data']['kunde_email']['validation']['filter']['email']=[];
$ddm4_elements['data']['kunde_email']['_list']=[];
$ddm4_elements['data']['kunde_email']['_list']['enabled']=false;

/*
 * Data: Strasse/Hausnr.
 */
$ddm4_elements['data']['kunde_strasse']=[];
$ddm4_elements['data']['kunde_strasse']['module']='text';
$ddm4_elements['data']['kunde_strasse']['title']='Strasse/Hausnr.';
$ddm4_elements['data']['kunde_strasse']['name']='kunde_strasse';
$ddm4_elements['data']['kunde_nachname']['options']=[];
$ddm4_elements['data']['kunde_nachname']['options']['search']=true;
$ddm4_elements['data']['kunde_strasse']['validation']=[];
$ddm4_elements['data']['kunde_strasse']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_strasse']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_strasse']['_list']=[];
$ddm4_elements['data']['kunde_strasse']['_list']['enabled']=false;

/*
 * Data: Land
 */
$ddm4_elements['data']['kunde_land']=[];
$ddm4_elements['data']['kunde_land']['module']='select';
$ddm4_elements['data']['kunde_land']['title']='Land';
$ddm4_elements['data']['kunde_land']['name']='kunde_land';
$ddm4_elements['data']['kunde_land']['options']=[];
$ddm4_elements['data']['kunde_land']['options']['data']=$VIS2_WebERP_Verwaltung->getKundenLaender(false, 'land_titel');
$ddm4_elements['data']['kunde_land']['validation']=[];
$ddm4_elements['data']['kunde_land']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_land']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_land']['_list']=[];
$ddm4_elements['data']['kunde_land']['_list']['enabled']=false;

/*
 * Data: PLZ
 */
$ddm4_elements['data']['kunde_plz']=[];
$ddm4_elements['data']['kunde_plz']['module']='text';
$ddm4_elements['data']['kunde_plz']['title']='PLZ';
$ddm4_elements['data']['kunde_plz']['name']='kunde_plz';
$ddm4_elements['data']['kunde_plz']['validation']=[];
$ddm4_elements['data']['kunde_plz']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_plz']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_plz']['_list']=[];
$ddm4_elements['data']['kunde_plz']['_list']['enabled']=false;

/*
 * Data: Ort
 */
$ddm4_elements['data']['kunde_ort']=[];
$ddm4_elements['data']['kunde_ort']['module']='text';
$ddm4_elements['data']['kunde_ort']['title']='Ort';
$ddm4_elements['data']['kunde_ort']['name']='kunde_ort';
$ddm4_elements['data']['kunde_ort']['validation']=[];
$ddm4_elements['data']['kunde_ort']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_ort']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_ort']['_list']=[];
$ddm4_elements['data']['kunde_ort']['_list']['enabled']=false;

/*
 * Data: Telefon
 */
$ddm4_elements['data']['kunde_telefon']=[];
$ddm4_elements['data']['kunde_telefon']['module']='text';
$ddm4_elements['data']['kunde_telefon']['title']='Telefon';
$ddm4_elements['data']['kunde_telefon']['name']='kunde_telefon';
$ddm4_elements['data']['kunde_telefon']['validation']=[];
$ddm4_elements['data']['kunde_telefon']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_telefon']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_telefon']['_list']=[];
$ddm4_elements['data']['kunde_telefon']['_list']['enabled']=false;

/*
 * Data: Fax
 */
$ddm4_elements['data']['kunde_fax']=[];
$ddm4_elements['data']['kunde_fax']['module']='text';
$ddm4_elements['data']['kunde_fax']['title']='Fax';
$ddm4_elements['data']['kunde_fax']['name']='kunde_fax';
$ddm4_elements['data']['kunde_fax']['validation']=[];
$ddm4_elements['data']['kunde_fax']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_fax']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_fax']['_list']=[];
$ddm4_elements['data']['kunde_fax']['_list']['enabled']=false;

/*
 * Data: Mobil
 */
$ddm4_elements['data']['kunde_mobil']=[];
$ddm4_elements['data']['kunde_mobil']['module']='text';
$ddm4_elements['data']['kunde_mobil']['title']='Mobil';
$ddm4_elements['data']['kunde_mobil']['name']='kunde_mobil';
$ddm4_elements['data']['kunde_mobil']['validation']=[];
$ddm4_elements['data']['kunde_mobil']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_mobil']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_mobil']['_list']=[];
$ddm4_elements['data']['kunde_mobil']['_list']['enabled']=false;

/*
 * Data: Homepage
 */
$ddm4_elements['data']['kunde_homepage']=[];
$ddm4_elements['data']['kunde_homepage']['module']='text';
$ddm4_elements['data']['kunde_homepage']['title']='Homepage';
$ddm4_elements['data']['kunde_homepage']['name']='kunde_homepage';
$ddm4_elements['data']['kunde_homepage']['validation']=[];
$ddm4_elements['data']['kunde_homepage']['validation']['length_min']=0;
$ddm4_elements['data']['kunde_homepage']['validation']['length_max']=128;
$ddm4_elements['data']['kunde_homepage']['validation']['filter']=[];
$ddm4_elements['data']['kunde_homepage']['validation']['filter']['url']=[];
$ddm4_elements['data']['kunde_homepage']['_list']=[];
$ddm4_elements['data']['kunde_homepage']['_list']['enabled']=false;

/*
 * Data: Aktiviert
 */
$ddm4_elements['data']['kunde_ispublic']=[];
$ddm4_elements['data']['kunde_ispublic']['module']='yesno';
$ddm4_elements['data']['kunde_ispublic']['title']='Aktiviert';
$ddm4_elements['data']['kunde_ispublic']['name']='kunde_ispublic';
$ddm4_elements['data']['kunde_ispublic']['options']=[];
$ddm4_elements['data']['kunde_ispublic']['options']['order']=true;
$ddm4_elements['data']['kunde_ispublic']['options']['required']=true;
$ddm4_elements['data']['kunde_ispublic']['options']['default_value']=1;

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='kunde_';
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='kunde_';

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