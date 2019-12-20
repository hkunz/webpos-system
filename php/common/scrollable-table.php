<?php

//Enclose selected query fields with {{ }} to exclude them from table layout if it's needed for in_data only
function create_scrollable_table($id, $con, $query, $column_widths, $column_checks, & $in_data) {
	$vscroll_w = 12;
	$ccy = $_SESSION['currency'];
	$cols = count($column_widths);
	$result = $con->query($query);
	$tbody = '';
	$thead = '<tr>';
	$tfoot = '<tr>';
	$thead_complete = false;
	while($row = $result->fetch_array()) {
		$tbody .= '<tr>';
		$i = 0;
		foreach ($row as $key => $value) {
			if (is_numeric($key)) {
				unset($row[$key]);
				continue;
			}
			if (is_numeric(strpos($key, '{{'))) {
				$newkey = str_replace('}}','',str_replace('{{','',$key));
				unset($row[$key]);
				$row[$newkey] = $value;
				continue;
			}
			$type = $column_checks ? $column_checks[$i] : 0;
			$isccy = ($type === 1);
			$align_right = ($isccy || $type === 3);
			$ccyspan = $isccy ? "<span style='margin-right:2px;'>$ccy</span>" : '';
			$width = $column_widths ? $column_widths[$i] : 'auto';
			$css_max = $width === '100%' ? "max-width:300px;overflow:hidden;text-overflow:ellipsis;" : '';
			$cellvalue = $isccy ? number_format($row[$key], 2) : $row[$key];
			$tbody .= "<td style='width:$width;text-align:" . ($align_right ? 'right' : 'left') . ";$css_max' nowrap>$ccyspan$cellvalue</td>";
			++$i;
			if ($thead_complete) {
				continue;
			}
			$header = $key;
			$thead .= "<th style='width:$width;text-align:" . ($align_right ? 'right' : 'left') . "' nowrap>$header</th>";
			$tfoot .= "<td style='width:$width;text-align:" . ($align_right ? 'right' : 'left') . "' nowrap>A</td>";
		}
		if (isset($in_data)) {
			array_push($in_data, $row);
		}
		if ($thead_complete === false) {
			$w = ($vscroll_w / 2);
			$thead .= "<th style='padding-left:${w}px;padding-right:${w}px;'></th></tr>";	
			$tfoot .= "<td style='padding-left:${w}px;padding-right:${w}px;'></td></tr>";
			$thead_complete = true;
		}
		$tbody .= '</tr>';
	}
	return "<table id='" . (isset($id) ? $id : 'scroll_table') . "' class='common-table common-table-scroll' cellspacing='0' cellpadding='0'><thead class='scroll'>$thead</thead><tbody class='scroll'>$tbody</tbody></table>"; //<tfoot class='scroll' style='display:none;'>$tfoot</tfoot></table>";
}
?>

