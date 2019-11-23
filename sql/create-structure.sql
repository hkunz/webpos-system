DROP TABLE IF EXISTS items_transactions_details;
DROP TABLE IF EXISTS items_transactions;
DROP TABLE IF EXISTS items_stock;
DROP TABLE IF EXISTS items;

CREATE TABLE items (
	item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	bar_code VARCHAR(13) NOT NULL,
	unit ENUM('bar','bot','bx','crd','cut','dz','jar','pad','pc','pd','pg','pk','rm','sbx','set','tie') NOT NULL,
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
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE items_transactions_details (
	transaction_id INT UNSIGNED NOT NULL,
	item_id INT UNSIGNED NOT NULL,
	amount INT UNSIGNED NOT NULL,
	FOREIGN KEY (item_id) REFERENCES items(item_id),
	FOREIGN KEY (transaction_id) REFERENCES items_transactions(transaction_id),
	PRIMARY KEY (transaction_id, item_id)
);

DROP PROCEDURE IF EXISTS insert_items_transaction;
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

CREATE PROCEDURE insert_items_transaction(IN data JSON, IN date TIMESTAMP, OUT success BOOLEAN)
proc_insert_items_transaction:
BEGIN
	DECLARE id INT UNSIGNED DEFAULT 0;
    DECLARE customer VARCHAR(30) DEFAULT NULL;
    SET id = get_next_transaction_id();
    IF id = 0 THEN
        SET success = FALSE;
        LEAVE proc_insert_items_transaction;
    END IF;
    SET customer = json_unquote(json_extract(data, '$.customer'));
    SELECT customer;
    SET success = TRUE;
END $$
DELIMITER ;

-- call insert_items_transaction(CONCAT('{"transaction_id":"',5,'","customer":"chuyte","type","SELL","items":[{},{}]}'), CURRENT_TIMESTAMP, @success);

