<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;

class FileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'snowcap_core_file';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'web_path' => null
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('web_path', $options['web_path'] ? : null);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $view->set('web_path', $form->getAttribute('web_path'));
    }
}