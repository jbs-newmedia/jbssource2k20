<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

// width
$w=0;
if (isset($_GET['w'])) {
	$w=intval($_GET['w']);
}

// height
$h=0;
if (isset($_GET['h'])) {
	$h=intval($_GET['h']);
}

// size (font)
$s=0;
if (isset($_GET['s'])) {
	$s=intval($_GET['s']);
}

// font
$f='';
if (isset($_GET['f'])) {
	$f=$_GET['f'];
}

// text
$t='';
if (isset($_GET['t'])) {
	$t=$_GET['t'];
}

// color font
$cf='';
if (isset($_GET['cf'])) {
	$cf=$_GET['cf'];
}

// color background
$cb='';
if (isset($_GET['cb'])) {
	$cb=$_GET['cb'];
}

if ($w>4096) {
	$w=4096;
}

if ($w<0) {
	$w=0;
}

if ($h>4096) {
	$h=4096;
}

if ($h<0) {
	$h=0;
}

if ($w==0) {
	$w=640;
}

if ($h==0) {
	$h=360;
}

if ($s==0) {
	$s=bcdiv($h, 6);
	if ($s<12) {
		$s=bcdiv($h, 5);
		if ($s<12) {
			$s=bcdiv($h, 4);
			if ($s<12) {
				$s=bcdiv($h, 3);
				if ($s<12) {
					$s=bcdiv($h, 2);
				}
			}
		}
	}

	#$s=40;
}

switch ($f) {
	default:
		$f='PTS75F';
		break;
}

if ($t=='') {
	$t=$w.' x '.$h;
}

if ($cf=='') {
	$cf='cccccc';
}

if ($cb=='') {
	$cb='777777';
}

if ((hexdec(substr($cf, 0, 2))<0)||(hexdec(substr($cf, 0, 2))>255)) {
	$cf='cccccc';
}
if ((hexdec(substr($cf, 2, 2))<0)||(hexdec(substr($cf, 2, 2))>255)) {
	$cf='cccccc';
}
if ((hexdec(substr($cf, 4, 2))<0)||(hexdec(substr($cf, 4, 2))>255)) {
	$cf='cccccc';
}

if ((hexdec(substr($cb, 0, 2))<0)||(hexdec(substr($cb, 0, 2))>255)) {
	$cb='777777';
}
if ((hexdec(substr($cb, 2, 2))<0)||(hexdec(substr($cb, 2, 2))>255)) {
	$cb='777777';
}
if ((hexdec(substr($cb, 4, 2))<0)||(hexdec(substr($cb, 4, 2))>255)) {
	$cb='777777';
}

$f=JBSNewMedia\Site\Core::getResourcePath().'font'.DIRECTORY_SEPARATOR.$f.'.ttf';

# http://php.net/manual/de/function.imagettfbbox.php#105593
function calculateTextBox($text, $fontFile, $fontSize, $fontAngle) {
	/************
	simple function that calculates the *exact* bounding box (single pixel precision).
	The function returns an associative array with these keys:
	left, top:  coordinates you will pass to imagettftext
	width, height: dimension of the image you have to create
	 *************/
	$rect = imagettfbbox($fontSize, $fontAngle, $fontFile, $text);
	$minX = min(array($rect[0], $rect[2], $rect[4], $rect[6]));
	$maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6]));
	$minY = min(array($rect[1], $rect[3], $rect[5], $rect[7]));
	$maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7]));

	return array(
		"left"   => abs($minX) - 1,
		"top"	=> abs($minY) - 1,
		"width"  => $maxX - $minX,
		"height" => $maxY - $minY,
		"box"	=> $rect
	);
}

$text_angle=0;
$text_padding=10;

$im=imagecreatetruecolor($w, $h);
$color=imagecolorallocatealpha($im, hexdec(substr($cb, 0, 2)), hexdec(substr($cb, 2, 2)), hexdec(substr($cb, 4, 2)), 0);
imagefill($im, 0, 0, $color);
imagesavealpha($im, true);

$result=calculateTextBox($t, $f, $s, 0);
if ($result['width']>$w) {
	$s=round(($s/$result['width'])*$w)-2;
	$result=calculateTextBox($t, $f, $s, 0);
}

if ($s<6) {
	$t='';
}

$color=imagecolorallocatealpha($im, hexdec(substr($cf, 0, 2)), hexdec(substr($cf, 2, 2)), hexdec(substr($cf, 4, 2)), 0);
imagettftext($im, $s, 0, bcdiv($w, 2)-(bcdiv($result['width'], 2)), bcdiv($h, 2)+(bcdiv($result['height'], 2)), $color, $f, $t);

header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);

?>