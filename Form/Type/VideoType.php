<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'snowcap_core_video';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'video_id' => null,
                'provider' => null
            )
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('video_id', $options['video_id'] ? : null)
            ->setAttribute('provider', $options['provider'] ? : null)
        ;
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface     $form
     * @param array                                     $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['video_id'] = $form->getAttribute('video_id');
        $view->vars['provider'] = $form->getAttribute('provider');
    }
}