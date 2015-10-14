<?php

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VideoType
 * @package Leapt\CoreBundle\Form\Type
 */
class VideoType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'leapt_core_video';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(array('provider'))
            ->setAllowedValues('provider', array('youtube', 'tudou', 'vimeo', 'dailymotion'))
        ;
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface     $form
     * @param array                                     $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($options['provider'])) {
            $view->vars['provider'] = $options['provider'];
        }
    }
}
