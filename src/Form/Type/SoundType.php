<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoundType extends AbstractType
{
    public const PROVIDER_SOUNDCLOUD = 'soundcloud';

    public function getBlockPrefix(): string
    {
        return 'leapt_core_sound';
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'provider'      => null,
                'player_width'  => 560,
                'player_height' => 300,
            ])
            ->setAllowedValues('provider', [self::PROVIDER_SOUNDCLOUD])
            ->setAllowedTypes('player_width', ['int', 'string'])
            ->setAllowedTypes('player_height', ['int', 'string'])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['provider'] = $options['provider'];
        $view->vars['player_width'] = $options['player_width'];
        $view->vars['player_height'] = $options['player_height'];
    }
}
