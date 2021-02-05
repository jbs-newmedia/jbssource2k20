<?php

/**
 * This file is part of the Core package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package Core
 * @link https://jbs-newmedia.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace JBSNewMedia\Core;

use osWFrame\Core as osWFrame;

class Kalender {

	use osWFrame\BaseStaticTrait;

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
	 * Kalender constructor.
	 */
	private function __construct() {
	}

	/**
	 * Ermittelt ob das Datum ein Arbeitstag ist.
	 *
	 * @param int $month
	 * @param int $day
	 * @param int $year
	 * @param string $state
	 * @param bool $saturday
	 * @param bool $sunday
	 * @return bool|null
	 */
	public static function isWorkingDay(int $month=0, int $day=0, int $year=0, string $state='', bool $saturday=true, bool $sunday=true):?bool {
		if ($month==0) {
			$month=date('n');
		}
		if ($day==0) {
			$day=date('j');
		}
		if ($year==0) {
			$year=date('Y');
		}

		if (!checkdate($month, $day, $year)) {
			return null;
		}

		if ((self::isHoliday($month, $day, $year, $state)!==true)&&(self::isWeekend($month, $day, $year, $saturday, $sunday)!==true)) {
			return true;
		}

		return false;
	}

	/**
	 * Ermittelt die Anzahl der Tage bis zum Datum von X Werktagen.
	 *
	 * @param int $days
	 * @param string $state
	 * @param bool $saturday
	 * @param bool $sunday
	 * @return int
	 */
	public static function nextWorkingDayCount(int $days=0, string $state='', bool $saturday=true, bool $sunday=true):int {
		$workdays=0;
		$free=0;
		$i=1;
		while ($days>$workdays) {
			$ts=mktime(date('H'), date('i'), date('s'), date('n'), date('j')+$i, date('Y'));
			$i++;
			if (self::isWorkingDay(date('n', $ts), date('j', $ts), date('Y', $ts), $state, $saturday, $sunday)===true) {
				$workdays++;
			} else {
				$free++;
			}
		}

		return $workdays+$free;
	}

	/**
	 * Gibt den Namen des Feiertages zurück. Leerer String wenn kein Feiertag ist.
	 *
	 * @param int $month
	 * @param int $day
	 * @param int $year
	 * @param string $state
	 * @return string|null
	 */
	public static function getHoliday(int $month=0, int $day=0, int $year=0, string $state=''):?string {
		if ($month==0) {
			$month=date('n');
		}
		if ($day==0) {
			$day=date('j');
		}
		if ($year==0) {
			$year=date('Y');
		}

		$state=strtolower($state);
		if (!in_array($state, ['bw', 'by', 'be', 'bb', 'hb', 'hh', 'he', 'mv', 'ni', 'nw', 'rp', 'sl', 'sn', 'st', 'sh', 'th'])) {
			$state='';
		}

		if (!checkdate($month, $day, $year)) {
			return null;
		}

		$easter_day=date('j', easter_date($year));
		$easter_month=date('n', easter_date($year));

		if (($month==1)&&($day==1)) {
			return 'Neujahr';
		} elseif (($month==1)&&($day==6)&&(in_array($state, ['bw', 'by', 'st']))) {
			return 'Heilige Drei Könige';
		} elseif (($month==$easter_month)&&($day==$easter_day)) {
			return 'Ostersonntag';
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day-2, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day-2, $year)))) {
			return 'Karfreitag';
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+1, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+1, $year)))) {
			return 'Ostermontag';
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+39, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+39, $year)))) {
			return 'Christi Himmelfahrt';
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+49, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+49, $year)))) {
			return 'Pfingstsonntag';
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+50, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+50, $year)))) {
			return 'Pfingstmontag';
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+60, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+60, $year)))&&(in_array($state, ['bw', 'by', 'he', 'nw', 'rp', 'sl']))) {
			return 'Fronleichnam';
		} elseif (($month==5)&&($day==1)) {
			return 'Maifeiertag';
		} elseif (($month==8)&&($day==15)&&(in_array($state, ['by', 'sl']))) {
			return 'Mariä Himmelfahrt';
		} elseif (($month==10)&&($day==3)) {
			return 'Tag der deutschen Einheit';
		} elseif (($month==10)&&($day==31)&&(in_array($state, ['bb', 'mv', 'sn', 'st', 'th']))) {
			return 'Reformationstag';
		} elseif (($month==11)&&($day==1)&&(in_array($state, ['bw', 'by', 'nw', 'rp', 'sl']))) {
			return 'Allerheiligen';
		} elseif (($month==11)&&($day==21)&&(in_array($state, ['sn']))) {
			return 'Buß- und Bettag';
		} elseif (($month==12)&&($day==24)) {
			return 'Heiliger Abend';
		} elseif (($month==12)&&($day==25)) {
			return '1. Weihnachtstag';
		} elseif (($month==12)&&($day==26)) {
			return '2. Weihnachtstag';
		} elseif (($month==12)&&($day==31)) {
			return 'Sylvester';
		}

		return '';
	}

	/**
	 * Ermittelt ob das Datum ein Feiertag ist.
	 *
	 * @param int $month
	 * @param int $day
	 * @param int $year
	 * @param string $state
	 * @return bool|null
	 */
	public static function isHoliday(int $month=0, int $day=0, int $year=0, string $state=''):?bool {
		if ($month==0) {
			$month=date('n');
		}
		if ($day==0) {
			$day=date('j');
		}
		if ($year==0) {
			$year=date('Y');
		}

		$state=strtolower($state);
		if (!in_array($state, ['bw', 'by', 'be', 'bb', 'hb', 'hh', 'he', 'mv', 'ni', 'nw', 'rp', 'sl', 'sn', 'st', 'sh', 'th'])) {
			$state='';
		}

		if (!checkdate($month, $day, $year)) {
			return null;
		}

		$easter_day=date('j', easter_date($year));
		$easter_month=date('n', easter_date($year));

		if (($month==1)&&($day==1)) {
			return true;
		} elseif (($month==1)&&($day==6)&&(in_array($state, ['bw', 'by', 'st']))) {
			return true;
		} elseif (($month==$easter_month)&&($day==$easter_day)) {
			return true;
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day-2, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day-2, $year)))) {
			return true;
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+1, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+1, $year)))) {
			return true;
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+39, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+39, $year)))) {
			return true;
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+49, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+49, $year)))) {
			return true;
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+50, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+50, $year)))) {
			return true;
		} elseif (($month==date('n', mktime(12, 0, 0, $easter_month, $easter_day+60, $year)))&&($day==date('j', mktime(0, 0, 0, $easter_month, $easter_day+60, $year)))&&(in_array($state, ['bw', 'by', 'he', 'nw', 'rp', 'sl']))) {
			return true;
		} elseif (($month==5)&&($day==1)) {
			return true;
		} elseif (($month==8)&&($day==15)&&(in_array($state, ['by', 'sl']))) {
			return true;
		} elseif (($month==10)&&($day==3)) {
			return true;
		} elseif (($month==10)&&($day==31)&&(in_array($state, ['bb', 'mv', 'sn', 'st', 'th']))) {
			return true;
		} elseif (($month==11)&&($day==1)&&(in_array($state, ['bw', 'by', 'nw', 'rp', 'sl']))) {
			return true;
		} elseif (($month==11)&&($day==21)&&(in_array($state, ['sn']))) {
			return true;
		} elseif (($month==12)&&($day==24)) {
			return true;
		} elseif (($month==12)&&($day==25)) {
			return true;
		} elseif (($month==12)&&($day==26)) {
			return true;
		} elseif (($month==12)&&($day==31)) {
			return true;
		}

		return false;
	}

	/**
	 * Ermittelt ob das Datum ein Tag am Wochenende ist.
	 *
	 * @param int $month
	 * @param int $day
	 * @param int $year
	 * @param bool $saturday
	 * @param bool $sunday
	 * @return bool|null
	 */
	public static function isWeekend(int $month=0, int $day=0, int $year=0, bool $saturday=true, bool $sunday=true):?bool {
		if ($month==0) {
			$month=date('n');
		}
		if ($day==0) {
			$day=date('j');
		}
		if ($year==0) {
			$year=date('Y');
		}

		if (!checkdate($month, $day, $year)) {
			return null;
		}

		$date_ar=getdate(mktime(0, 0, 0, $month, $day, $year));
		if ((($sunday===true)&&($date_ar['wday']==0))||(($saturday===true)&&($date_ar['wday']==6))) {
			return true;
		}

		return false;
	}

}

?>