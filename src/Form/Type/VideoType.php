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
            ->setDefined(['provider'])
            ->setAllowedValues('provider', ['youtube', 'tudou', 'vimeo', 'dailymotion'])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['provider'])) {
            $view->vars['provider'] = $options['provider'];
        }
    }
}
