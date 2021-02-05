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

namespace JBSNewMedia\WebERP;

trait BaseVerwaltungTrait {

	public ?object $obj_Verwaltung=null;

	/**
	 * @param object $Verwaltung
	 * @return bool
	 */
	public function setVerwaltung(object $Verwaltung):bool {
		$this->obj_Verwaltung=$Verwaltung;

		return true;
	}

	/**
	 *
	 * @return object|null
	 */
	public function getVerwaltung():?object {
		return $this->obj_Verwaltung;
	}

}

?>