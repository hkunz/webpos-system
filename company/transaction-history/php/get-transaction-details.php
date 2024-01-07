<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");
require_once("${root}php/common/scrollable-table.php");

$transaction_id = $_POST['value'];
$table_id = $_POST['table_id'] ?? null;

//workaround added spaces between row_number to have a unique cellcontent searchable by TableRowHandler
$query = "
SELECT CONCAT(' ',row_number() OVER (),' ') '#', ii.item_id `{{item_id}}`, ii.item_description 'Product Description', BBB.sell_price 'Unit Price', t.amount Qty, BBB.sell_price * t.amount 'Cost'
FROM items_transactions_details t
LEFT JOIN (
    SELECT i.item_id,i.item_description FROM items i
) ii
ON t.item_id=ii.item_id
LEFT JOIN (
    SELECT BB.item_id, BB.sell_price, BB.sell_price_asofdate
    FROM (
        SELECT B.item_id, B.sell_price, B.sell_price_asofdate,
        ROW_NUMBER() OVER (
            PARTITION BY B.item_id
                 ORDER BY
                     B.sell_price_asofdate DESC,
                     B.row_id DESC
        ) as 's_row_num'
        FROM items_prices B
    ) BB
    WHERE BB.s_row_num=1
) BBB ON ii.item_id = BBB.item_id
WHERE t.transaction_id=$transaction_id;
";

$data = array();
$table = create_scrollable_table($table_id, $sql_con, $query, array('25px', '100%', '100px', '90px', '180px'), array(0, 0, 1, 2, 1), $data);
echo '{"transaction_id":"' . $transaction_id . '","content":"' . $table . '","view":"view_transactions_details","data":' . json_encode($data) . '}';

$sql_con->close();
?>

