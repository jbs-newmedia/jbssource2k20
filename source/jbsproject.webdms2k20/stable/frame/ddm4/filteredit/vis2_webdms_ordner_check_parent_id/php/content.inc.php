<?php

/**
 * This file is part of the VIS2:WebDMS package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebDMS
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

if ($this->getIndexElementStorage()==$this->getDoEditElementStorage('ordner_parent_id')) {
	$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $this->getFilterElementStorage($element)));
	$this->setFilterErrorElementStorage($element, true);
}

?>