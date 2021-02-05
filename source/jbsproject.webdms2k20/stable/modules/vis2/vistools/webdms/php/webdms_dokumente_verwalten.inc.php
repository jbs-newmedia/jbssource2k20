<?php

/**
 * This file is part of the VIS2:WebDMS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebDMS
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$VIS2_Mandant->directEmptyMandant($osW_Template->buildhrefLink(\osWFrame\Core\Settings::getStringVar('frame_current_module'), 'vistool='.$VIS2_Main->getTool().'&vispage='.$VIS2_Navigation->getDefaultPage()));

/*
 * DDM4 initialisieren
 */
$ddm4_object=[];
$ddm4_object['general']=[];
$ddm4_object['general']['engine']='vis2_datatables';
$ddm4_object['general']['index_parent']='ordner_parent_id';
if ($VIS2_WebDMS_Verwaltung->getIntVar('dirlevel')!==null) {
	$ddm4_object['general']['navigation_level']=$VIS2_WebDMS_Verwaltung->getIntVar('dirlevel')+1;
} else {
	$ddm4_object['general']['navigation_level']=3;
}
$ddm4_object['general']['ordner_max']=10;
$ddm4_object['general']['cache']=\osWFrame\Core\Settings::catchValue('ddm_cache', '', 'pg');
$ddm4_object['general']['elements_per_page']=50;
$ddm4_object['general']['enable_log']=true;
$ddm4_object['general']['status_keys']=[];
$ddm4_object['general']['status_keys']['dokument_ispublic']=[];
$ddm4_object['general']['status_keys']['dokument_ispublic'][0]=['value'=>'Nein', 'class'=>'danger'];
$ddm4_object['data']=[];
$ddm4_object['data']['user_id']=$VIS2_User->getId();
$ddm4_object['data']['mandant_id']=$VIS2_Mandant->getId();
$ddm4_object['data']['tool']=$VIS2_Main->getTool();
$ddm4_object['data']['page']=$VIS2_Navigation->getPage();
$ddm4_object['messages']=[];
$ddm4_object['messages']['createupdate_title']='Datensatzinformationen';
$ddm4_object['messages']['data_noresults']='Keine Dokumente vorhanden';
$ddm4_object['messages']['search_title']='Dokumente durchsuchen';
$ddm4_object['messages']['add_title']='Neues Dokument anlegen';
$ddm4_object['messages']['add_success_title']='Dokument wurde erfolgreich angelegt';
$ddm4_object['messages']['add_error_title']='Dokument konnte nicht angelegt werden';
$ddm4_object['messages']['edit_title']='Dokument editieren';
$ddm4_object['messages']['edit_load_error_title']='Dokument wurde nicht gefunden';
$ddm4_object['messages']['edit_success_title']='Dokument wurde erfolgreich editiert';
$ddm4_object['messages']['edit_error_title']='Dokument konnte nicht editiert werden';
$ddm4_object['messages']['delete_title']='Dokument löschen';
$ddm4_object['messages']['delete_load_error_title']='Dokument wurde nicht gefunden';
$ddm4_object['messages']['delete_success_title']='Dokument wurde erfolgreich gelöscht';
$ddm4_object['messages']['delete_error_title']='Dokument konnte nicht gelöscht werden';
$ddm4_object['direct']=[];
$ddm4_object['direct']['module']=\osWFrame\Core\Settings::getStringVar('frame_current_module');
$ddm4_object['direct']['parameters']=[];
$ddm4_object['direct']['parameters']['vistool']=$VIS2_Main->getTool();
$ddm4_object['direct']['parameters']['vispage']=$VIS2_Navigation->getPage();
$ddm4_object['database']=[];
$ddm4_object['database']['table']='webdms_dokument';
$ddm4_object['database']['alias']='tbl1';
$ddm4_object['database']['index']='dokument_id';
$ddm4_object['database']['index_type']='integer';
$ddm4_object['database']['filter']=[['and'=>[['key'=>'mandant_id', 'operator'=>'=', 'value'=>$VIS2_Mandant->getId()]]]];
$ddm4_object['database']['order']=[];
$ddm4_object['database']['order']['dokument_id']='desc';
$ddm4_object['database']['order_case']=[];
$ddm4_object['database']['order_case']['user_update_user_id']=\VIS2\Core\Manager::getUsers();

