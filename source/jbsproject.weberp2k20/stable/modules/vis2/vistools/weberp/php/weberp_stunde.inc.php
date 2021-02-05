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
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen';
$ddm4_object['messages']['data_noresults']='Keine Stunden vorhanden';
$ddm4_object['messages']['search_title']='Stunden durchsuchen';
$ddm4_object['messages']['add_title']='Neue Stunde anlegen';
$ddm4_object['messages']['add_success_title']='Stunde wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Stunde konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Stunde editieren';
$ddm4_object['messages']['edit_load_error_title']='Stunde wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Stunde wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Stunde konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Stunde löschen';
$ddm4_object['messages']['delete_load_error_title']='Stunde wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Stunde wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Stunde konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_stunde';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='stunde_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['stunde_datum']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_stunde', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(stunde_id) AS counter FROM :table_weberp_stunde: WHERE mandant_id=:mandant_id: AND stunde_abrechnen=:stunde_abrechnen: AND stunde_abgerechnet=:stunde_abgerechnet:');
$QselectCount->bindTable(':table_weberp_stunde:', 'weberp_stunde');
$QselectCount->bindInt(':stunde_abrechnen:', 0);
$QselectCount->bindInt(':stunde_abgerechnet:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Offen', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(stunde_id) AS counter FROM :table_weberp_stunde: WHERE mandant_id=:mandant_id: AND stunde_abrechnen=:stunde_abrechnen: AND stunde_abgerechnet=:stunde_abgerechnet:');
$QselectCount->bindTable(':table_weberp_stunde:', 'weberp_stunde');
$QselectCount->bindInt(':stunde_abrechnen:', 1);
$QselectCount->bindInt(':stunde_abgerechnet:', 0);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Abrechnen', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(stunde_id) AS counter FROM :table_weberp_stunde: WHERE mandant_id=:mandant_id: AND stunde_abrechnen=:stunde_abrechnen: AND stunde_abgerechnet=:stunde_abgerechnet:');
$QselectCount->bindTable(':table_weberp_stunde:', 'weberp_stunde');
$QselectCount->bindInt(':stunde_abrechnen:', 1);
$QselectCount->bindInt(':stunde_abgerechnet:', 1);
$QselectCount->bindInt(':mandant_id:', $VIS2_Mandant->getId());
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Abgerechnet', 'counter'=>clone $QselectCount];

