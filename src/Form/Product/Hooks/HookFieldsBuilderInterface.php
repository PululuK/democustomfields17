<?php
    
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use Symfony\Component\Form\FormBuilderInterface;
    use Module;
    
    interface HookFieldsBuilderInterface
    {
        public function addFields(FormBuilderInterface $adminFormBuilder, Module $module) : FormBuilderInterface;
    }
