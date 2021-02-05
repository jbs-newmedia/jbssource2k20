<?php

$this->data['settings']=array();

$this->data['settings']['data']=array(
	'page_title'=>'VIS2:JBSNewMedia:WebERP Settings',
);

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	foreach ($this->data['values_post'] as $key => $values) {
		$this->data['values_json'][$key]=$values['value'];
	}

	if ((isset($this->data['values_json']['vis2_tool_jbsnm_weberp']))&&($this->data['values_json']['vis2_tool_jbsnm_weberp']==1)) {
		osW_Tool_Database::addDatabase('default', array('type'=>'mysql', 'database'=>$this->data['values_json']['database_db'], 'server'=>$this->data['values_json']['database_server'], 'username'=>$this->data['values_json']['database_username'], 'password'=>$this->data['values_json']['database_password'], 'pconnect'=>false, 'prefix'=>$this->data['values_json']['database_prefix']));
	}

	$_vis2_script=array();
	$_vis2_script['tool']=array(
		'tool_name'=>'JBSNM:WebERP',
		'tool_name_intern'=>'weberp',
		'tool_description'=>'JBSNM:WebERP',
		'tool_ispublic'=>1,
		'tool_hide_logon'=>0,
		'tool_hide_navigation'=>0,
		'tool_use_mandant'=>1,
		'tool_use_mandantswitch'=>1
	);
	$_vis2_script['group']=array();
	$_vis2_script['group'][1]=array(
		'group_name'=>'JBSNM:WebERP-Admin',
		'group_name_intern'=>'jbsnmweberp_admin',
		'group_description'=>'JBSNM:WebERP-Admin',
		'group_ispublic'=>1,
	);
	$_vis2_script['group'][2]=array(
		'group_name'=>'JBSNM:WebERP-User',
		'group_name_intern'=>'jbsnmweberp_user',
		'group_description'=>'JBSNM:WebERP-User',
		'group_ispublic'=>1,
	);
	$_vis2_script['permission']=array();
	$_vis2_script['permission'][]=array(
		'permission_flag'=>'link',
		'permission_title'=>'Link anzeigen',
		'permission_ispublic'=>1,
	);
	$_vis2_script['permission'][]=array(
		'permission_flag'=>'view',
		'permission_title'=>'Seite anzeigen',
		'permission_ispublic'=>1,
	);
	$_vis2_script['navigation']=array(
		array(
			'navigation_parent_id'=>'',
			'navigation_title'=>'Stammdaten',
			'navigation_sortorder'=>50,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header Stammdaten',
				'page_name_intern'=>'header_stammdaten',
				'page_description'=>'Header Stammdaten',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_stammdaten',
			'navigation_title'=>'Kunden',
			'navigation_sortorder'=>10,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Kunden',
				'page_name_intern'=>'weberp_kunde',
				'page_description'=>'WebERP Kunden',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_stammdaten',
			'navigation_title'=>'Kunden-Konto',
			'navigation_sortorder'=>20,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Kunden-Konto',
				'page_name_intern'=>'weberp_kunde_konto',
				'page_description'=>'WebERP Kunden_konto',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_stammdaten',
			'navigation_title'=>'Kunden-Sepa',
			'navigation_sortorder'=>30,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Kunden-Sepa',
				'page_name_intern'=>'weberp_kunde_sepa',
				'page_description'=>'WebERP Sepa',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_stammdaten',
			'navigation_title'=>'Artikel',
			'navigation_sortorder'=>40,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Artikel',
				'page_name_intern'=>'weberp_artikel',
				'page_description'=>'WebERP Artikel',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'',
			'navigation_title'=>'Vorgänge',
			'navigation_sortorder'=>30,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header Vorgänge',
				'page_name_intern'=>'header_vorgaenge',
				'page_description'=>'Header Vorgänge',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_vorgaenge',
			'navigation_title'=>'Angebote',
			'navigation_sortorder'=>10,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Angebote',
				'page_name_intern'=>'weberp_angebot',
				'page_description'=>'WebERP Angebote',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_vorgaenge',
			'navigation_title'=>'Stunden',
			'navigation_sortorder'=>20,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Stunden',
				'page_name_intern'=>'weberp_stunde',
				'page_description'=>'WebERP Stunden',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_vorgaenge',
			'navigation_title'=>'Rechnungen (Cron)',
			'navigation_sortorder'=>30,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Rechnungen',
				'page_name_intern'=>'weberp_rechnung_cron',
				'page_description'=>'WebERP Rechnungen (Cron)',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_vorgaenge',
			'navigation_title'=>'Rechnungen',
			'navigation_sortorder'=>40,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Rechnungen',
				'page_name_intern'=>'weberp_rechnung',
				'page_description'=>'WebERP Rechnungen',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_vorgaenge',
			'navigation_title'=>'Statistik',
			'navigation_sortorder'=>60,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Statistik',
				'page_name_intern'=>'weberp_statistik',
				'page_description'=>'WebERP Statistik',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'',
			'navigation_title'=>'Buchhaltung',
			'navigation_sortorder'=>70,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header Buchhaltung',
				'page_name_intern'=>'header_buchhaltung',
				'page_description'=>'Header Buchhaltung',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_buchhaltung',
			'navigation_title'=>'Konto',
			'navigation_sortorder'=>10,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Konto',
				'page_name_intern'=>'weberp_konto',
				'page_description'=>'WebERP Konto',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'',
			'navigation_title'=>'Personal',
			'navigation_sortorder'=>90,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header Personal',
				'page_name_intern'=>'header_personal',
				'page_description'=>'Header Personal',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_personal',
			'navigation_title'=>'Mitarbeiter',
			'navigation_sortorder'=>10,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Mitarbeiter',
				'page_name_intern'=>'weberp_mitarbeiter',
				'page_description'=>'WebERP Mitarbeiter',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_personal',
			'navigation_title'=>'Lohn',
			'navigation_sortorder'=>20,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Lohn',
				'page_name_intern'=>'weberp_lohn',
				'page_description'=>'WebERP Lohn',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'',
			'navigation_title'=>'Meine Firma',
			'navigation_sortorder'=>110,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header Meine Firma',
				'page_name_intern'=>'header_meine_firma',
				'page_description'=>'Header Meine Firma',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_meine_firma',
			'navigation_title'=>'Schreiben',
			'navigation_sortorder'=>10,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Schreiben',
				'page_name_intern'=>'weberp_schreiben',
				'page_description'=>'WebERP Schreiben',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_meine_firma',
			'navigation_title'=>'Einstellungen',
			'navigation_sortorder'=>20,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Einstellungen',
				'page_name_intern'=>'weberp_einstellungen',
				'page_description'=>'WebERP Einstellungen',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_meine_firma',
			'navigation_title'=>'Variablen',
			'navigation_sortorder'=>30,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebERP Variablen',
				'page_name_intern'=>'weberp_variablen',
				'page_description'=>'WebERP Variablen',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>0,
			'navigation_title'=>'VIS',
			'navigation_sortorder'=>999,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header VIS',
				'page_name_intern'=>'header_vis2',
				'page_description'=>'Header VIS2',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_vis2',
			'navigation_title'=>'Navigation',
			'navigation_sortorder'=>10,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'VIS Navigation',
				'page_name_intern'=>'vis_navigation',
				'page_description'=>'VIS Navigation',
				'page_ispublic'=>1,
				'permission'=>array('link','view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_vis2',
			'navigation_title'=>'Benutzer',
			'navigation_sortorder'=>20,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'VIS Benutzer',
				'page_name_intern'=>'vis_user',
				'page_description'=>'VIS Benutzer',
				'page_ispublic'=>1,
				'permission'=>array('link','view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
	);

	osW_Tool_VIS2::getInstance()->parseScript($_vis2_script, $this, 1);

	$this->data['messages'][]='VIS2:JBSNewMedia:WebERP configured successfully';
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	$this->data['messages'][]='VIS2:JBSNewMedia:WebERP configured successfully';
}

?>