/*
 * DDM4-Objekt erstellen
 */
$osW_DDM4=new osWFrame\Core\DDM4($osW_Template, 'webdms_dokument', $ddm4_object);

/*
 * Datenelemente anlegen
 */
$ddm4_elements=$osW_DDM4->getElementsArrayInit();

/*
 * View: VIS2_Datatables
 */
$ddm4_elements['view']['vis2_datatables']=[];
$ddm4_elements['view']['vis2_datatables']['module']='vis2_datatables';

/*
 * Ordnerliste laden
 */
$data=$VIS2_WebDMS_Verwaltung->createOrdnerRecursive(0, 0, $osW_DDM4->getGroupOption('navigation_level'));

/*
 * Data: Ordner
 */
$ddm4_elements['data']['ordner_id_1']=[];
$ddm4_elements['data']['ordner_id_1']['module']='select';
$ddm4_elements['data']['ordner_id_1']['title']='Ordner';
$ddm4_elements['data']['ordner_id_1']['name']='ordner_id_1';
$ddm4_elements['data']['ordner_id_1']['options']=[];
$ddm4_elements['data']['ordner_id_1']['options']['required']=true;
$ddm4_elements['data']['ordner_id_1']['options']['data']=$data['title'];
$ddm4_elements['data']['ordner_id_1']['options']['blank_value']=false;
$ddm4_elements['data']['ordner_id_1']['options']['search']=true;
$ddm4_elements['data']['ordner_id_1']['validation']=[];
$ddm4_elements['data']['ordner_id_1']['validation']['module']='integer';
$ddm4_elements['data']['ordner_id_1']['validation']['length_min']=1;
$ddm4_elements['data']['ordner_id_1']['validation']['length_max']=11;
$ddm4_elements['data']['ordner_id_1']['validation']['value_min']=1;
$ddm4_elements['data']['ordner_id_1']['validation']['value_max']=999999;

/*
 * Data: Titel
 */
$ddm4_elements['data']['dokument_titel']=[];
$ddm4_elements['data']['dokument_titel']['module']='text';
$ddm4_elements['data']['dokument_titel']['title']='Titel';
$ddm4_elements['data']['dokument_titel']['name']='dokument_titel';
$ddm4_elements['data']['dokument_titel']['options']=[];
$ddm4_elements['data']['dokument_titel']['options']['order']=true;
$ddm4_elements['data']['dokument_titel']['options']['required']=true;
$ddm4_elements['data']['dokument_titel']['options']['search']=true;
$ddm4_elements['data']['dokument_titel']['validation']=[];
$ddm4_elements['data']['dokument_titel']['validation']['module']='string';
$ddm4_elements['data']['dokument_titel']['validation']['length_min']=2;
$ddm4_elements['data']['dokument_titel']['validation']['length_max']=128;

/*
 * Data: Beschreibung
 */
$ddm4_elements['data']['dokument_beschreibung']=[];
$ddm4_elements['data']['dokument_beschreibung']['module']='textarea';
$ddm4_elements['data']['dokument_beschreibung']['title']='Beschreibung';
$ddm4_elements['data']['dokument_beschreibung']['name']='dokument_beschreibung';
$ddm4_elements['data']['dokument_beschreibung']['options']=[];
$ddm4_elements['data']['dokument_beschreibung']['options']['order']=true;
$ddm4_elements['data']['dokument_beschreibung']['options']['search']=true;
$ddm4_elements['data']['dokument_beschreibung']['validation']=[];
$ddm4_elements['data']['dokument_beschreibung']['validation']['module']='string';
$ddm4_elements['data']['dokument_beschreibung']['validation']['length_min']=0;
$ddm4_elements['data']['dokument_beschreibung']['validation']['length_max']=10000;
$ddm4_elements['data']['dokument_beschreibung']['_list']=[];
$ddm4_elements['data']['dokument_beschreibung']['_list']['enabled']=false;

/*
 * Data: Dokument
 */
