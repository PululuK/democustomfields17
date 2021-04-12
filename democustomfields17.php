<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsExtraFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsMainStepLeftColumnBottomFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsMainStepRightColumnBottomFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsQuantitiesStepBottomFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsPriceStepBottomFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsOptionsStepTopFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsOptionsStepBottomFieldsBuilder;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookDisplayAdminProductsSeoStepBottomFieldsBuilder;
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
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsExtra($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsExtraFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsMainStepLeftColumnBottom($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsMainStepLeftColumnBottomFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsMainStepRightColumnBottom($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsMainStepRightColumnBottomFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsQuantitiesStepBottom($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsQuantitiesStepBottomFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsPriceStepBottom($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsPriceStepBottomFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsOptionsStepBottom($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsOptionsStepBottomFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsOptionsStepTop($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsOptionsStepTopFieldsBuilder()), $params);
    }
    
    public function hookDisplayAdminProductsSeoStepBottom($params)
    {
        return $this->displayProductAdminHookFields((new HookDisplayAdminProductsSeoStepBottomFieldsBuilder()), $params);
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
    
    private function getProductAdminHookFieldsDefinition(HookFieldsBuilderInterface $hookFieldsBuilder, array $datas)
    {
        $formFactory = $this->symfonyContainerInstance()->get('form.factory');
        $options = [
            'csrf_protection' => false,
            'hookFieldsBuilder' => $hookFieldsBuilder,
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
    
    private function displayProductAdminHookFields(HookFieldsBuilderInterface $hookFieldsBuilder, array $params)
    {
        $productFieldsDatas = [];
        $idProduct = $params['id_product'];

        /* Todo : You have $idProduct ... load product datas :)
         *
         *  1 - My data system get product custom fields values by $idProduct
         *  2 - $productFieldsDatas['My_product_customfield_name'] = 'My_product_custom_field_value';
         *
         * */

        $form = $this->getProductAdminHookFieldsDefinition($hookFieldsBuilder, $productFieldsDatas);
        
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
    
    public function getLocales()
    {
        $sfContainer = $this->symfonyContainerInstance();
        return $sfContainer->get('prestashop.adapter.data_provider.language')->getLanguages();
    }
    
    public function getModuleFormDatasID()
    {
        return 'fields_from_'.$this->name.'_'.$this->id ;
    }
}
