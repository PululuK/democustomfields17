<?php
    
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use Symfony\Component\Form\FormBuilderInterface;
    use Module;
    
    interface HookFormInterface
    {
        public function addCustomFields(FormBuilderInterface $builder, Module $module) : FormBuilderInterface;
    }