$ddm4_elements['data']['dokument_file']=[];
$ddm4_elements['data']['dokument_file']['module']='file';
$ddm4_elements['data']['dokument_file']['title']='Dokument';
$ddm4_elements['data']['dokument_file']['name']='dokument_file';
$ddm4_elements['data']['dokument_file']['options']=[];
$ddm4_elements['data']['dokument_file']['options']['file_dir']='data/dms/';
$ddm4_elements['data']['dokument_file']['options']['file_name']='shared_md5';
$ddm4_elements['data']['dokument_file']['options']['store_name']=true;
$ddm4_elements['data']['dokument_file']['options']['store_type']=true;
$ddm4_elements['data']['dokument_file']['options']['store_size']=true;
$ddm4_elements['data']['dokument_file']['options']['store_md5']=true;
$ddm4_elements['data']['dokument_file']['options']['required']=true;
$ddm4_elements['data']['dokument_file']['validation']=[];
$ddm4_elements['data']['dokument_file']['validation']['extension']=[];
$ddm4_elements['data']['dokument_file']['validation']['extension']['0']='pdf';
$ddm4_elements['data']['dokument_file']['validation']['size_min']=2048;
$ddm4_elements['data']['dokument_file']['validation']['size_max']=8388608;
$ddm4_elements['data']['dokument_file']['_list']=[];
$ddm4_elements['data']['dokument_file']['_list']['enabled']=false;

/*
 * Data: Datum
 */
$ddm4_elements['data']['dokument_datum']=[];
$ddm4_elements['data']['dokument_datum']['module']='datepicker';
$ddm4_elements['data']['dokument_datum']['title']='Datum';
$ddm4_elements['data']['dokument_datum']['name']='dokument_datum';
$ddm4_elements['data']['dokument_datum']['options']=[];
$ddm4_elements['data']['dokument_datum']['options']['required']=true;
$ddm4_elements['data']['dokument_datum']['options']['default_value']=date('Ymd');

/*
 * Data: Status
 */
$ddm4_elements['data']['status_id']=[];
$ddm4_elements['data']['status_id']['module']='select';
$ddm4_elements['data']['status_id']['title']='Status';
$ddm4_elements['data']['status_id']['name']='status_id';
$ddm4_elements['data']['status_id']['options']=[];
$ddm4_elements['data']['status_id']['options']['order']=true;
$ddm4_elements['data']['status_id']['options']['search']=true;
$ddm4_elements['data']['status_id']['options']['required']=true;
$ddm4_elements['data']['status_id']['options']['data']=$VIS2_WebDMS_Verwaltung->getStatus();
$ddm4_elements['data']['status_id']['validation']=[];
$ddm4_elements['data']['status_id']['validation']['module']='integer';
$ddm4_elements['data']['status_id']['validation']['length_min']=1;
$ddm4_elements['data']['status_id']['validation']['length_max']=11;
$ddm4_elements['data']['status_id']['validation']['value_min']=1;

/*
 * Data: Typ
 */
$ddm4_elements['data']['typ_id']=[];
$ddm4_elements['data']['typ_id']['module']='select';
$ddm4_elements['data']['typ_id']['title']='Typ';
$ddm4_elements['data']['typ_id']['name']='typ_id';
$ddm4_elements['data']['typ_id']['options']=[];
$ddm4_elements['data']['typ_id']['options']['order']=true;
$ddm4_elements['data']['typ_id']['options']['search']=true;
$ddm4_elements['data']['typ_id']['options']['required']=true;
$ddm4_elements['data']['typ_id']['options']['data']=$VIS2_WebDMS_Verwaltung->getTyp();
$ddm4_elements['data']['typ_id']['validation']=[];
$ddm4_elements['data']['typ_id']['validation']['module']='integer';
$ddm4_elements['data']['typ_id']['validation']['length_min']=1;
$ddm4_elements['data']['typ_id']['validation']['length_max']=11;
$ddm4_elements['data']['typ_id']['validation']['value_min']=1;

