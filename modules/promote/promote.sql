CREATE TABLE IF NOT EXISTS `sc_shop_promote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `discount` float NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sc_shop_promote_code`
--

CREATE TABLE IF NOT EXISTS `sc_shop_promote_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(16) NOT NULL,
  `used` timestamp NULL DEFAULT NULL,
  `promote_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `promote_id` (`promote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=201 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sc_shop_promote_rel`
--

CREATE TABLE IF NOT EXISTS `sc_shop_promote_rel` (
  `promote_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  KEY `promote_id` (`promote_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;