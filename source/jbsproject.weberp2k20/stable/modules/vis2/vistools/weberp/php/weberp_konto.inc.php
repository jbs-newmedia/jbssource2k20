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
$ddm4_object['general']['disable_delete']=true;
$ddm4_object['general']['disable_add']=true;
$ddm4_object['general']['disable_search']=true;
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS2_User->getId();
$ddm4_object['data']['mandant_id']=$VIS2_Mandant->getId();
$ddm4_object['data']['tool']=$VIS2_Main->getTool();
$ddm4_object['data']['page']=$VIS2_Navigation->getPage();
$ddm4_object['messages']=[];
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen';
$ddm4_object['messages']['data_noresults']='Keine Buchungen vorhanden';
$ddm4_object['messages']['search_title']='Buchungen durchsuchen';
$ddm4_object['messages']['add_title']='Neue Buchung anlegen';
$ddm4_object['messages']['add_success_title']='Buchung wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Buchung konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Buchung editieren';
$ddm4_object['messages']['edit_load_error_title']='Buchung wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Buchung wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Buchung konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Buchung löschen';
$ddm4_object['messages']['delete_load_error_title']='Buchung wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Buchung wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Buchung konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='weberp_buchung';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='buchung_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['buchung_buchungtag']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'weberp_buchung', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * Navigationpunkte anlegen
 */
