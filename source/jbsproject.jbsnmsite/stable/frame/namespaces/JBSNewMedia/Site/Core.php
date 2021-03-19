<?php

/**
 * This file is part of the JBSNewMedia-Website package
 *
 * Copyright (C) JBS New Media GmbH (https://jbs-newmedia.com) by Juergen Schwind - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited Proprietary and confidential
 */

namespace JBSNewMedia\Site;

use \osWFrame\Core as Frame;

class Core {

	use Frame\BaseStaticTrait;
	use Frame\BaseTemplateBridgeTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var array
	 */
	private array $navigation=[];

	/**
	 * Main constructor.
	 */
	public function __construct() {
		$this->setNavigation();
	}

	/**
	 * @return string
	 */
	public static function getResourcePath():string {
		$version=self::getVersion();
		$dir=strtolower('JBSNMSite');

		return Frame\Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;
	}

	/**
	 *
	 * @param object $osW_Template
	 * @return object
	 */
	public function setEnvironment(object $Template):object {
		$version=self::getVersion();
		$dir=strtolower('JBSNMSite');
		$name=$version.'.resource';
		$path=self::getResourcePath();
		#if (Frame\Resource::existsResource('JBSNMSite', $name)!==true) {
		$files=['css'.DIRECTORY_SEPARATOR.'bootstrap-jbsnm.css', 'css'.DIRECTORY_SEPARATOR.'jbsnm.css', 'img'.DIRECTORY_SEPARATOR.'jbs-logo-symbol.svg', 'img'.DIRECTORY_SEPARATOR.'jbs-logo.svg', 'js'.DIRECTORY_SEPARATOR.'jbsnm.js', 'img'.DIRECTORY_SEPARATOR.'osw-logo.svg', 'font'.DIRECTORY_SEPARATOR.'PTS75F.ttf'];

		Frame\Resource::copyResourcePath('modules'.DIRECTORY_SEPARATOR.'jbsnm'.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $files);
		Frame\Resource::writeResource('JBSNMSite', $name, time());
		#}

		$this->setTemplate($Template);

		$this->addTemplateJSFile('head', $path.'js'.DIRECTORY_SEPARATOR.'jbsnm.js');
		$this->addTemplateCSSFile('head', $path.'css'.DIRECTORY_SEPARATOR.'bootstrap-jbsnm.css');
		$this->addTemplateCSSFile('head', $path.'css'.DIRECTORY_SEPARATOR.'jbsnm.css');

		return $this;
	}

	public function setNavigation():object {
		$this->navigation['header']=[];
		$this->navigation['header']['projekte']['details']=['title'=>'Projekte'];
		$this->navigation['header']['projekte']['links']=[];
		$this->navigation['header']['projekte']['links']['oswframe']['details']=['title'=>'osWFrame', 'module'=>'jbsnm_projekte_oswframe'];
		$this->navigation['header']['tools']['details']=['title'=>'Tools'];
		$this->navigation['header']['tools']['links']=[];
		$this->navigation['header']['tools']['links']['dummyimage']['details']=['title'=>'DummyImage', 'module'=>'jbsnm_tools_dummyimage'];
		$this->navigation['footer']=[];
		$this->navigation['footer']['datenschutz']['details']=['title'=>'Datenschutz', 'module'=>'jbsnm_datenschutz'];
		$this->navigation['footer']['impressum']['details']=['title'=>'Impressum', 'module'=>'jbsnm_impressum'];
		$this->navigation['hidden']['errorlogger']['details']=['title'=>'Fehlerseite', 'module'=>'jbsnm_errorlogger'];

		foreach ($this->navigation as $_part=>$_l_details) {
			foreach ($this->navigation[$_part] as $_page1=>$dummy) {
				if (isset($this->navigation[$_part][$_page1]['details'])) {
					if (!isset($this->navigation[$_part][$_page1]['details']['active'])) {
						$this->navigation[$_part][$_page1]['details']['active']=false;
					}
					if (isset($this->navigation[$_part][$_page1]['details']['module'])) {
						Frame\Language::setModuleName($this->navigation[$_part][$_page1]['details']['module'], $this->navigation[$_part][$_page1]['details']['title']);
						if (!isset($this->navigation[$_part][$_page1]['details']['active'])) {
							$this->navigation[$_part][$_page1]['details']['active']=false;
						}
					}
				}
				if (isset($this->navigation[$_part][$_page1]['links'])) {
					foreach ($this->navigation[$_part][$_page1]['links'] as $_page2 =>$_s_details) {
						if (isset($this->navigation[$_part][$_page1]['links'][$_page2]['details'])) {
							if (isset($this->navigation[$_part][$_page1]['links'][$_page2]['details']['module'])) {
								Frame\Language::setModuleName($this->navigation[$_part][$_page1]['links'][$_page2]['details']['module'], $this->navigation[$_part][$_page1]['links'][$_page2]['details']['title']);
								if (!isset($this->navigation[$_part][$_page1]['links'][$_page2]['details']['active'])) {
									$this->navigation[$_part][$_page1]['links'][$_page2]['details']['active']=false;
								}
							}
						}
					}
				}
			}
		}

		/*
		VIS
		Party9
		9TageTicket
		oneclickbutton
		pogofriends
		mensch²

		jbssync
		veracloud
		*/


		
		return $this;
	}

	public function getNavigation(string $part='', string $page=''):array {
		$navigation=$this->navigation;
		foreach ($navigation as $_part=>$_l_details) {
			foreach ($navigation[$_part] as $_page1=>$dummy) {
				if (isset($navigation[$_part][$_page1]['details'])) {
					if (!isset($navigation[$_part][$_page1]['details']['active'])) {
						$navigation[$_part][$_page1]['details']['active']=false;
					}
					if (isset($navigation[$_part][$_page1]['details']['module'])) {
						if (!isset($navigation[$_part][$_page1]['details']['active'])) {
							$navigation[$_part][$_page1]['details']['active']=false;
						}
						if ((isset($navigation[$_part][$_page1]['details']['module']))&&($navigation[$_part][$_page1]['details']['module']==$page)) {
							$navigation[$_part][$_page1]['details']['active']=true;
						}
					}
				}
				if (isset($navigation[$_part][$_page1]['links'])) {
					foreach ($navigation[$_part][$_page1]['links'] as $_page2 =>$_s_details) {
						if (isset($navigation[$_part][$_page1]['links'][$_page2]['details'])) {
							if (isset($navigation[$_part][$_page1]['links'][$_page2]['details']['module'])) {
								if (!isset($navigation[$_part][$_page1]['links'][$_page2]['details']['active'])) {
									$navigation[$_part][$_page1]['links'][$_page2]['details']['active']=false;
								}
								if ((isset($navigation[$_part][$_page1]['links'][$_page2]['details']['module']))&&($navigation[$_part][$_page1]['links'][$_page2]['details']['module']==$page)) {
									$navigation[$_part][$_page1]['details']['active']=true;
									$navigation[$_part][$_page1]['links'][$_page2]['details']['active']=true;
								}
							}
						}
					}
				}
			}
		}
		if ($part=='') {
			return $navigation[$part];

		} elseif (isset($navigation[$part])) {
			return $navigation[$part];
		}

		return [];
	}

}

?>