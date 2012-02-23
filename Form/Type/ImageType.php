<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;

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
            'web_path' => null
        );
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->setAttribute('web_path', $options['web_path'] ?: null);
    }


    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('web_path', $form->getAttribute('web_path'));
    }
}