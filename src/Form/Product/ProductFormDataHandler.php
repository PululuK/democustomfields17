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
        $productCustomFields = ProductCustomFieldsFactory::create((int)$params['id_product']);
        return [
            'id' => $productCustomFields->id,
            'id_product' => $productCustomFields->id_product,
        ];
    }
}
