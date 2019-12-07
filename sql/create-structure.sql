DROP TABLE IF EXISTS items_transactions_details;
DROP TABLE IF EXISTS items_transactions;
DROP TABLE IF EXISTS items_stock;
DROP TABLE IF EXISTS items_prices;
DROP TABLE IF EXISTS items;

CREATE TABLE items (
	item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	bar_code VARCHAR(13) NOT NULL,
	unit ENUM('bar','bot','bx','crd','cut','dz','jar','ld','pad','pc','pd','pg','pk','rm','sbx','set','tie') NOT NULL,
	count SMALLINT UNSIGNED NOT NULL,
	item_description VARCHAR(100) NOT NULL,
	general_name VARCHAR(30) NOT NULL,
	brand_name VARCHAR(25) NOT NULL,
	category ENUM('Electronics','Food Additive','Galenical','Hardware','Household','Personal Accessory','Personal Hygiene','Pharmaceutical','School & Office','Service','Toiletry') NOT NULL,
	supplier_name ENUM('Chuyte','Klebbys','Conchitas','Hypermart','Other') NOT NULL
);

CREATE TABLE items_prices (
    row_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id INT UNSIGNED NOT NULL,
    unit_price_asofdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    unit_price DECIMAL(13,2) NOT NULL,
    sell_price_asofdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sell_price DECIMAL(13,2) NOT NULL,
    FOREIGN KEY (item_id) REFERENCES items(item_id)
)

CREATE TABLE items_stock (
	item_id INT UNSIGNED NOT NULL,
	stock INT UNSIGNED NOT NULL,
	FOREIGN KEY (item_id) REFERENCES items(item_id),
	row_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
);

CREATE TABLE items_transactions (
    transaction_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer VARCHAR(30),
    type ENUM('RESTOCK', 'SALE', 'RETURN', 'LOSS', 'SURPLUS') NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sub_total DECIMAL(13,2) NOT NULL DEFAULT 0,
    service_charge DECIMAL(13,2) NOT NULL DEFAULT 0,
    grand_total DECIMAL(13,2) NOT NULL DEFAULT 0,
    discount DECIMAL(13,2) NOT NULL DEFAULT 0,
    payment DECIMAL(13,2) NOT NULL DEFAULT 0
);

CREATE TABLE items_transactions_details (
	transaction_id INT UNSIGNED NOT NULL,
	item_id INT UNSIGNED NOT NULL,
	amount INT UNSIGNED NOT NULL,
	FOREIGN KEY (item_id) REFERENCES items(item_id),
	FOREIGN KEY (transaction_id) REFERENCES items_transactions(transaction_id),
	PRIMARY KEY (transaction_id, item_id)
);

DROP TABLE IF EXISTS operational_expenses;
CREATE TABLE operational_expenses (
    expense_transaction_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    type ENUM('PRINTING_STOCKS', 'ELECTRICITY') NOT NULL,
    grand_total DECIMAL(13,2) NOT NULL,
    remarks VARCHAR(100) NOT NULL
);

DROP VIEW IF EXISTS view_items_prices_latest;
CREATE VIEW view_items_prices_latest AS
SELECT AAA.item_id, AAA.unit_price, AAA.unit_price_asofdate, BBB.sell_price, BBB.sell_price_asofdate
    FROM (
    SELECT AA.item_id, AA.unit_price, AA.unit_price_asofdate
    FROM (
        SELECT A.item_id, A.unit_price, A.unit_price_asofdate,
        ROW_NUMBER() OVER (
            PARTITION BY A.item_id
                 ORDER BY
                     A.unit_price_asofdate DESC,
                     A.row_id DESC
        ) as 'u_row_num'
        FROM items_prices A
    ) AA
    WHERE AA.u_row_num=1
) AAA
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
) BBB ON AAA.item_id = BBB.item_id


DROP VIEW IF EXISTS view_items;
CREATE VIEW view_items AS
SELECT `item_id`, `bar_code`, `unit`, `count`, `item_description`, `general_name`, `brand_name`, `category`, j.`unit_price_latest`, j.`sell_price_latest`, `supplier_name`, IFNULL(jj.`stock`,0) as 'stock' FROM items i
LEFT JOIN (
    SELECT v.item_id as 'id1', v.unit_price as 'unit_price_latest', v.sell_price as 'sell_price_latest'
    FROM view_items_prices_latest AS v
) j ON i.item_id = j.id1
LEFT JOIN (
    SELECT s.item_id as 'id2', s.stock FROM items_stock s
) jj ON i.item_id = jj.id2
ORDER BY i.item_id DESC;


