DROP TABLE IF EXISTS items_transactions_details;
DROP TABLE IF EXISTS items_transactions;
DROP TABLE IF EXISTS items_stock;
DROP TABLE IF EXISTS items;

CREATE TABLE items (
	item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	bar_code VARCHAR(13) NOT NULL,
	unit ENUM('bar','bot','bx','crd','cut','dz','jar','ld','pad','pc','pd','pg','pk','rm','sbx','set','tie') NOT NULL,
	count SMALLINT UNSIGNED NOT NULL,
	item_description VARCHAR(100) NOT NULL,
	general_name VARCHAR(30) NOT NULL,
	brand_name VARCHAR(25),
	category ENUM('Electronics','Food Additive','Galenical','Hardware','Household','Personal Accessory','Personal Hygiene','Pharmaceutical','School & Office','Service','Toiletry') NOT NULL,
	unit_price DECIMAL(13,2) NOT NULL,
	sell_price DECIMAL(13,2) NOT NULL,
	supplier_name ENUM('Chuyte','Klebbys','Conchitas','Hypermart','Other') NOT NULL
);

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
        IF `type`='SALE' THEN
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
