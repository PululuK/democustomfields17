<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class Democustomfields17 extends Module
{
    public function __construct()
    {
        $this->name = 'democustomfields17';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'PululuK';
        $this->need_instance = 1;

        parent::__construct();

        $this->displayName = $this->l('Demo products custom fields prestashop 1.7');
        $this->description = $this->l('Demo products custom fields prestashop 1.7');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall my module?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }
}
