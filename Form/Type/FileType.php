<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'snowcap_core_file';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('web_path' => null));
    }


    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('web_path', $options['web_path'] ? : null);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface     $form
     * @param array                                     $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['web_path'] = $form->getAttribute('web_path');
    }
}