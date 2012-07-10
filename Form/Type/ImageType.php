<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class ImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'snowcap_core_image';
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
            'web_path' => null,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['web_path'])) {
            throw new MissingOptionsException('The "web_path" option is mandatory', array('web_path'));
        }
        $builder->setAttribute('web_path', $options['web_path'] ? : null);
    }


    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $vars = $view->getParent()->getVars();
        $parentValue = $vars['value'];
        if (!empty($parentValue)) {
            $propertyPath = new PropertyPath($form->getAttribute('web_path'));
            $view->set('image_src', $propertyPath->getValue($parentValue));
        }
    }
}