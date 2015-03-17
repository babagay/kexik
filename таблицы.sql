SET foreign_key_checks = 0;

CREATE TABLE IF NOT EXISTS `categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
   `categories_name` varchar(32) NOT NULL DEFAULT '',
     `categories_description` text,
  `categories_image` varchar(64) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `categories_status` tinyint(4) NOT NULL DEFAULT '1',
  `categories_level` int(11) NOT NULL DEFAULT '0',  
  `categories_seo_page_name` varchar(128) NOT NULL,
  
  PRIMARY KEY (`categories_id`),
  KEY `idx_categories_parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;
  
   drop table `manufacturers`;
  CREATE TABLE IF NOT EXISTS `manufacturers` (
  `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturers_name` varchar(32) NOT NULL DEFAULT '',
  `manufacturers_image` varchar(64) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`),
  KEY `IDX_MANUFACTURERS_NAME` (`manufacturers_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=400 ;


INSERT INTO `manufacturers` (`manufacturers_id`, `manufacturers_name`, `manufacturers_image`, `date_added`, `last_modified`) VALUES
(400, 'noname', NULL, '2015-02-18 00:00:00', NULL);

drop table `products`;
CREATE TABLE IF NOT EXISTS `products` (  
  `products_id` int(11) NOT NULL  COMMENT 'Артикул',
  `products_barcode` varchar(13) NOT NULL COMMENT 'Штрихкод',
  `products_name` varchar(64) NOT NULL DEFAULT 'Наименование полное',
  `products_unit` varchar(20) NOT NULL DEFAULT '' COMMENT 'Ед.изм',
  `products_departament` varchar(120) NOT NULL DEFAULT '' COMMENT 'Отдел',
  `products_shopping_cart_price` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT 'Цена розн',
  `products_price_wholesale` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT 'Цена опт',
  `products_quantity` decimal(15,4) NOT NULL DEFAULT '0' COMMENT 'Остаток',   
  PRIMARY KEY (`products_id`),  
  UNIQUE KEY `idx_products_barcode` (`products_barcode`),
  FULLTEXT  KEY `idx_products_name` (`products_name`)
 
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=200 ;

 
 drop table `products_description`;
CREATE TABLE IF NOT EXISTS `products_description` (
  `products_id` int(11) NOT NULL  ,
  `products_description` text,
  `products_description_short` varchar(255) NOT NULL DEFAULT '',
  `products_image_large` varchar(64) DEFAULT NULL,
  `products_image_small` varchar(64) DEFAULT NULL,
   `products_last_modified` datetime DEFAULT NULL,    
	 `products_seo_page_name` varchar(64) NOT NULL DEFAULT '',
	   `sort_order` int(11) NOT NULL DEFAULT '0',   
	   `products_visibility` int(1) NOT NULL DEFAULT '0',   
	   `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_id`),
  KEY `idx_manufacturers_id` (`manufacturers_id`),  
  FULLTEXT KEY `idx_products_description` (`products_description`),
   CONSTRAINT `fk_manufacturers_id` FOREIGN KEY (manufacturers_id)
	REFERENCES manufacturers(manufacturers_id) ON DELETE RESTRICT ON UPDATE CASCADE
)  ENGINE=InnoDB  DEFAULT CHARSET=utf8   ;
 
 drop table `products_to_categories`;
CREATE TABLE IF NOT EXISTS `products_to_categories` (
  `products_id` int(11) NOT NULL DEFAULT '0',
  `categories_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_id`,`categories_id`),
  KEY `categories_id` (`categories_id`),   
	CONSTRAINT `fk_categories_id` FOREIGN KEY (categories_id)
	REFERENCES categories(categories_id) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `fk_products_id` FOREIGN KEY (products_id)
	REFERENCES products_description(products_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=300 ;

SET foreign_key_checks = 1;

// --- Вместо этого писать дату в лог `products_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
// --  products_id === products_id
// -- Тогда, после каждой загрузки нужно проверять, чтобы в связанных таблицах не было несцществующих products_id:
// -- products_description, products_to_categories, manufacturers_to_products,
// -- хотя в products_description можно и оставить. А , может, и в остальных тоже

При апдейте продуктов делать UPDATE записей в products, добавлять те, которых не было. 
Сохранять в лог айдишники (записанные и проапдейченные)
Взять записи, которые были раньше, но в новом продукт-листе их нет, и перевести в режим invisible.
Для этих записей выставить Остаток = 0 в т products.
Внешние ключи можно сохранить. Но они могут быть для т products_description, а не для products.
Штрихкод можно сохранять в products_description на случай, если продукт будет удален из products

После загрузки продуктов можно Взять все продукты, высосать их products_id и баркод и закинуть в products_description.
Это можно делать через редирект.
А потом можно вручную заполнять описание каждого продукта






/*
CREATE TABLE IF NOT EXISTS `manufacturers_to_products` (
  `manufacturers_id` int(11) NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`manufacturers_id`,`products_id`),
  KEY `products_id` (`products_id`)
  
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=500 ;
*/

// --------------------------
 CONSTRAINT `fk_manufacturers_id` FOREIGN KEY (manufacturers_id)
	REFERENCES manufacturers(manufacturers_id) ON DELETE CASCADE ON UPDATE CASCADE
	

 `products_model` varchar(25) DEFAULT NULL,
  `products_image` varchar(64) DEFAULT NULL,
  `products_image_med` varchar(64) DEFAULT NULL,
  `products_image_lrg` varchar(64) DEFAULT NULL,
  `products_image_sm_1` varchar(64) DEFAULT NULL,
  `products_image_xl_1` varchar(64) DEFAULT NULL,
  `products_image_sm_2` varchar(64) DEFAULT NULL,
  `products_image_xl_2` varchar(64) DEFAULT NULL,
  `products_image_sm_3` varchar(64) DEFAULT NULL,
  `products_image_xl_3` varchar(64) DEFAULT NULL,
  `products_image_sm_4` varchar(64) DEFAULT NULL,
  `products_image_xl_4` varchar(64) DEFAULT NULL,
  `products_image_sm_5` varchar(64) DEFAULT NULL,
  `products_image_xl_5` varchar(64) DEFAULT NULL,
  `products_image_sm_6` varchar(64) DEFAULT NULL,
  `products_image_xl_6` varchar(64) DEFAULT NULL,
  
 
  `products_date_available` datetime DEFAULT NULL,
  `products_weight` decimal(5,2) NOT NULL DEFAULT '0.00',

  `products_tax_class_id` int(11) NOT NULL DEFAULT '0',
  `manufacturers_id` int(11) DEFAULT NULL,
  `products_ordered` int(11) NOT NULL DEFAULT '0',
 
  `products_image_alt_1` varchar(64) NOT NULL DEFAULT '',
  `products_image_alt_2` varchar(64) NOT NULL DEFAULT '',
  `products_image_alt_3` varchar(64) NOT NULL DEFAULT '',
  `products_image_alt_4` varchar(64) NOT NULL DEFAULT '',
  `products_image_alt_5` varchar(64) NOT NULL DEFAULT '',
  `products_image_alt_6` varchar(64) NOT NULL DEFAULT '',
 
  `products_file` varchar(255) NOT NULL DEFAULT '',
  `products_price_discount` varchar(255) NOT NULL DEFAULT '',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `previous_status` tinyint(4) DEFAULT NULL,
  `last_xml_import` datetime DEFAULT NULL,
  `last_xml_export` datetime DEFAULT NULL,
  `products_sets_discount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `google_product_category` varchar(255) NOT NULL,
  `google_product_type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_id`),
  KEY `idx_products_date_added` (`products_date_added`),
  FULLTEXT KEY `idx_products_model` (`products_model`)

CREATE TABLE IF NOT EXISTS `products_description` (
  `products_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_name` varchar(64) NOT NULL DEFAULT '',
  `products_description` text,
  `products_url` varchar(255) DEFAULT NULL,
  `products_viewed` int(5) DEFAULT '0',
  `products_head_title_tag` varchar(80) DEFAULT NULL,
  `products_head_desc_tag` longtext NOT NULL,
  `products_head_keywords_tag` longtext NOT NULL,
  `products_description_short` varchar(255) NOT NULL DEFAULT '',
  `products_name_soundex` text,
  `products_description_soundex` text,
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`products_id`,`language_id`,`affiliate_id`),
  KEY `products_name` (`products_name`),
  FULLTEXT KEY `idx_products_name` (`products_name`),
  FULLTEXT KEY `idx_products_description` (`products_description`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=373 ;

SELECT * FROM products_description
        WHERE MATCH (products_name) AGAINST ('aeg');









