ALTER TABLE  `sc_shop_item` ADD  `sort_main` INT NOT NULL DEFAULT  '0' COMMENT  '���������� �� �������' AFTER  `update_time` ,
ADD INDEX (  `sort_main` )