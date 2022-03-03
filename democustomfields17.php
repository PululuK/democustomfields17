<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderFinder;
use PrestaShop\Module\Democustomfields17\Form\Product\Democustomfields17AdminForm;
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

    public function __call($hookName, $params) {
        $hookFieldsBuilder = (new HookFieldsBuilderFinder())->find($hookName);

        if (null != $hookFieldsBuilder){
            return $this->displayProductAdminHookFields($hookFieldsBuilder, $params);
        }
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

        return $formFactory->createNamed($this->name, Democustomfields17AdminForm::class, $datas, $options);
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
