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
    public const PROVIDER_YOUTUBE = 'youtube';
    public const PROVIDER_TUDOU = 'tudou';
    public const PROVIDER_VIMEO = 'vimeo';
    public const PROVIDER_DAILYMOTION = 'dailymotion';

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
                'player_width'  => 560,
                'player_height' => 315,
            ])
            ->setDefined(['provider'])
            ->setAllowedValues('provider', [
                self::PROVIDER_YOUTUBE,
                self::PROVIDER_TUDOU,
                self::PROVIDER_VIMEO,
                self::PROVIDER_DAILYMOTION,
            ])
            ->setAllowedTypes('player_width', ['int', 'string'])
            ->setAllowedTypes('player_height', ['int', 'string'])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['provider'])) {
            $view->vars['provider'] = $options['provider'];
        }
        $view->vars['player_width'] = $options['player_width'];
        $view->vars['player_height'] = $options['player_height'];
    }
}
