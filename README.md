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

2 - Go to https://github.com/PululuK/democustomfields17/blob/main/democustomfields17.php and see the hook form called in the hook method, in this case is `DisplayAdminProductsMainStepLeftColumnMiddleForm`

```php
public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
{
    return $this->getProductAdminHookContent((new DisplayAdminProductsMainStepLeftColumnMiddleForm()), $params);
}
```
 
3 - Go to https://github.com/PululuK/democustomfields17/tree/main/src/Form/Product/Hooks and implement this hook form.
See Types references here : https://devdocs.prestashop.com/1.7/development/components/form/types-reference/
```php
<?php
        
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFormInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use PrestaShopBundle\Form\Admin\Type\TranslatableType;
    use PrestaShopBundle\Form\Admin\Type\SwitchType;
    use Module;

    class DisplayAdminProductsMainStepLeftColumnMiddleForm implements HookFormInterface
    {
        public function addCustomFields(FormBuilderInterface $builder, Module $module) :FormBuilderInterface
        {
            $builder
                ->add('displayadminproductsmainstepLeftcolumnmiddleform', TextType::class, [
                     'label' => $module->l('DisplayAdminProductsMainStepLeftColumnMiddleForm'),
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
                
                    
            return $builder;
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
