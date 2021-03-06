<?php

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VideoType.
 */
class VideoType extends AbstractType
{
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'leapt_core_video';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['provider'])
            ->setAllowedValues('provider', ['youtube', 'tudou', 'vimeo', 'dailymotion'])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['provider'])) {
            $view->vars['provider'] = $options['provider'];
        }
    }
}