DROP VIEW IF EXISTS view_items_transactions_prices;
CREATE VIEW view_items_transactions_prices AS
SELECT
    bb.date,
    i.item_id,
    bb.transaction_id,
    (
    SELECT 
    	pp.unit_price
        FROM items_prices pp
        WHERE i.item_id = pp.item_id AND bb.date >= pp.unit_price_asofdate
        ORDER BY pp.unit_price_asofdate DESC, pp.row_id DESC
        LIMIT 1
    ) unit_price,
    (
    SELECT 
    	pp.sell_price
        FROM items_prices pp
        WHERE i.item_id = pp.item_id AND bb.date >= pp.sell_price_asofdate
        ORDER BY pp.sell_price_asofdate DESC, pp.row_id DESC
        LIMIT 1
    ) sell_price
FROM items_transactions bb
LEFT JOIN items_transactions_details t
    ON bb.transaction_id = t.transaction_id
LEFT JOIN items i
    ON i.item_id = t.item_id


DROP VIEW IF EXISTS view_transactions_products;
CREATE VIEW view_transactions_products AS (
    SELECT tt.date, i.item_id, i.unit, i.item_description, p.unit_price, p.sell_price, t.transaction_id, t.amount, t.amount * p.unit_price AS "cost", t.amount * p.sell_price AS "revenue", (t.amount * p.sell_price) - (t.amount * p.unit_price) as "profit"
    FROM items i
    LEFT JOIN items_transactions_details t ON i.item_id = t.item_id
    LEFT JOIN items_transactions tt ON t.transaction_id = tt.transaction_id
    LEFT JOIN view_items_transactions_prices p ON p.transaction_id = tt.transaction_id AND p.item_id = i.item_id
    WHERE tt.type='SALE' AND i.category<>'service'
    ORDER by t.transaction_id DESC
);

DROP VIEW IF EXISTS view_transactions_services;
CREATE VIEW view_transactions_services AS (
    SELECT tt.date, i.item_id, i.item_description, t.transaction_id, t.amount, t.amount * p.unit_price AS "cost", t.amount * p.sell_price AS "revenue"
    FROM items i
    LEFT JOIN items_transactions_details t ON i.item_id = t.item_id
    LEFT JOIN items_transactions tt ON t.transaction_id = tt.transaction_id
    LEFT JOIN view_items_transactions_prices p ON p.transaction_id = tt.transaction_id AND p.item_id = i.item_id
    WHERE tt.type='SALE' AND i.category='service' AND i.general_name<>'Prepaid Load Service'
    ORDER by t.transaction_id DESC
);

DROP VIEW IF EXISTS view_transactions_prepaid_load;
CREATE VIEW view_transactions_prepaid_load AS (
    SELECT tt.date, i.item_id, i.item_description, p.unit_price, p.sell_price, t.transaction_id, t.amount, t.amount * p.unit_price AS "cost", (t.amount * p.sell_price) + tt.service_charge AS 'revenue', (t.amount * p.sell_price) - (t.amount * p.unit_price) + tt.service_charge AS "profit"
    FROM items i
    LEFT JOIN items_transactions_details t ON i.item_id = t.item_id
    LEFT JOIN items_transactions tt ON t.transaction_id = tt.transaction_id
    LEFT JOIN view_items_transactions_prices p ON p.transaction_id = tt.transaction_id AND p.item_id = i.item_id
    WHERE tt.type='SALE' AND i.category='service' AND i.general_name='Prepaid Load Service'
    ORDER by t.transaction_id DESC
);

