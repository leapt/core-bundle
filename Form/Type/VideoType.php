<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;

class VideoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'snowcap_core_video';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'video_id' => null,
            'provider' => null
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('video_id', $options['video_id'] ? : null)
            ->setAttribute('provider', $options['provider'] ? : null);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $view->set('video_id', $form->getAttribute('video_id'));
        $view->set('provider', $form->getAttribute('provider'));
    }
}