<?php

function create_scrollable_table($con, $query, $column_widths, $currency_checks) {
	$vscroll_w = 12;
	$ccy = $_SESSION['currency'];
	$cols = count($column_widths);
	$result = $con->query($query);
	$tbody = '';
	$thead = '<tr>';
	$thead_complete = false;
	while($row = $result->fetch_array()) {
		$tbody .= '<tr>';
		$i = 0;
		foreach ($row as $key => $value) {
			if (is_numeric($key)) continue;
			$isccy = $currency_checks[$i];
			$ccyspan = $isccy ? "<span style='margin-right:2px;'>$ccy</span>" : '';
			$width = $column_widths[$i];
			$tbody .= "<td style='width:$width;text-align:" . ($isccy ? 'right' : 'left') . "' nowrap>$ccyspan${row[$i]}</td>";
			++$i;
			if ($thead_complete) {
				continue;
			}
			$header = $key;
			$thead .= "<th style='width:$width;text-align:" . ($isccy ? 'right' : 'left') . "' nowrap>$header</th>";
		}
		if ($thead_complete === false) {
			$w = ($vscroll_w / 2);
			$thead .= "<th style='padding-left:${w}px;padding-right:${w}px;'></th></tr>";	
			$thead_complete = true;
		}
		$tbody .= '</tr>';
	}
	return "<table id='customer_table' class='common-table common-table-scroll' cellspacing='0' cellpadding='0'><thead class='scroll'>$thead</thead><tbody class='scroll'>$tbody</tbody></table>";
}
?>

