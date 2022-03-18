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

Imagine you want to add a new field in backoffice product page in extra tab.

- 1 : Identify the hook. In our case will be
 `DisplayAdminProductsExtra` ([See hooks list](https://devdocs.prestashop.com/1.7/modules/concepts/hooks/list-of-hooks/#full-list))
 
- 2 : Go to https://github.com/PululuK/democustomfields17/tree/main/src/Form/Product/Hooks and implement this hook form.

**NOTE** : The hook fields builder must implement `HookFieldsBuilderInterface` and respect this name rule `Hook<HookName>FieldsBuilder` 

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
```

![image](https://user-images.githubusercontent.com/16455155/156677951-1baafe10-ab98-4aa0-b4ba-a40c842a091b.png)

- 3 : Process data after product form submit

See  `src/Form/Product/ProductFormDataHandler.php` 

NOTE : Make sure your fields exists in [ProductCustomFields Model](https://github.com/PululuK/democustomfields17/blob/main/src/Form/Product/ProductFormDataHandler.php) and you [database table schema](https://github.com/PululuK/democustomfields17/blob/main/sql/install.php)

```php

<?php

namespace PrestaShop\Module\Democustomfields17\Form\Product;

use PrestaShop\Module\Democustomfields17\Form\FormDataHandlerInterface;
use PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException;
use PrestaShop\Module\Democustomfields17\Factory\ProductCustomFieldsFactory;
use Exception;

final class ProductFormDataHandler implements FormDataHandlerInterface
{
    public function save(array $data): bool{

        $idProduct = (int) $data['id_product'];
        $productCustomFields = ProductCustomFieldsFactory::create($idProduct);
        $productCustomFields->id_product = $idProduct;
        $productCustomFields->my_switch_field_example = (bool) $data['my_switch_field_example'];
        $productCustomFields->my_translatable_text_field_example = $data['my_translatable_text_field_example'];
        $productCustomFields->my_text_field_example = $data['my_text_field_example'];

        try {
            if($productCustomFields->save()){
                return true;
            }
        } catch(Exception $e){
            throw new ModuleErrorException($e->getMessage());
        }

        return true;
    }

    public function getData(array $params): array{
        $productCustomFields = ProductCustomFieldsFactory::create(
            (int)$params['id_product'],
            $params['id_lang'] ?? null,
            $params['id_shop'] ?? null
        );

        return [
            'id' => $productCustomFields->id,
            'id_product' => $productCustomFields->id_product,
            'my_switch_field_example' => (bool) $productCustomFields->my_switch_field_example,
            'my_text_field_example' => $productCustomFields->my_text_field_example,
            'my_translatable_text_field_example' => $productCustomFields->my_translatable_text_field_example,
        ];
    }
}

```

- 4 : Acces your data in Front

```{$product.democustomfields17.YOUR_FIELD_NAME_HERE}```

Ex.: 

```smarty
{$product.democustomfields17.my_text_field_example}
{$product.democustomfields17.my_translatable_text_field_example }
{$product.democustomfields17.my_switch_field_example}
```




