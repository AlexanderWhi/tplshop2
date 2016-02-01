--Сброс базы--
TRUNCATE TABLE sc_loger ;

TRUNCATE TABLE sc_users ;
INSERT INTO `sc_users` (`u_id`, `login`, `password`, `status`, `key_unlock`, `type`, `mail`, `url`, `name`, `last_name`, `first_name`, `middle_name`, `contact_name`, `post`, `company`, `company_desc`, `birth_date`, `address`, `street`, `house`, `flat`, `porch`, `floor`, `town`, `phone`, `avat`, `reg_date`, `validity`, `visit`, `last_visit`, `balance`, `discount`, `bonus`, `refid`) VALUES
(1, 'supervisor', PASSWORD('supervisor'), 1, '6dc04465f0322487b17754392bc126cf', 'supervisor', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '/storage/avatar/436062adef4d5a8aa7a6c78255b0e7a8.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, '2011-09-06 17:20:07', 0, 0, 0, 0),
(2, 'robot', PASSWORD('robot'), 1, '', 'user', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '/storage/avatar/436062adef4d5a8aa7a6c78255b0e7a8.jpg', '2010-08-26 19:21:54', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0, 0, 0),
(3, 'admin', PASSWORD('admin'), 1, '', 'admin', 'admin@mail.ru', '', 'Администратор', '', '', '', '', '', '', '', '01/09/2009', '', '', '', '', '', '', '', '', '', '2009-09-08 16:34:39', '0000-00-00 00:00:00', 15, '2011-10-05 17:11:49', 0, 0, 0, 0),
(4, 'alexander', PASSWORD('123'), 1, '0e86724b20ab42f4d5d144373d3ca52d', 'user', 'alexanderwhite@mail.ru', 'co-in.ru', 'Александр Леонов', 'Леонов', 'Александр', 'Александрович', 'Леонов Александр', '', 'Моя любимая компания', '', '15.02.1984', 'Малышева, 111-46, подъезд , этаж ', 'Малышева', '111', '46', '', '', 'Екатеринбург', '9049831660', '', '2009-09-09 00:00:00', '0000-00-00 00:00:00', 7, '2011-10-05 17:22:45', 0, 8, 2, 0)


--Сброс магазина--
TRUNCATE TABLE sc_shop_item_tmp;
--заказы
TRUNCATE TABLE sc_shop_order ;
TRUNCATE TABLE sc_shop_order_item;

