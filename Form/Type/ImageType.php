<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\MissingOptionsException;

class ImageType extends AbstractType {
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'snowcap_core_image';
    }

    public function getParent(array $options)
    {
        return 'file';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'web_path' => null,
        );
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        if(!isset($options['web_path'])) {
            throw new MissingOptionsException('The "web_path" option is mandatory', array('web_path'));
        }
        $builder
            ->setAttribute('web_path', $options['web_path'] ?: null)
        ;
    }


    public function buildView(FormView $view, FormInterface $form)
    {
        $vars = $view->getParent()->getVars();
        $parentValue = $vars['value'];
        if(!empty($parentValue)) {
            $propertyPath = new PropertyPath($form->getAttribute('web_path'));
            $view->set('image_src', $propertyPath->getValue($parentValue));
        }
    }
}