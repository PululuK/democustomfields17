<?php
        
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Module;

    class HookDisplayAdminProductsMainStepLeftColumnBottomFieldsBuilder implements HookFieldsBuilderInterface
    {
        public function addFields(FormBuilderInterface $adminFormBuilder, Module $module) :FormBuilderInterface
        {
            $adminFormBuilder
                ->add('displayadminproductsmainstepleftcolumnbottom', TextType::class, array(
                        'label' => $module->l('HookDisplayAdminProductsMainStepLeftColumnBottomFieldsBuilder'),
                        'attr' => array(
                            'class' => 'my-custom-class',
                            'data-hex'=> 'true'
                        )
                ));
                    
            return $adminFormBuilder;
        }
    }
