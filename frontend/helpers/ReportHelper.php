<?php

namespace frontend\helpers;

use yii\helpers\Url;

class ReportHelper
{
	public static function months() {
		return [
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		];
	}
	
	public static function header($params = []) {
		
		switch ($params['view']) {
			case 'outgoing':
				$return = '
					<table class="table-report-header" width="100%">
						<tr>
							<td rowspan="2">
								<h4 style="margin:0;"><b>' . $params['title'] . '</b></h4>
							</td>
							'. ($params['type'] && $params['models'] ? '<td style="width:1px; padding:0 20px">Tipe</td>' : '' ) . '
							<td style="width:1px; padding:0 20px">Periode</td>
						</tr>
						<tr>
							'. ($params['type'] && $params['models'] ? '<td style="width:1px; padding:0 20px; white-space:nowrap"><b>' . ($params['type'] ? $params['models'][0]->submission->item->type : '<span class="text-muted">(semua)</span>') . '</b></td>' : '' ) . '
							<td style="width:1px; padding:0 20px; white-space:nowrap"><b>' . ($params['date_start'] ? ($params['date_start'] == $params['date_end'] ? $params['date_start'] : $params['date_start'] . ' - ' . $params['date_end']) : '<span class="text-muted">(semua)</span>') . '</b></td>
						</tr>
					</table>
				';
				break;
			
			default:
				$return = null;
				break;
		}	

		

		return $return;
	}

	public static function footer($to_pdf = false) {
		$fontSize 	= $to_pdf ? 'font-size:11px' : '';

		return;
	}
}