$navigation_links=[];
$navigation_links[1]=['navigation_id'=>1, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Buchungen'];
$navigation_links[2]=['navigation_id'=>2, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Kontodaten importieren'];
$navigation_links[3]=['navigation_id'=>3, 'module'=>$osW_DDM4->getDirectModule(), 'parameter'=>'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getPage(), 'text'=>'Zuordnen'];

$osW_DDM4->readParameters();

$ddm_navigation_id=intval(\osWFrame\Core\Settings::catchIntValue('ddm_navigation_id', intval($osW_DDM4->getParameter('ddm_navigation_id')), 'pg'));
if (!isset($navigation_links[$ddm_navigation_id])) {
	$ddm_navigation_id=1;
}

$osW_DDM4->addParameter('ddm_navigation_id', $ddm_navigation_id);
$osW_DDM4->storeParameters();

if (in_array($ddm_navigation_id, [1])) {
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
	 * Data: Auftragskonto
	 */
	$ddm4_elements['data']['buchung_auftragskonto']=[];
	$ddm4_elements['data']['buchung_auftragskonto']['module']='text';
	$ddm4_elements['data']['buchung_auftragskonto']['title']='Auftragskonto';
	$ddm4_elements['data']['buchung_auftragskonto']['name']='buchung_auftragskonto';
	$ddm4_elements['data']['buchung_auftragskonto']['options']=[];
	$ddm4_elements['data']['buchung_auftragskonto']['options']['order']=true;
	$ddm4_elements['data']['buchung_auftragskonto']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_auftragskonto']['options']['search']=true;
	$ddm4_elements['data']['buchung_auftragskonto']['_list']=[];
	$ddm4_elements['data']['buchung_auftragskonto']['_list']['enabled']=false;

	/*
	 * Data: Buchungstag
	 */
	$ddm4_elements['data']['buchung_buchungtag']=[];
	$ddm4_elements['data']['buchung_buchungtag']['module']='date';
	$ddm4_elements['data']['buchung_buchungtag']['title']='Buchungstag';
	$ddm4_elements['data']['buchung_buchungtag']['name']='buchung_buchungtag';
	$ddm4_elements['data']['buchung_buchungtag']['options']=[];
	$ddm4_elements['data']['buchung_buchungtag']['options']['order']=true;
	$ddm4_elements['data']['buchung_buchungtag']['options']['read_only']=true;

	/*
	 * Data: Valutadatum
	 */
	$ddm4_elements['data']['buchung_valutadatum']=[];
	$ddm4_elements['data']['buchung_valutadatum']['module']='date';
	$ddm4_elements['data']['buchung_valutadatum']['title']='Valutadatum';
	$ddm4_elements['data']['buchung_valutadatum']['name']='buchung_valutadatum';
	$ddm4_elements['data']['buchung_valutadatum']['options']=[];
	$ddm4_elements['data']['buchung_valutadatum']['options']['order']=true;
	$ddm4_elements['data']['buchung_valutadatum']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_valutadatum']['_list']=[];
	$ddm4_elements['data']['buchung_valutadatum']['_list']['enabled']=false;

	/*
	 * Data: Text
	 */
	$ddm4_elements['data']['buchung_text']=[];
	$ddm4_elements['data']['buchung_text']['module']='text';
	$ddm4_elements['data']['buchung_text']['title']='Text';
	$ddm4_elements['data']['buchung_text']['name']='buchung_text';
	$ddm4_elements['data']['buchung_text']['options']=[];
	$ddm4_elements['data']['buchung_text']['options']['order']=true;
	$ddm4_elements['data']['buchung_text']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_text']['options']['search']=true;

	/*
	 * Data: Kontoinhaber
	 */
	$ddm4_elements['data']['buchung_kontoinhaber']=[];
	$ddm4_elements['data']['buchung_kontoinhaber']['module']='text';
	$ddm4_elements['data']['buchung_kontoinhaber']['title']='Kontoinhaber';
	$ddm4_elements['data']['buchung_kontoinhaber']['name']='buchung_kontoinhaber';
	$ddm4_elements['data']['buchung_kontoinhaber']['options']=[];
	$ddm4_elements['data']['buchung_kontoinhaber']['options']['order']=true;
	$ddm4_elements['data']['buchung_kontoinhaber']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_kontoinhaber']['options']['search']=true;

	/*
	 * Data: IBAN
	 */
	$ddm4_elements['data']['buchung_iban']=[];
	$ddm4_elements['data']['buchung_iban']['module']='text';
	$ddm4_elements['data']['buchung_iban']['title']='IBAN';
	$ddm4_elements['data']['buchung_iban']['name']='buchung_iban';
	$ddm4_elements['data']['buchung_iban']['options']=[];
	$ddm4_elements['data']['buchung_iban']['options']['order']=true;
	$ddm4_elements['data']['buchung_iban']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_iban']['options']['search']=true;
	$ddm4_elements['data']['buchung_iban']['_list']=[];
	$ddm4_elements['data']['buchung_iban']['_list']['enabled']=false;

	/*
	 * Data: BIC
	 */
	$ddm4_elements['data']['buchung_bic']=[];
	$ddm4_elements['data']['buchung_bic']['module']='text';
	$ddm4_elements['data']['buchung_bic']['title']='BIC';
	$ddm4_elements['data']['buchung_bic']['name']='buchung_bic';
	$ddm4_elements['data']['buchung_bic']['options']=[];
	$ddm4_elements['data']['buchung_bic']['options']['order']=true;
	$ddm4_elements['data']['buchung_bic']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_bic']['options']['search']=true;
	$ddm4_elements['data']['buchung_bic']['_list']=[];
	$ddm4_elements['data']['buchung_bic']['_list']['enabled']=false;

	/*
	 * Data: Verwendungszweck
	 */
	$ddm4_elements['data']['buchung_verwendungszweck']=[];
	$ddm4_elements['data']['buchung_verwendungszweck']['module']='textarea';
	$ddm4_elements['data']['buchung_verwendungszweck']['title']='Verwendungszweck';
	$ddm4_elements['data']['buchung_verwendungszweck']['name']='buchung_verwendungszweck';
	$ddm4_elements['data']['buchung_verwendungszweck']['options']=[];
	$ddm4_elements['data']['buchung_verwendungszweck']['options']['order']=true;
	$ddm4_elements['data']['buchung_verwendungszweck']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_verwendungszweck']['options']['search']=true;
	$ddm4_elements['data']['buchung_verwendungszweck']['_list']=[];
	$ddm4_elements['data']['buchung_verwendungszweck']['_list']['enabled']=false;

	/*
	 * Data: Betrag
	 */
	$ddm4_elements['data']['buchung_betrag']=[];
	$ddm4_elements['data']['buchung_betrag']['module']='vis2_weberp_text_price';
	$ddm4_elements['data']['buchung_betrag']['title']='Betrag';
	$ddm4_elements['data']['buchung_betrag']['name']='buchung_betrag';
	$ddm4_elements['data']['buchung_betrag']['options']=[];
	$ddm4_elements['data']['buchung_betrag']['options']['order']=true;
	$ddm4_elements['data']['buchung_betrag']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_betrag']['options']['search']=true;

	/*
	 * Data: Währung
	 */
	$ddm4_elements['data']['buchung_waehrung']=[];
	$ddm4_elements['data']['buchung_waehrung']['module']='text';
	$ddm4_elements['data']['buchung_waehrung']['title']='Währung';
	$ddm4_elements['data']['buchung_waehrung']['name']='buchung_waehrung';
	$ddm4_elements['data']['buchung_waehrung']['options']=[];
	$ddm4_elements['data']['buchung_waehrung']['options']['order']=true;
	$ddm4_elements['data']['buchung_waehrung']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_waehrung']['_list']=[];
	$ddm4_elements['data']['buchung_waehrung']['_list']['enabled']=false;

	/*
	 * Data: Info
	 */
	$ddm4_elements['data']['buchung_info']=[];
	$ddm4_elements['data']['buchung_info']['module']='text';
	$ddm4_elements['data']['buchung_info']['title']='Info';
	$ddm4_elements['data']['buchung_info']['name']='buchung_info';
	$ddm4_elements['data']['buchung_info']['options']=[];
	$ddm4_elements['data']['buchung_info']['options']['order']=true;
	$ddm4_elements['data']['buchung_info']['options']['read_only']=true;
	$ddm4_elements['data']['buchung_info']['_list']=[];
	$ddm4_elements['data']['buchung_info']['_list']['enabled']=false;

	/*
	 * Data: VIS2_CreateUpdate
	 */
	$ddm4_elements['data']['vis2_createupdatestatus']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['module']='vis2_createupdatestatus';
	$ddm4_elements['data']['vis2_createupdatestatus']['title']=$osW_DDM4->getGroupOption('createupdate_title', 'messages');
	$ddm4_elements['data']['vis2_createupdatestatus']['options']=[];
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='buchung_';
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['time']=time();
	$ddm4_elements['data']['vis2_createupdatestatus']['options']['user_id']=$VIS2_User->getId();
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
	$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='buchung_';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

if (in_array($ddm_navigation_id, [2])) {
	$dir=\osWFrame\Core\Settings::getStringVar('settings_abspath').'data'.DIRECTORY_SEPARATOR.'weberp'.DIRECTORY_SEPARATOR.'import'.DIRECTORY_SEPARATOR;
	if (\osWFrame\Core\Filesystem::isDir($dir)!==true) {
		\osWFrame\Core\Filesystem::makeDir($dir);
	}

	$osW_DDM4->setGroupOption('engine', 'vis2_formular');

	$messages=[];
	$messages['send_title']='Anrede editieren';
	$messages['send_load_error_title']='Anrede wurde nicht gefunden';
	$messages['send_success_title']='Anrede wurde erfolgreich editiert';
	$messages['send_error_title']='Anrede konnte nicht editiert werden';
	$osW_DDM4->setGroupMessages($osW_DDM4->loadDefaultMessages($messages));

	/*
	 * PreView: VIS2_Navigation
	 */
	$ddm4_elements['send']['vis2_navigation']=[];
	$ddm4_elements['send']['vis2_navigation']['module']='vis2_navigation';
	$ddm4_elements['send']['vis2_navigation']['options']=[];
	$ddm4_elements['send']['vis2_navigation']['options']['data']=$navigation_links;

	/*
	 * Send: Dateiformat
	 */
	$ddm4_elements['send']['konto_format']=[];
	$ddm4_elements['send']['konto_format']['module']='select';
	$ddm4_elements['send']['konto_format']['title']='Dateiformat';
	$ddm4_elements['send']['konto_format']['module']='select';
	$ddm4_elements['send']['konto_format']['options']=[];
	$ddm4_elements['send']['konto_format']['options']['blank_value']=false;
	$ddm4_elements['send']['konto_format']['options']['data']=[];
	$ddm4_elements['send']['konto_format']['options']['data']['csv-mt940']='CSV-MT940';

	/*
	 * Send: Datei
	 */
	$ddm4_elements['send']['konto_import']=[];
	$ddm4_elements['send']['konto_import']['module']='file';
	$ddm4_elements['send']['konto_import']['title']='Datei';
	$ddm4_elements['send']['konto_import']['options']=[];
	$ddm4_elements['send']['konto_import']['options']['required']=true;
	$ddm4_elements['send']['konto_import']['options']['file_dir']='data'.DIRECTORY_SEPARATOR.'weberp'.DIRECTORY_SEPARATOR.'import'.DIRECTORY_SEPARATOR;
	$ddm4_elements['send']['konto_import']['options']['file_name']='time+rand';
	$ddm4_elements['send']['konto_import']['validation']['module']='file';
	$ddm4_elements['send']['konto_import']['validation']['types']=['text/csv', 'text/plain'];
	$ddm4_elements['send']['konto_import']['validation']['extensions']=['csv'];
	$ddm4_elements['send']['konto_import']['validation']['size_min']=1024;
	$ddm4_elements['send']['konto_import']['validation']['size_max']=1024*1024*5;

	/*
	 * Send: Submit
	 */
	$ddm4_elements['send']['submit']=[];
	$ddm4_elements['send']['submit']['module']='submit';

	/*
	 * Finish: VIS2_WebERP_KontoImport
	 */
	$ddm4_elements['finish']['vis2_weberp_kontoimport']=[];
	$ddm4_elements['finish']['vis2_weberp_kontoimport']['module']='vis2_weberp_kontoimport';
	$ddm4_elements['finish']['vis2_weberp_kontoimport']['options']=[];
	$ddm4_elements['finish']['vis2_weberp_kontoimport']['options']['mandant_id']=$VIS2_Mandant->getId();
	$ddm4_elements['finish']['vis2_weberp_kontoimport']['options']['var_file']='konto_import';
	$ddm4_elements['finish']['vis2_weberp_kontoimport']['options']['var_format']='konto_format';

	/*
	 * AfterFinish: VIS2_Direct
	 */
	$ddm4_elements['afterfinish']['vis2_direct']=[];
	$ddm4_elements['afterfinish']['vis2_direct']['module']='vis2_direct';
}

if (in_array($ddm_navigation_id, [3])) {
	$osW_DDM4->setGroupOption('engine', 'vis2_formular');

	/*
	 * PreView: VIS2_Navigation
	 */
	$ddm4_elements['send']['vis2_navigation']=[];
	$ddm4_elements['send']['vis2_navigation']['module']='vis2_navigation';
	$ddm4_elements['send']['vis2_navigation']['options']=[];
	$ddm4_elements['send']['vis2_navigation']['options']['data']=$navigation_links;

	$Konto=new \JBSNewMedia\WebERP\Konto($VIS2_Mandant->getId());
	$ar_todo=$Konto->getBuchungsAusgleichAsList();

	if ($ar_todo['ok']!==[]) {
		/*
		 * Send: Passend
		 */
		$ddm4_elements['send']['buchung_passend']=[];
		$ddm4_elements['send']['buchung_passend']['module']='bitmask';
		$ddm4_elements['send']['buchung_passend']['title']='Passend';
		$ddm4_elements['send']['buchung_passend']['options']=[];
		$ddm4_elements['send']['buchung_passend']['options']['data']=$ar_todo['ok'];
	} else {
		/*
		 * Send: Passend
		 */
		$ddm4_elements['send']['buchung_passend']=[];
		$ddm4_elements['send']['buchung_passend']['module']='text';
		$ddm4_elements['send']['buchung_passend']['title']='Passend';
		$ddm4_elements['send']['buchung_passend']['options']=[];
		$ddm4_elements['send']['buchung_passend']['options']['read_only']=true;
		$ddm4_elements['send']['buchung_passend']['options']['default_value']='---';
	}

	if ($ar_todo['evtl']!==[]) {
		/*
		 * Send: Überzahlt
		 */
		$ddm4_elements['send']['buchung_ueberzahlt']=[];
		$ddm4_elements['send']['buchung_ueberzahlt']['module']='bitmask';
		$ddm4_elements['send']['buchung_ueberzahlt']['title']='Überzahlt';
		$ddm4_elements['send']['buchung_ueberzahlt']['options']=[];
		$ddm4_elements['send']['buchung_ueberzahlt']['options']['data']=$ar_todo['evtl'];
	} else {
		/*
		 * Send: Passend
		 */
		$ddm4_elements['send']['buchung_ueberzahlt']=[];
		$ddm4_elements['send']['buchung_ueberzahlt']['module']='text';
		$ddm4_elements['send']['buchung_ueberzahlt']['title']='Überzahlt';
		$ddm4_elements['send']['buchung_ueberzahlt']['options']=[];
		$ddm4_elements['send']['buchung_ueberzahlt']['options']['read_only']=true;
		$ddm4_elements['send']['buchung_ueberzahlt']['options']['default_value']='---';
	}

	/*
	 * Send: Submit
	 */
	$ddm4_elements['send']['submit']=[];
	$ddm4_elements['send']['submit']['module']='submit';

	/*
	 * Finish: VIS2_WebERP_KontoImport
	 */
	$ddm4_elements['finish']['vis2_weberp_kontobuchung']=[];
	$ddm4_elements['finish']['vis2_weberp_kontobuchung']['module']='vis2_weberp_kontobuchung';
	$ddm4_elements['finish']['vis2_weberp_kontobuchung']['options']=[];
	$ddm4_elements['finish']['vis2_weberp_kontobuchung']['options']['konto']=$Konto;

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