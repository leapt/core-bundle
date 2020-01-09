<?php

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SoundType
 * @package Leapt\CoreBundle\Form\Type
 */
class SoundType extends AbstractType
{
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'leapt_core_sound';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['provider' => null])
            ->setAllowedValues('provider', ['soundcloud'])
        ;
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface     $form
     * @param array                                     $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['provider'] = $options['provider'];
    }
}