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

?><div class="container_tool_content_box">

	<div class="row">

		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Umsatz</h6>
				</div>
				<div class="card-body">
					<div class="chart-area">
						<canvas id="line_chart_umsatz"></canvas>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Rechnungen</h6>
				</div>
				<div class="card-body">
					<div class="chart-area">
						<canvas id="line_chart_rechnungen"></canvas>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Umsatz pro Rechnung</h6>
				</div>
				<div class="card-body">
					<div class="chart-area">
						<canvas id="line_chart_rechnungen_umsatz"></canvas>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>