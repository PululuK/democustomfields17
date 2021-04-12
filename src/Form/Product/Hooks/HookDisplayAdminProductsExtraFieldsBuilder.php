<?php
        
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use PrestaShopBundle\Form\Admin\Type\CategoryChoiceTreeType;
    use PrestaShopBundle\Form\Admin\Type\ChangePasswordType;
    use PrestaShopBundle\Form\Admin\Type\CountryChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use PrestaShopBundle\Form\Admin\Type\TranslatableType;
    use PrestaShopBundle\Form\Admin\Type\SwitchType;
    use PrestaShopBundle\Form\Admin\Type\TextWithLengthCounterType;
    use Module;

    class HookDisplayAdminProductsExtraFieldsBuilder implements HookFieldsBuilderInterface
    {
        public function addFields(FormBuilderInterface $adminFormBuilder, Module $module) :FormBuilderInterface
        {
            $adminFormBuilder
                ->add('my_text_type_field_exemple', TextType::class, [
                        'label' => $module->l('My simple text type'),
                        'attr' => array(
                            'class' => 'my-custom-class',
                            'data-hex'=> 'true'
                        )
                ])
                ->add('my_switch_type_field_exemple', SwitchType::class, [
                    'label' => $module->l('My switch type'),
                    'choices' => [
                        $module->l('ON') => true,
                        $module->l('OFF') => false,
                    ],
                ])
                ->add('my_translatable_type_field_exemple', TranslatableType::class, [
                    // we'll have text area that is translatable
                    'label' => $module->l('My translatable type'),
                    'type' => TextareaType::class,
                    'locales' => $module->getLocales()
                ])
                ->add('meta_title', TextWithLengthCounterType::class, [
                    'label' => $module->l('My text with length counter type'),
                    'max_length' => 255,
                ])
                ->add('category_id', CategoryChoiceTreeType::class, [
                    'label' => $module->l('My categorytree type'),
                    'disabled_values' => [4, 5], // Recommantion : Use something look $module->getDisabledCategoriesIds()
                ]);

            return $adminFormBuilder;
        }
    }
