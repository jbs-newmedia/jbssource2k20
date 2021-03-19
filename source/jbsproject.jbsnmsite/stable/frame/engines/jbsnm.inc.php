<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$osW_Template=new \osWFrame\Core\Template();

$osW_jQuery3=new \osWFrame\Core\jQuery3($osW_Template);

$osW_Bootstrap4=new \osWFrame\Core\Bootstrap4($osW_Template);

$osW_FontAwesome5=new \osWFrame\Core\FontAwesome5($osW_Template);

$osW_jQuery3->loadPlugin('easing');

$Site=new \JBSNewMedia\Site\Core();
$Site->setEnvironment($osW_Template);

$osW_Template->setVar('Site', $Site);

\osWFrame\Core\Settings::setStringVar('frame_current_module', \osWFrame\Core\Navigation::getModuleByName(\osWFrame\Core\Settings::catchValue('module', \osWFrame\Core\Settings::getStringVar('project_default_module'), 'g')));

\osWFrame\Core\Network::sendHeader('Content-Type: text/html; charset=utf-8');
$osW_Template->addVoidTag('base', ['href'=>\osWFrame\Core\Settings::getStringVar('project_domain_full')]);
$osW_Template->addVoidTag('meta', ['charset'=>'utf-8']);
$osW_Template->addVoidTag('meta', ['http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge']);
$osW_Template->addVoidTag('meta', ['name'=>'viewport', 'content'=>'width=device-width, initial-scale=1, shrink-to-fit=no']);

$osW_FavIcon=new \osWFrame\Core\FavIcon('modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'jbs_logo_symbol_512.png', $osW_Template);
$osW_FavIcon->setIcons2Template();

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content.inc.php';
if (file_exists($file)) {
	include_once $file;

	if(\osWFrame\Core\Settings::getStringVar('project_default_module')==\osWFrame\Core\Settings::getStringVar('frame_default_module')) {
		$osW_Template->addStringTag('title', 'JBS New Media GmbH');
	} else {
		$osW_Template->addStringTag('title', \osWFrame\Core\Language::getModuleName(\osWFrame\Core\Settings::getStringVar('frame_current_module')).' | JBS New Media GmbH');
	}

	$osW_Template->setVarFromFile('content', 'content', \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'modules');

	\osWFrame\Core\Navigation::checkUrl();
} else {
	\osWFrame\Core\Settings::setStringVar('frame_current_module', 'jbsnm_errorlogger');

	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content.inc.php';
	if (file_exists($file)) {
		include_once $file;
	}
	$osW_Template->addStringTag('title', 'Nicht gefunden! | JBS New Media GmbH');

	$osW_Template->setVarFromFile('content', 'content', \osWFrame\Core\Settings::getStringVar('frame_current_module'), 'modules');
}

?>