$QselectCount=$osW_DDM4->getConnection();
$QselectCount->prepare('SELECT count(stunde_id) AS counter FROM :table_weberp_stunde: WHERE mandant_id=:mandant_id:');
$QselectCount->bindTable(':table_weberp_stunde:', 'weberp_stunde');
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
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'stunde_abrechnen', 'operator'=>'=', 'value'=>0], ['key'=>'stunde_abgerechnet', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [2])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'stunde_abrechnen', 'operator'=>'=', 'value'=>1], ['key'=>'stunde_abgerechnet', 'operator'=>'=', 'value'=>0], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('filter', [['and'=>[['key'=>'stunde_abrechnen', 'operator'=>'=', 'value'=>1], ['key'=>'stunde_abgerechnet', 'operator'=>'=', 'value'=>1], ['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]], 'database');
}

if (in_array($ddm_navigation_id, [4])) {
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
$ddm4_elements['data']['stunde_datum']=[];
$ddm4_elements['data']['stunde_datum']['module']='datepicker';
$ddm4_elements['data']['stunde_datum']['title']='Datum';
$ddm4_elements['data']['stunde_datum']['name']='stunde_datum';
$ddm4_elements['data']['stunde_datum']['options']=[];
$ddm4_elements['data']['stunde_datum']['options']['order']=true;
$ddm4_elements['data']['stunde_datum']['options']['required']=true;
$ddm4_elements['data']['stunde_datum']['options']['year_min']=\JBSNewMedia\WebERP\Verwaltung::getBeginningYear();
$ddm4_elements['data']['stunde_datum']['options']['default_value']=date('Ymd');

/*
 * Interne Beschreibung
 */
$ddm4_elements['data']['stunde_beschreibung']=[];
$ddm4_elements['data']['stunde_beschreibung']['module']='textarea';
$ddm4_elements['data']['stunde_beschreibung']['title']='Interne Beschreibung';
$ddm4_elements['data']['stunde_beschreibung']['name']='stunde_beschreibung';
$ddm4_elements['data']['stunde_beschreibung']['options']=[];
$ddm4_elements['data']['stunde_beschreibung']['options']['order']=true;
$ddm4_elements['data']['stunde_beschreibung']['options']['search']=true;
$ddm4_elements['data']['stunde_beschreibung']['options']['required']=true;
$ddm4_elements['data']['stunde_beschreibung']['validation']=[];
$ddm4_elements['data']['stunde_beschreibung']['validation']['length_min']=6;
$ddm4_elements['data']['stunde_beschreibung']['validation']['length_max']=255;
$ddm4_elements['data']['stunde_beschreibung']['_list']=[];
$ddm4_elements['data']['stunde_beschreibung']['_list']['enabled']=false;
$ddm4_elements['data']['stunde_beschreibung']['_search']=[];
$ddm4_elements['data']['stunde_beschreibung']['_search']['enabled']=false;

/*
 * Artikel
 */
$ddm4_elements['data']['artikel_id']=[];
$ddm4_elements['data']['artikel_id']['module']='select';
$ddm4_elements['data']['artikel_id']['title']='Artikel';
$ddm4_elements['data']['artikel_id']['name']='artikel_id';
$ddm4_elements['data']['artikel_id']['options']=[];
$ddm4_elements['data']['artikel_id']['options']['search']=true;
$ddm4_elements['data']['artikel_id']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikel(false);
$ddm4_elements['data']['artikel_id']['validation']=[];
$ddm4_elements['data']['artikel_id']['validation']['module']='integer';
$ddm4_elements['data']['artikel_id']['validation']['length_min']=0;
$ddm4_elements['data']['artikel_id']['validation']['length_max']=11;
$ddm4_elements['data']['artikel_id']['_list']=[];
$ddm4_elements['data']['artikel_id']['_list']['enabled']=false;
$ddm4_elements['data']['artikel_id']['_search']=[];
$ddm4_elements['data']['artikel_id']['_search']['enabled']=false;
$ddm4_elements['data']['artikel_id']['_add']=[];
$ddm4_elements['data']['artikel_id']['_add']['options']=[];
$ddm4_elements['data']['artikel_id']['_add']['options']['data']=$VIS2_WebERP_Verwaltung->getArtikel();

/*
 * Anzahl
 */
$ddm4_elements['data']['artikel_anzahl']=[];
$ddm4_elements['data']['artikel_anzahl']['module']='text';
$ddm4_elements['data']['artikel_anzahl']['title']='Anzahl';
$ddm4_elements['data']['artikel_anzahl']['validation']=[];
$ddm4_elements['data']['artikel_anzahl']['validation']['module']='float';
$ddm4_elements['data']['artikel_anzahl']['_list']=[];
$ddm4_elements['data']['artikel_anzahl']['_list']['enabled']=false;
$ddm4_elements['data']['artikel_anzahl']['_search']=[];
$ddm4_elements['data']['artikel_anzahl']['_search']['enabled']=false;

/*
 * Zusatzbeschreibung
 */
$ddm4_elements['data']['artikel_zusatz']=[];
$ddm4_elements['data']['artikel_zusatz']['module']='textarea';
$ddm4_elements['data']['artikel_zusatz']['title']='Zusatzbeschreibung';
$ddm4_elements['data']['artikel_zusatz']['name']='artikel_zusatz';
$ddm4_elements['data']['artikel_zusatz']['options']=[];
$ddm4_elements['data']['artikel_zusatz']['options']['order']=true;
$ddm4_elements['data']['artikel_zusatz']['validation']=[];
$ddm4_elements['data']['artikel_zusatz']['validation']['length_min']=0;
$ddm4_elements['data']['artikel_zusatz']['validation']['length_max']=10000;
$ddm4_elements['data']['artikel_zusatz']['_list']=[];
$ddm4_elements['data']['artikel_zusatz']['_list']['enabled']=false;
$ddm4_elements['data']['artikel_zusatz']['_search']=[];
$ddm4_elements['data']['artikel_zusatz']['_search']['enabled']=false;

/*
 * Data: ReNr
 */
$ddm4_elements['data']['rechnung_nr']=[];
$ddm4_elements['data']['rechnung_nr']['module']='text';
$ddm4_elements['data']['rechnung_nr']['title']='ReNr';
$ddm4_elements['data']['rechnung_nr']['name']='rechnung_nr';
$ddm4_elements['data']['rechnung_nr']['options']=[];
$ddm4_elements['data']['rechnung_nr']['options']['order']=true;
$ddm4_elements['data']['rechnung_nr']['options']['search']=true;
$ddm4_elements['data']['rechnung_nr']['options']['read_only']=true;

/*
 * Data: Abrechnen
 */
$ddm4_elements['data']['stunde_abrechnen']=[];
$ddm4_elements['data']['stunde_abrechnen']['module']='yesno';
$ddm4_elements['data']['stunde_abrechnen']['title']='Abrechnen';
$ddm4_elements['data']['stunde_abrechnen']['name']='stunde_abrechnen';
$ddm4_elements['data']['stunde_abrechnen']['options']=[];
$ddm4_elements['data']['stunde_abrechnen']['options']['order']=true;
$ddm4_elements['data']['stunde_abrechnen']['options']['required']=true;
$ddm4_elements['data']['stunde_abrechnen']['options']['default_value']=0;

/*
 * Data: Abgerechnet
 */
$ddm4_elements['data']['stunde_abgerechnet']=[];
$ddm4_elements['data']['stunde_abgerechnet']['module']='yesno';
$ddm4_elements['data']['stunde_abgerechnet']['title']='Abgerechnet';
$ddm4_elements['data']['stunde_abgerechnet']['name']='stunde_abgerechnet';
$ddm4_elements['data']['stunde_abgerechnet']['options']=[];
$ddm4_elements['data']['stunde_abgerechnet']['options']['order']=true;
$ddm4_elements['data']['stunde_abgerechnet']['options']['required']=true;
$ddm4_elements['data']['stunde_abgerechnet']['options']['default_value']=0;

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='stunde_';
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='stunde_';

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