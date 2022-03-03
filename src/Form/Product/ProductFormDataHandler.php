<?php

namespace PrestaShop\Module\Democustomfields17\Form\Product;

use PrestaShop\Module\Democustomfields17\Form\FormDataHandlerInterface;

final class ProductFormDataHandler implements FormDataHandlerInterface
{
    public function save(array $data): bool{
        return true;
    }

    public function getData(array $params): array{
        return [];
    }
}
