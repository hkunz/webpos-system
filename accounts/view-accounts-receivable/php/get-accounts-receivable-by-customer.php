<?php
$root = $_SESSION['root'];
require "../../../php/db.php";

$currency = $_POST['currency'];
$customer = $_POST['customer'];

function create_scroll_table_content($con, $query, $column_widths, $currency_checks) {
	$currency = $_POST['currency'];
	$vscroll_w = 12;
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
			$ccyspan = $isccy ? "<span style='margin-right:2px;'>$currency</span>" : '';
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

$query = "SELECT `customer` `Customer`,MAX(`date`) `Last Update Date`,SUM(`grand_total`) `Receivable`,SUM(`payment`) `Payment` FROM `items_transactions` WHERE `type`='SALE' AND `payment` < `grand_total` GROUP BY `customer` ORDER BY `customer` ASC;";

$table_content = create_scroll_table_content($sql_con, $query, array('350px', '195px', '100px', '100px'), array(0, 0, 1, 1));
echo '{"content":"' . $table_content . '"}';
?>

