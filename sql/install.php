<?php

$sql = [];

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'democustomfields17` (
    `id_democustomfields17` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) unsigned NOT NULL,
    `my_switch_field_example` tinyint(1) unsigned NOT NULL DEFAULT "0",
    `my_text_field_example` text DEFAULT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY  (`id_democustomfields17`),
    KEY `id_product` (`id_product`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'democustomfields17_lang` (
    `id_democustomfields17` int(11) unsigned NOT NULL,
    `id_shop` INT(11) UNSIGNED NOT NULL DEFAULT "1",
    `id_lang` int(11) unsigned NOT NULL ,
    `my_translatable_text_field_example` text DEFAULT NULL,
    PRIMARY KEY  (`id_democustomfields17`, `id_shop`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
