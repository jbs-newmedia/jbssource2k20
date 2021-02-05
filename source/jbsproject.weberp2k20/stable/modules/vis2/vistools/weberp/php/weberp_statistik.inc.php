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

$osW_Lib=new \osWFrame\Core\JSLib($osW_Template);
$osW_Lib->load('chart.js');

$osW_Statistik=new \JBSNewMedia\WebERP\Statistik($VIS2_Mandant->getId());
$osW_Statistik->getStatistik();

$osW_Template->addJSCodeHead('

function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + "").replace(",", "").replace(" ", "");
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === "undefined") ? "." : thousands_sep,
		dec = (typeof dec_point === "undefined") ? "," : dec_point,
		s = "",
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return "" + Math.round(n * k) / k;
		};
	s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || "").length < prec) {
		s[1] = s[1] || "";
		s[1] += new Array(prec - s[1].length + 1).join("0");
	}
	return s.join(dec);
}

$(document).ready(function () {
/* Umsatz */
	var ctx = $("#line_chart_umsatz");
	var line_chart_umsatz = new Chart(ctx, {
		type: "line",
		data: {
			labels: ["'.implode('", "', array_keys($osW_Statistik->getUmsatzNetto())).'"],
			datasets: [{
				label: "Brutto",
				backgroundColor: "rgb(255, 99, 132)",
				borderColor: "rgb(255, 99, 132)",
				data: ['.implode(', ', $osW_Statistik->getUmsatzBrutto()).'],
				fill: false
			},
				{
					label: "Netto",
					backgroundColor: "rgb(54, 162, 235)",
					borderColor: "rgb(54, 162, 235)",
					data: ['.implode(', ', $osW_Statistik->getUmsatzNetto()).'],
					fill: false
				}]
		},
		options: {
			maintainAspectRatio: false,
			layout: {
				padding: {
					left: 10,
					right: 25,
					top: 25,
					bottom: 0
				}
			},
			scales: {
				xAxes: [{
					time: {
						unit: "date"
					},
					gridLines: {
						display: false,
						drawBorder: false
					},
					ticks: {
						maxTicksLimit: 7
					}
				}],
				yAxes: [{
					ticks: {
						maxTicksLimit: 5,
						padding: 10,
						callback: function (value, index, values) {
							return number_format(value) + " Euro";
						}
					},
					gridLines: {
						color: "rgb(234, 236, 244)",
						zeroLineColor: "rgb(234, 236, 244)",
						drawBorder: false,
						borderDash: [2],
						zeroLineBorderDash: [2]
					}
				}]
			},
			legend: {
				display: false
			},
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				titleMarginBottom: 10,
				titleFontColor: "#6e707e",
				titleFontSize: 14,
				borderColor: "#dddfeb",
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				intersect: false,
				mode: "index",
				caretPadding: 10,
				callbacks: {
					label: function (tooltipItem, chart) {
						var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || "";
						return datasetLabel + ": " + number_format(tooltipItem.yLabel) + " Euro";
					}
				}
			}
		}
	});
	
	/* Rechnungen */
	var ctx = $("#line_chart_rechnungen");
	var line_chart_rechnungen = new Chart(ctx, {
		type: "line",
		data: {
			labels: ["'.implode('", "', array_keys($osW_Statistik->getAnzahlRechnungen())).'"],
			datasets: [{
				label: "Rechnung pro Jahr",
				backgroundColor: "rgb(255, 99, 132)",
				borderColor: "rgb(255, 99, 132)",
				data: ['.implode(', ', $osW_Statistik->getAnzahlRechnungen()).'],
				fill: false
			}],
		},
		options: {
			maintainAspectRatio: false,
			layout: {
				padding: {
					left: 10,
					right: 25,
					top: 25,
					bottom: 0
				}
			},
			scales: {
				xAxes: [{
					time: {
						unit: "date"
					},
					gridLines: {
						display: false,
						drawBorder: false
					},
					ticks: {
						maxTicksLimit: 7
					}
				}],
				yAxes: [{
					ticks: {
						maxTicksLimit: 5,
						padding: 10
					},
					gridLines: {
						color: "rgb(234, 236, 244)",
						zeroLineColor: "rgb(234, 236, 244)",
						drawBorder: false,
						borderDash: [2],
						zeroLineBorderDash: [2]
					}
				}],
			},
			legend: {
				display: false
			},
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				titleMarginBottom: 10,
				titleFontColor: "#6e707e",
				titleFontSize: 14,
				borderColor: "#dddfeb",
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				intersect: false,
				mode: "index",
				caretPadding: 10
			}
		}
	});
	
		/* Umsatz */
	var ctx = $("#line_chart_rechnungen_umsatz");
	var line_chart_rechnungen_umsatz = new Chart(ctx, {
		type: "line",
		data: {
			labels: ["'.implode('", "', array_keys($osW_Statistik->getUmsatzProRechnungNetto())).'"],
			datasets: [{
				label: "Brutto",
				backgroundColor: "rgb(255, 99, 132)",
				borderColor: "rgb(255, 99, 132)",
				data: ['.implode(', ', $osW_Statistik->getUmsatzProRechnungBrutto()).'],
				fill: false
			},
				{
					label: "Netto",
					backgroundColor: "rgb(54, 162, 235)",
					borderColor: "rgb(54, 162, 235)",
					data: ['.implode(', ', $osW_Statistik->getUmsatzProRechnungNetto()).'],
					fill: false
				}],
		},
		options: {
			maintainAspectRatio: false,
			layout: {
				padding: {
					left: 10,
					right: 25,
					top: 25,
					bottom: 0
				}
			},
			scales: {
				xAxes: [{
					time: {
						unit: "date"
					},
					gridLines: {
						display: false,
						drawBorder: false
					},
					ticks: {
						maxTicksLimit: 7
					}
				}],
				yAxes: [{
					ticks: {
						maxTicksLimit: 5,
						padding: 10,
						callback: function (value, index, values) {
							return number_format(value) + " Euro";
						}
					},
					gridLines: {
						color: "rgb(234, 236, 244)",
						zeroLineColor: "rgb(234, 236, 244)",
						drawBorder: false,
						borderDash: [2],
						zeroLineBorderDash: [2]
					}
				}],
			},
			legend: {
				display: false
			},
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				titleMarginBottom: 10,
				titleFontColor: "#6e707e",
				titleFontSize: 14,
				borderColor: "#dddfeb",
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				intersect: false,
				mode: "index",
				caretPadding: 10,
				callbacks: {
					label: function (tooltipItem, chart) {
						var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || "";
						return datasetLabel + ": " + number_format(tooltipItem.yLabel) + " Euro";
					}
				}
			}
		}
	});
});

');

?>