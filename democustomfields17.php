<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFormInterface;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsMainStepLeftColumnMiddleForm;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsExtraForm;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsMainStepLeftColumnBottom;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsMainStepRightColumnBottom;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsQuantitiesStepBottom;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsPriceStepBottom;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsOptionsStepTop;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsOptionsStepBottom;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\DisplayAdminProductsSeoStepBottom;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

class Democustomfields17 extends Module
{
    private $symfonyInstance = null;
        
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
    
    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() && $this->registerHook($this->getHooks());
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }
    
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsMainStepLeftColumnMiddleForm()), $params);
    }
    
    public function hookDisplayAdminProductsExtra($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsExtraForm()), $params);
    }
    
    public function hookDisplayAdminProductsMainStepLeftColumnBottom($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsMainStepLeftColumnBottom()), $params);
    }
    
    public function hookDisplayAdminProductsMainStepRightColumnBottom($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsMainStepRightColumnBottom()), $params);
    }
    
    public function hookDisplayAdminProductsQuantitiesStepBottom($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsQuantitiesStepBottom()), $params);
    }
    
    public function hookDisplayAdminProductsPriceStepBottom($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsPriceStepBottom()), $params);
    }
    
    public function hookDisplayAdminProductsOptionsStepBottom($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsOptionsStepBottom()), $params);
    }
    
    public function hookDisplayAdminProductsOptionsStepTop($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsOptionsStepTop()), $params);
    }
    
    public function hookDisplayAdminProductsSeoStepBottom($params)
    {
        return $this->getProductAdminHookContent((new DisplayAdminProductsSeoStepBottom()), $params);
    }
    
    public function hookActionAdminProductsControllerSaveAfter($params)
    {
        $data = Tools::getValue($this->name);
        $idProduct = (int) Tools::getValue('id_product');
                        
        if (!is_array($data)
        || !isset($data[$this->getModuleFormDatasID()])) { // Make sure datas come form this form
            return;
        }
        
        $productObject = new Product($idProduct);

        if (!Validate::isLoadedObject($productObject)) {
            return;
        }
        
        die(var_dump($data));
        
        // TODO : play with $data and $productObject ;)
    }
    
    public function symfonyContainerInstance()
    {
        if (null != $this->symfonyInstance) {
            return $this->symfonyInstance;
        }
        
        $this->symfonyInstance = SymfonyContainer::getInstance();
        
        return $this->symfonyInstance;
    }
    
    private function getProductAdminHookForm(HookFormInterface $hookForm, array $datas)
    {
        $formFactory = $this->symfonyContainerInstance()->get('form.factory');
        $options = [
            'csrf_protection' => false,
            'hookFormBuilder' => $hookForm,
            'module' => $this,
        ];

        $form = $formFactory->createNamed(
            $this->name,
            'PrestaShop\Module\Democustomfields17\Form\Product\Democustomfields17AdminForm',
            $datas,
            $options
        );

        return $form;
    }
    
    private function getProductAdminHookContent(HookFormInterface $hookForm, array $params)
    {
        $productFieldsDatas = [];
        $idProduct = $params['id_product'];
        
        //Todo : load product datas :)

        $form = $this->getProductAdminHookForm($hookForm, $productFieldsDatas);
        
        return $this->symfonyContainerInstance()
            ->get('twig')
            ->render('@PrestaShop/'.$this->name.'/admin/product/customfields.html.twig', [
                'form' => $form->createView(),
            ]);
    }
    
    public function getHooks()
    {
        // @see https://devdocs.prestashop.com/1.7/modules/concepts/hooks/list-of-hooks/#full-list
        return [
            'displayAdminProductsExtra',
            'displayAdminProductsMainStepLeftColumnMiddle',
            'displayAdminProductsMainStepLeftColumnBottom',
            'displayAdminProductsMainStepRightColumnBottom',
            'displayAdminProductsQuantitiesStepBottom',
            'displayAdminProductsPriceStepBottom',
            'displayAdminProductsOptionsStepTop',
            'displayAdminProductsOptionsStepBottom',
            'displayAdminProductsSeoStepBottom',
            'actionAdminProductsControllerSaveAfter',
            'actionObjectProductDeleteAfter',
        ];
    }
    
    private function getLocales()
    {
        $sfContainer = $this->symfonyContainerInstance();
        return $sfContainer->get('prestashop.adapter.data_provider.language')->getLanguages();
    }
    
    public function getModuleFormDatasID()
    {
        return 'fields_from_'.$this->name.'_'.$this->id ;
    }
}
