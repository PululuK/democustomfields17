<?php
        
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use PrestaShopBundle\Form\Admin\Type\TranslatableType;
    use PrestaShopBundle\Form\Admin\Type\SwitchType;
    use Module;

    class HookDisplayAdminProductsExtraFieldsBuilder implements HookFieldsBuilderInterface
    {
        public function addFields(FormBuilderInterface $adminFormBuilder, Module $module) :FormBuilderInterface
        {
            $adminFormBuilder
                ->add('my_text_field_example', TextType::class, [
                    'label' => $module->l('My text'),
                    'attr' => [
                        'class' => 'my-custom-class',
                        'data-hex'=> 'true'
                    ]
                ])
                ->add('my_switch_field_example', SwitchType::class, [
                    'label' => $module->l('My switch')
                ])
                ->add('my_translatable_text_field_example', TranslatableType::class, [
                    'label' => $module->l('My translatable text'),
                    'type' => TextareaType::class,
                    'locales' => $module->getLocales()
                ]);

            return $adminFormBuilder;
        }
    }
