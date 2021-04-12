<?php
    
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use Symfony\Component\Form\HookFieldsBuilderInterface;
    use Module;
    
    interface HookFieldsBuilderInterface
    {
        public function addCustomFields(FormBuilderInterface $builder, Module $module) : FormBuilderInterface;
    }
