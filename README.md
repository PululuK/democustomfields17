# democustomfields17

This module is an architectural skeleton for module developers, who want to add custom fields in the product page on the backoffice.

It's a base, you have to adapt it to your needs ... This will save you a lot of time!


## Current produt hooks

- displayAdminProductsExtra
- displayAdminProductsMainStepLeftColumnMiddle
- displayAdminProductsMainStepLeftColumnBottom
- displayAdminProductsMainStepRightColumnBottom
- displayAdminProductsQuantitiesStepBottom
- displayAdminProductsPriceStepBottom
- displayAdminProductsOptionsStepTop
- displayAdminProductsOptionsStepBottom
- displayAdminProductsSeoStepBottom

## Requirements

- Prestashop >= 1.7.x
- composer >= 2.0.8
- See https://devdocs.prestashop.com/1.7/basics/installation/system-requirements/#php-compatibility-chart

## Install

- `cd` your_shop_root_dir/modules
- `git` clone https://github.com/PululuK/democustomfields17.git
- `cd` democustomfields17
- `composer` install
- Go to BO > Improvement > Modules catalogue and install


## How to use ?

Imagine you want to add a new field in backoffice product page in left column.

1 - Identify the hook. In our case will be
 `DisplayAdminProductsMainStepLeftColumnMiddle` ([See hooks list](https://devdocs.prestashop.com/1.7/modules/concepts/hooks/list-of-hooks/#full-list))

2 - Go to https://github.com/PululuK/democustomfields17/blob/main/democustomfields17.php and see the hook form called in the hook method, in this case is `HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder`

```php
public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
{
    return $this->displayProductAdminHookFields((new HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder()), $params);
}
```
 
3 - Go to https://github.com/PululuK/democustomfields17/tree/main/src/Form/Product/Hooks and implement this hook form.
See Types references here : https://devdocs.prestashop.com/1.7/development/components/form/types-reference/
```php
<?php
        
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use PrestaShopBundle\Form\Admin\Type\TranslatableType;
    use PrestaShopBundle\Form\Admin\Type\SwitchType;
    use Module;

    class HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder implements HookFieldsBuilderInterface
    {
        public function addFields(FormBuilderInterface $adminFormBuilder, Module $module) :FormBuilderInterface
        {
            $adminFormBuilder
                ->add('displayadminproductsmainstepLeftcolumnmiddleform', TextType::class, [
                     'label' => $module->l('HookDisplayAdminProductsMainStepLeftColumnMiddleFieldsBuilder'),
                     'attr' => [
                        'class' => 'my-custom-class',
                        'data-hex'=> 'true'
                      ]
                ])
                ->add('description', TranslatableType::class, [
                    // we'll have text area that is translatable
                   'type' => TextareaType::class,
                ])
                ->add('switch', SwitchType::class, [
                    // Customized choices with ON/OFF instead of Yes/No
                   'choices' => [
                     'ON' => true,
                     'OFF' => false,
                   ],
               ]);
                
                    
            return $adminFormBuilder;
        }
    }
```

![image](https://user-images.githubusercontent.com/16455155/113463753-077a7280-9428-11eb-856f-dc5e93002da7.png)


4 - Process data after product form submit

See module `hookActionAdminProductsControllerSaveAfter` method called after performing save/update in product controller. 

```php
public function hookActionAdminProductsControllerSaveAfter($params)
{
        $data = Tools::getValue($this->name);
        $idProduct = (int) Tools::getValue('id_product');
                        
        if (!is_array($data)
        || !isset($data[$this->getModuleDatasCommeFromID()])) { // Make sure datas come form this form
            return;
        }
        
        $productObject = new Product($idProduct);

        if (!Validate::isLoadedObject($productObject)) {
            return;
        }
        
        die(var_dump($data));
        
        // TODO : play with $data and $productObject ;)
  }
```

`$productObject` current product and  `$data` is array contains your  custom fields datas : 

```php
array(10) { 
  ["displayadminproductsmainstepLeftcolumnmiddleform"]=> string(10) "value test1" 
  ["fields_from_democustomfields17_81"]=> string(1) "1" 
  ["displayadminproductsmainstepleftcolumnbottom"]=> string(0) "" 
  ["displayadminproductsmainsteprightcolumnbottom"]=> string(0) "" 
  ["displayadminproductsquantitiesstepbottom"]=> string(0) "" 
  ["displayadminproductspricestepbottom"]=> string(0) "" 
  ["displayadminproductsseostepbottom"]=> string(0) "" 
  ["displayadminproductsoptionssteptop"]=> string(0) "" 
  ["displayadminproductsoptionsstepbottom"]=> string(0) "" 
  ["displayadminproductsextra"]=> string(0) "" 
 } 
```

So you can play with that :) ... Persist your data, update other systems, send notifications etc etc !


## Fields types exemples :)

For more informations see Types references here : https://devdocs.prestashop.com/1.7/development/components/form/types-reference/

> NOTE : Be sure that the type of fields you want to use exist in your version of prestashop.


### Code 

See https://github.com/PululuK/democustomfields17/blob/main/src/Form/Product/Hooks/DisplayAdminProductsExtraForm.php

```php
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

```

### Result

BO > Product 

![image](https://user-images.githubusercontent.com/16455155/113608216-8e0e9a00-964a-11eb-98f7-40da7de66953.png)


FO

```twig
{$product.democustomfields17.my_text_field_example}
{$product.democustomfields17.my_translatable_text_field_example }
{$product.democustomfields17.my_switch_field_example}
```




