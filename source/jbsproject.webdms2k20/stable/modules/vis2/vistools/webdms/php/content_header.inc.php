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

$VIS2_WebDMS_Verwaltung=new \JBSNewMedia\WebDMS\Verwaltung($VIS2_Mandant->getId());
$VIS2_WebDMS_Verwaltung->setUserId($VIS2_User->getId());

?>