<?php

/**
 * This file is part of the VIS2:WebERP package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:WebERP
 * @link https://jbs-newmedia.com
 * @license MIT License
 */

if ((isset($data[$element]))&&(!in_array($data[$element], ['']))) {
	if ($this->getSearchElementValidation($element, 'search_like')===false) {
		$ddm_search_case_array[]=$this->getGroupOption('alias', 'database').'.'.$options['name'].' = '.self::getConnection()->escapeString($data[$element]).'';
	} else {
		$ddm_search_case_array[]=$this->getGroupOption('alias', 'database').'.'.$options['name'].' LIKE '.self::getConnection()->escapeString('%'.$data[$element].'%').'';
	}
}

?>