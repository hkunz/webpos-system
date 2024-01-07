<?php

$root_defined = isset($_SESSION['root']);
$root = $root_defined ? $_SESSION['root'] : "";

require_once("${root}/php/libs/MobileDetect.php");

// Enclose selected query fields with {{ }} to exclude them from table layout if it's needed for in_data only

// @column_formats values:
// 0 => no formatting align left
// 1 => currency format and align right
// 2 => just align right
// 3 => button

function create_scrollable_table($id, $con, $query, $column_widths, $column_formats, & $in_data = null) {
	$ismobile = (new MobileDetect)->isMobile();
	$vscroll_w = 12;
	$ccy = $_SESSION['currency'];
	$cols = count($column_widths);
	$result = $con->query($query);
	$tbody = '';
	$thead = '<tr>';
	$tfoot = '<tr>';
	$thead_complete = false;
	$row_id = 0;
	while($row = $result->fetch_array()) {
		$tbody .= '<tr id=\"row_' . ++$row_id . '\">';
		$i = 0;
		$first_cellvalue = null;
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
			$fmt = $column_formats ? $column_formats[$i] : 0;
			$isccy = ($fmt === 1);
			$align_right = ($isccy || $fmt === 2);
			$ccyspan = $isccy ? "<span style='margin-right:2px;'>$ccy</span>" : '';
			$width = $column_widths ? $column_widths[$i] : 'auto';
			$css_max = $width === '100%' ? "max-width:300px;overflow:hidden;text-overflow:ellipsis;" : '';
			if ($first_cellvalue === null) {
				$first_cellvalue = $row[$key];
			}
			if ($fmt === 3) {
				$event = 'document.getElementById(\"eventdispatcher\").dispatchEvent(new CustomEvent(\"table-button-click\", {\"detail\":{\"button_id\":\"button_' . $i . '\",\"first_cellvalue\":\"' . $first_cellvalue . '\",\"row_id\":\"row_' . $row_id . '\",\"row_number\":\"' . $row_id . '\"}}))';
				$cellvalue = "<label id='button_$i' onclick='event.stopImmediatePropagation();$event' style='cursor:pointer;box-shadow: 0px 0px 0px 0px #000;font-size:" . ($ismobile ? "20px" : "15px") . ";background-color:#33AA33;border:1px solid #fff;border-radius:5px;margin-bottom:2px;'>&nbsp;<b>${row[$key]}</b>&nbsp;</label>";
			} else {
				$cellvalue = $isccy ? number_format($row[$key], 2) : htmlentities($row[$key]);
			}
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

