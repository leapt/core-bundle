<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'leapt_core_video';
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'video_width'  => 560,
                'video_height' => 315,
            ])
            ->setDefined(['provider'])
            ->setAllowedValues('provider', ['youtube', 'tudou', 'vimeo', 'dailymotion'])
            ->setAllowedTypes('video_width', ['int', 'string'])
            ->setAllowedTypes('video_height', ['int', 'string'])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['provider'])) {
            $view->vars['provider'] = $options['provider'];
        }
        $view->vars['video_width'] = $options['video_width'];
        $view->vars['video_height'] = $options['video_height'];
    }
}
