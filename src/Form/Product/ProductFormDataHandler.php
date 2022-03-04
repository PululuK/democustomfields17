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
