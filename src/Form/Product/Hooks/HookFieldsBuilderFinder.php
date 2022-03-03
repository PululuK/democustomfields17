<?php
    
    namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

    use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
    use Exception;
    
    final class HookFieldsBuilderFinder
    {
        public const HOOK_FIELDS_BUILDER_NAMESPACE = 'PrestaShop\Module\Democustomfields17\Form\Product\Hooks\\';

        public function find(string $hookName): ?HookFieldsBuilderInterface{

            $fieldsBuilderFullClassName = $this->getFieldsBuilderFullClassName($hookName);

            if (class_exists($fieldsBuilderFullClassName)) {
                try {
                    $classObject = new $fieldsBuilderFullClassName();
                    if (is_a($classObject, HookFieldsBuilderInterface::class)) {
                        return $classObject;
                    }
                    return null;
                } catch (Exception $e) {
                    return null;
                }
            }
            return null;
        }

        private function getFieldsBuilderFullClassName($hookName): string
        {
            return self::HOOK_FIELDS_BUILDER_NAMESPACE.ucfirst($hookName).'FieldsBuilder';
        }
    }
