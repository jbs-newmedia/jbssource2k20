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

$ddm_search_case_array[]=$this->getGroupOption('alias', 'database').'.'.$key.' LIKE '.self::getConnection()->escapeString('%'.$search['value'].'%');

?>