DROP FUNCTION IF EXISTS get_next_transaction_id;
DELIMITER $$
CREATE FUNCTION get_next_transaction_id() RETURNS INT UNSIGNED
function_get_next_transaction_id:
BEGIN
    DECLARE id,next_id INT UNSIGNED DEFAULT 0;
    SET id = (SELECT MAX(transaction_id) FROM items_transactions);
    IF (id <> (SELECT MAX(transaction_id) FROM items_transactions_details)) THEN
        RETURN 0;
    END IF;
    RETURN id + 1;
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS get_total_prepaid_load_revenue;
DELIMITER $$
CREATE FUNCTION get_total_prepaid_load_revenue(dateStart TIMESTAMP, dateEnd TIMESTAMP) RETURNS DECIMAL(13,2) UNSIGNED
function_get_total_prepaid_load_revenue:
BEGIN
    return (SELECT SUM(`revenue`) FROM `view_transactions_prepaid_load` WHERE `date`>=dateStart AND `date`<=dateEnd);
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS get_total_prepaid_load_profit;
DELIMITER $$
CREATE FUNCTION get_total_prepaid_load_profit(dateStart TIMESTAMP, dateEnd TIMESTAMP) RETURNS DECIMAL(13,2) UNSIGNED
function_get_total_prepaid_load_profit:
BEGIN
    DECLARE costs, revenue DECIMAL(13,2) UNSIGNED DEFAULT 0;
    SET costs = (SELECT SUM(`cost`) FROM `view_transactions_prepaid_load` WHERE `date`>=dateStart AND `date`<=dateEnd);
    SET revenue = get_total_prepaid_load_revenue(dateStart, dateEnd);
	return revenue - costs;
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS get_total_products_revenue;
DELIMITER $$
CREATE FUNCTION get_total_products_revenue(dateStart TIMESTAMP, dateEnd TIMESTAMP) RETURNS DECIMAL(13,2) UNSIGNED
function_get_total_products_revenue:
BEGIN
    return (SELECT SUM(`revenue`) FROM `view_transactions_products` WHERE `date`>=dateStart AND `date`<=dateEnd);
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS get_total_products_profit;
DELIMITER $$
CREATE FUNCTION get_total_products_profit(dateStart TIMESTAMP, dateEnd TIMESTAMP) RETURNS DECIMAL(13,2) UNSIGNED
function_get_total_products_profit:
BEGIN
    return (SELECT SUM(`profit`) FROM `view_transactions_products` WHERE `date`>=dateStart AND `date`<=dateEnd);
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS get_total_services_revenue;
DELIMITER $$
CREATE FUNCTION get_total_services_revenue(dateStart TIMESTAMP, dateEnd TIMESTAMP) RETURNS DECIMAL(13,2) UNSIGNED
function_get_total_services_revenue:
BEGIN
    return (SELECT SUM(`revenue`) FROM `view_transactions_services` WHERE `date`>=dateStart AND `date`<=dateEnd);
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS insert_items_transaction;
DELIMITER $$
CREATE PROCEDURE insert_items_transaction(IN data JSON, OUT success BOOLEAN)
proc_insert_items_transaction:
BEGIN
    DECLARE id INT UNSIGNED DEFAULT 0;
    DECLARE customer VARCHAR(30) DEFAULT NULL;
	DECLARE type VARCHAR(10);
    DECLARE timestamp TIMESTAMP;
	DECLARE transaction_id INT UNSIGNED DEfAULT json_unquote(json_extract(data, '$.transaction_id'));
    DECLARE grand_total DECIMAL(13,2) DEFAULT json_unquote(json_extract(data, '$.grand_total'));
    DECLARE sub_total DECIMAL(13,2) DEFAULT json_unquote(json_extract(data, '$.sub_total'));
    DECLARE service_charge DECIMAL(13,2) DEFAULT json_unquote(json_extract(data, '$.service_charge'));
    DECLARE discount DECIMAL(13,2) DEFAULT json_unquote(json_extract(data, '$.discount'));
    DECLARE payment DECIMAL(13,2) DEFAULT json_unquote(json_extract(data, '$.payment'));

    SET success = FALSE;
    SET @items = json_unquote(json_extract(data, '$.items'));
    SET id = get_next_transaction_id();

    IF id = 0 OR id <> transaction_id THEN
        LEAVE proc_insert_items_transaction;
    END IF;

    SET customer = json_unquote(json_extract(data, '$.customer'));
    SET timestamp = json_unquote(json_extract(data, '$.timestamp'));
    SET type = json_unquote(json_extract(data, '$.type'));

    INSERT INTO `items_transactions`(`transaction_id`, `customer`, `type`, `date`, `sub_total`, `service_charge`, `grand_total`, `discount`, `payment`)
    VALUES (`transaction_id`, `customer`, `type`, `timestamp`, `sub_total`, `service_charge`, `grand_total`, `discount`, `payment`);
    CALL insert_items_transaction_details(`transaction_id`, `type`, @items, @success2);
    SET success = @success2;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS insert_items_transaction_details;
DELIMITER $$
CREATE PROCEDURE insert_items_transaction_details(IN transaction_id INT UNSIGNED, IN type VARCHAR(10), IN data JSON, OUT success BOOLEAN)
proc_insert_items_transaction_details:
BEGIN
    DECLARE json_len TINYINT UNSIGNED DEFAULT JSON_LENGTH(data);
    DECLARE i TINYINT UNSIGNED DEFAULT 0;
    DECLARE _item_id INT UNSIGNED DEFAULT 0;
    DECLARE _amount INT UNSIGNED DEFAULT 0;
    DECLARE delta INT DEFAULT 0;

    WHILE `i` < `json_len` DO
        SET `_item_id` = JSON_EXTRACT(data, CONCAT('$[', `i`, '].itemId'));
        SET `_amount` = JSON_EXTRACT(data, CONCAT('$[', `i`, '].amount'));
        SET `delta` = `_amount`;
        INSERT INTO `items_transactions_details` (`transaction_id`, `item_id`, `amount`)
        VALUES (`transaction_id`, `_item_id`, `_amount`);
        IF `type` IN('SALE','LOSS') THEN
            SET `delta` = -`_amount`;
        END IF;
        CALL update_item_stock(`_item_id`, `delta`, @success2);
        SET `i` = `i` + 1;
    END WHILE;
    SET success = TRUE;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS update_item_stock;
