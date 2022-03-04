<?php

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'democustomfields17`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'democustomfields17_lang`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
