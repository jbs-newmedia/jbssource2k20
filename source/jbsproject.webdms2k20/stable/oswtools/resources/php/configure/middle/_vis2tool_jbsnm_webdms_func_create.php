<?php

$this->data['settings']=array();

$this->data['settings']['data']=array(
	'page_title'=>'VIS2:JBSNewMedia:WebDMS Settings',
);

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	foreach ($this->data['values_post'] as $key => $values) {
		$this->data['values_json'][$key]=$values['value'];
	}

	if ((isset($this->data['values_json']['vis2_tool_jbsnm_webdms']))&&($this->data['values_json']['vis2_tool_jbsnm_webdms']==1)) {
		osW_Tool_Database::addDatabase('default', array('type'=>'mysql', 'database'=>$this->data['values_json']['database_db'], 'server'=>$this->data['values_json']['database_server'], 'username'=>$this->data['values_json']['database_username'], 'password'=>$this->data['values_json']['database_password'], 'pconnect'=>false, 'prefix'=>$this->data['values_json']['database_prefix']));
	}

	$_vis2_script=array();
	$_vis2_script['tool']=array(
		'tool_name'=>'JBSNM:WebDMS',
		'tool_name_intern'=>'webdms',
		'tool_description'=>'JBSNM:WebDMS',
		'tool_ispublic'=>1,
		'tool_hide_logon'=>0,
		'tool_hide_navigation'=>0,
		'tool_use_mandant'=>1,
		'tool_use_mandantswitch'=>1
	);
	$_vis2_script['group']=array();
	$_vis2_script['group'][1]=array(
		'group_name'=>'JBSNM:WebDMS-Admin',
		'group_name_intern'=>'jbsnmwebdms_admin',
		'group_description'=>'JBSNM:WebDMS-Admin',
		'group_ispublic'=>1,
	);
	$_vis2_script['group'][2]=array(
		'group_name'=>'JBSNM:WebDMS-User',
		'group_name_intern'=>'jbsnmwebdms_user',
		'group_description'=>'JBSNM:WebDMS-User',
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
			'navigation_title'=>'Dokumente',
			'navigation_sortorder'=>80,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header Dokumente',
				'page_name_intern'=>'header_dokumente',
				'page_description'=>'Header Dokumente',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_dokumente',
			'navigation_title'=>'Verwalten',
			'navigation_sortorder'=>20,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebDMS Dokumente verwalten',
				'page_name_intern'=>'webdms_dokumente_verwalten',
				'page_description'=>'WebDMS Dokumente verwalten',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_dokumente',
			'navigation_title'=>'Explorer',
			'navigation_sortorder'=>30,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebDMS Dokumente Explorer',
				'page_name_intern'=>'webdms_dokumente_explorer',
				'page_description'=>'WebDMS Dokumente Explorer',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'',
			'navigation_title'=>'Einstellungen',
			'navigation_sortorder'=>110,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'Header Einstellungen',
				'page_name_intern'=>'header_einstellungen',
				'page_description'=>'Header Einstellungen',
				'page_ispublic'=>1,
				'permission'=>array('link'),
			),
			'permission'=>array(
				1=>array('link'),
			),
		),
		array(
			'navigation_parent_id'=>'header_einstellungen',
			'navigation_title'=>'Allgemein',
			'navigation_sortorder'=>20,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebDMS Einstellungen',
				'page_name_intern'=>'webdms_einstellungen',
				'page_description'=>'WebDMS Einstellungen',
				'page_ispublic'=>1,
				'permission'=>array('link', 'view'),
			),
			'permission'=>array(
				1=>array('link','view'),
			),
		),
		array(
			'navigation_parent_id'=>'header_einstellungen',
			'navigation_title'=>'Variablen',
			'navigation_sortorder'=>30,
			'navigation_ispublic'=>1,
			'page'=>array(
				'page_name'=>'WebDMS Variablen',
				'page_name_intern'=>'webdms_variablen',
				'page_description'=>'WebDMS Variablen',
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

	$this->data['messages'][]='VIS2:JBSNewMedia:WebDMS configured successfully';
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	$this->data['messages'][]='VIS2:JBSNewMedia:WebDMS configured successfully';
}

?>