DELIMITER $$
CREATE PROCEDURE update_item_stock(IN itemId INT UNSIGNED, IN delta INT, OUT success BOOLEAN)
proc_update_item_stock:
BEGIN
    DECLARE amount INT UNSIGNED DEFAULT 0;
    IF EXISTS (SELECT `stock` FROM `items_stock` WHERE `item_id`=`itemId`) THEN
    BEGIN
        SELECT t.`stock` INTO @amount FROM `items_stock` t WHERE t.`item_id`=`itemId`;
        IF @amount = 0 AND `delta` < 0 THEN
            LEAVE proc_update_item_stock;
        END IF;
        IF -`delta` > @amount THEN
            SET @amount = 0;
        ELSE
            SET @amount = @amount + `delta`;
        END IF;
        UPDATE `items_stock` t SET t.`stock`=@amount WHERE t.item_id=`itemId`;
    END;
    ELSE
    BEGIN
        IF `delta` > 0 THEN
            INSERT INTO `items_stock`(`item_id`, `stock`) VALUES (`itemId`,`delta`);
        END IF;
    END;
    END IF;
    SET success = TRUE;
END $$
DELIMITER ;

-- CALL insert_items_transaction('{"transaction_id":"6","customer":"Harry Kunz","type":"SALE","items":[{"itemId":"221","amount":2},{"itemId":"181","amount":3},{"itemId":"32","amount":10}],"timestamp":"2019-11-23 20:46:52","sub_total":93,"discount":0,"cash":100,"grand_total":93,"payment":100}', @success);
-- CALL insert_items_transaction('{"transaction_id":"6","customer":"","type":"SALE","items":[{"itemId":"221","amount":1}],"timestamp":"2019-11-24 15:27:40","sub_total":2.5,"service_charge":1,"discount":0,"payment":5,"grand_total":2.5}', @success);

DROP PROCEDURE IF EXISTS create_new_item;
DELIMITER $$
CREATE PROCEDURE create_new_item(IN data JSON, OUT iid INT UNSIGNED, OUT success BOOLEAN)
proc_create_new_item:
BEGIN
	DECLARE bar_code VARCHAR(13) DEFAULT json_unquote(json_extract(data, '$.bar_code'));
	DECLARE unit VARCHAR(3) DEFAULT json_unquote(json_extract(data, '$.unit'));
	DECLARE count SMALLINT UNSIGNED DEFAULT json_unquote(json_extract(data, '$.count'));
	DECLARE item_description VARCHAR(100) DEFAULT json_unquote(json_extract(data, '$.item_description'));
	DECLARE general_name VARCHAR(30) DEFAULT json_unquote(json_extract(data, '$.general_name'));
	DECLARE brand_name VARCHAR(25) DEFAULT json_unquote(json_extract(data, '$.brand_name'));
	DECLARE category VARCHAR(30) DEFAULT json_unquote(json_extract(data, '$.category'));
    DECLARE supplier_name VARCHAR(20) DEFAULT json_unquote(json_extract(data, '$.supplier_name'));
	DECLARE stock INT UNSIGNED DEFAULT json_unquote(json_extract(data, '$.stock'));
	DECLARE unit_price DECIMAL(13,2) DEFAULT json_unquote(json_extract(data, '$.unit_price'));
	DECLARE sell_price DECIMAL(13,2) DEFAULT json_unquote(json_extract(data, '$.sell_price'));
	INSERT INTO items (`bar_code`,`unit`,`count`,`item_description`,`general_name`,`brand_name`,`category`,`supplier_name`)
	VALUES (`bar_code`,`unit`,`count`,`item_description`,`general_name`,`brand_name`,`category`,`supplier_name`);
	SET iid = (SELECT MAX(i.`item_id`) FROM `items` i);
	INSERT INTO `items_stock` (`item_id`,`stock`) VALUES (`iid`,`stock`);
	INSERT INTO `items_prices` (`item_id`,`unit_price`,`sell_price`) VALUES (`iid`,`unit_price`,`sell_price`);
	SET success = true;
END $$
DELIMITER ;

-- CALL call create_new_item('{"category":"Personal Accessory","supplier_name":"Hypermart","unit":"jar","item_description":"My item description here it is","bar_code":"","general_name":"My Gen Name","brand_name":"My Bran Name","count":2,"stock":33,"unit_price":4,"sell_price":6.7}', @a, @b);
