<?php

namespace PrestaShop\Module\Democustomfields17\Factory;

use PrestaShop\Module\Democustomfields17\Model\ProductCustomFields;

class ProductCustomFieldsFactory{

    public static function create(
        int $idProduct,
        ?int $idLang = null,
        ?int $idShop = null
    ): ProductCustomFields{
        return ProductCustomFields::getInstanceByProductId(
            $idProduct,
            $idLang,
            $idShop
        );
    }
}
