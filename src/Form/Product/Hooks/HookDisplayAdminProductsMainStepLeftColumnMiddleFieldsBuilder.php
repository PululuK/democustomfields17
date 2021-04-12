<?php
        
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Module;

    class HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder implements HookFieldsBuilderInterface
    {
        public function addFields(FormBuilderInterface $adminFormBuilder, Module $module) :FormBuilderInterface
        {
            $adminFormBuilder
                ->add('my_field_name', TextType::class, array(
                        'label' => $module->l('HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder'),
                        'attr' => array(
                            'class' => 'my-custom-class',
                            'data-hex'=> 'true'
                        )
                ));
                    
            return $adminFormBuilder;
        }
    }
