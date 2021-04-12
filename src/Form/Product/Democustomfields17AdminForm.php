<?php


namespace PrestaShop\Module\Democustomfields17\Form\Product;

use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Module;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;

class Democustomfields17AdminForm extends CommonAbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFieldsByHook($options['hookFormBuilder'], $builder, $options['module']);
    }
    
    private function addFieldsByHook(
        HookFieldsBuilderInterface $hookForm,
        FormBuilderInterface $builder,
        Module $module
    ) {
        $hookForm
            ->addCustomFields($builder, $module)
            ->add(
                $module->getModuleFormDatasID(), // used to check if datas come from Admin Product form
                'Symfony\Component\Form\Extension\Core\Type\HiddenType',
                ['data' => '1']
            );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'hookFormBuilder' => null,
            'module' => null,
            'allow_extra_fields' => true
        ));
    }
}
