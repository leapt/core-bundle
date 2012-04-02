<?php

namespace Snowcap\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;

class VideoType extends AbstractType {
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'snowcap_core_video';
    }

    public function getParent(array $options)
    {
        return 'text';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'video_id' => null,
            'provider' => null
        );
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->setAttribute('video_id', $options['video_id'] ?: null)
            ->setAttribute('provider', $options['provider'] ?: null);
    }


    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('video_id', $form->getAttribute('video_id'));
        $view->set('provider', $form->getAttribute('provider'));
    }
}