for ($i=2;$i<=$osW_DDM4->getGroupOption('ordner_max');$i++) {
	/*
	 * Data: Weiterer Ordner
	 */
	$ddm4_elements['data']['ordner_id_'.$i]=[];
	$ddm4_elements['data']['ordner_id_'.$i]['module']='select';
	$ddm4_elements['data']['ordner_id_'.$i]['title']='Weiterer Ordner';
	$ddm4_elements['data']['ordner_id_'.$i]['name']='ordner_id_'.$i;
	$ddm4_elements['data']['ordner_id_'.$i]['options']=[];
	$ddm4_elements['data']['ordner_id_'.$i]['options']['required']=false;
	$ddm4_elements['data']['ordner_id_'.$i]['options']['data']=$data['title'];
	$ddm4_elements['data']['ordner_id_'.$i]['options']['blank_value']=false;
	$ddm4_elements['data']['ordner_id_'.$i]['options']['search']=true;
	$ddm4_elements['data']['ordner_id_'.$i]['validation']=[];
	$ddm4_elements['data']['ordner_id_'.$i]['validation']['module']='integer';
	$ddm4_elements['data']['ordner_id_'.$i]['validation']['length_min']=0;
	$ddm4_elements['data']['ordner_id_'.$i]['validation']['length_max']=11;
	$ddm4_elements['data']['ordner_id_'.$i]['validation']['value_min']=0;
	$ddm4_elements['data']['ordner_id_'.$i]['validation']['value_max']=999999;
	$ddm4_elements['data']['ordner_id_'.$i]['_list']=[];
	$ddm4_elements['data']['ordner_id_'.$i]['_list']['enabled']=false;
}

/*
 * Data: Aktiviert
 */
$ddm4_elements['data']['dokument_ispublic']=[];
$ddm4_elements['data']['dokument_ispublic']['module']='yesno';
$ddm4_elements['data']['dokument_ispublic']['title']='Aktiviert';
$ddm4_elements['data']['dokument_ispublic']['name']='dokument_ispublic';
$ddm4_elements['data']['dokument_ispublic']['options']=[];
$ddm4_elements['data']['dokument_ispublic']['options']['required']=true;
$ddm4_elements['data']['dokument_ispublic']['options']['default_value']=1;

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
$ddm4_elements['data']['vis2_createupdatestatus']['options']['prefix']='dokument_';
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
$ddm4_elements['finish']['vis2_store_form_data']['options']['createupdatestatus_prefix']='dokument_';

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
 * Ajax für "Weitere Ordner"
 */
$ajax=[];
$css=[];
$_ajax='
$(window).on("load", function (e) { 
	ddm4_formular_'.$osW_DDM4->getName().'_ordner("");
';
for ($i=1;$i<=$osW_DDM4->getGroupOption('ordner_max');$i++) {
	$_ajax.='	$("select[name=\'ordner_id_'.$i.'\']").change(function(){ddm4_formular_'.$osW_DDM4->getName().'_ordner('.$i.', true);});
';
}
$_ajax.='});
';
$ajax[]=$_ajax;
$_ajax='
function ddm4_formular_'.$osW_DDM4->getName().'_ordner(position, load) {
	last_ordner=0;
	for (i=1;i<='.$osW_DDM4->getGroupOption('ordner_max').';i++) {
		if ($("select[name=\'ordner_id_"+i+"\']").val()>0) {
			last_ordner=i;
		}
	}

	last_ordner=last_ordner+1;
	for (i=1;i<='.$osW_DDM4->getGroupOption('ordner_max').';i++) {
		if (i<=last_ordner) {
			$(".ddm_element_ordner_id_"+i).fadeIn(0);
		} else {
			$(".ddm_element_ordner_id_"+i).fadeOut(0);
		}
	}
}
';
$ajax[]=$_ajax;
$osW_DDM4->getTemplate()->addJSCodeHead(implode("\n", $ajax));
$osW_DDM4->getTemplate()->addCSSCodeHead(implode("\n", $css));

/*
 * DDM4-Objekt Runtime
 */
$osW_DDM4->runDDMPHP();

/*
 * DDM4-Objekt an Template übergeben
 */
$osW_Template->setVar('osW_DDM4', $osW_DDM4);

?>