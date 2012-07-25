<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'snowcap_core_image';
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
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['web_path'])) {
            throw new MissingOptionsException('The "web_path" option is mandatory', array('web_path'));
        }
        $builder->setAttribute('web_path', $options['web_path'] ? : null);
    }


    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface     $form
     * @param array                                     $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $vars = $view->parent->vars;
        $parentValue = $vars['value'];
        if (!empty($parentValue)) {
            $propertyPath = new PropertyPath($form->getAttribute('web_path'));
            $view->vars['image_src'] = $propertyPath->getValue($parentValue);
        }
    }
}