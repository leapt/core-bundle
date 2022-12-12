<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Extension;

use Leapt\CoreBundle\Form\Listener\HoneypotListener;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class HoneypotExtension extends AbstractTypeExtension
{
    public function __construct(
        private TranslatorInterface $translator,
        private bool $enableGlobally,
        private string $inputName,
        private string $cssClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (true !== $options['honeypot_enabled']) {
            return;
        }

        $builder->addEventSubscriber(new HoneypotListener($this->translator, $options['honeypot_input_name']));
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (null !== $view->parent || true !== $options['honeypot_enabled'] || true !== $options['compound']) {
            return;
        }

        if ($form->has($options['honeypot_input_name'])) {
            throw new \RuntimeException(sprintf('Honeypot field "%s" is already used.', $options['honeypot_input_name']));
        }

        $formOptions = $this->createViewOptions($options);

        $formView = $form->getConfig()->getFormFactory()
            ->createNamed($options['honeypot_input_name'], TextType::class, null, $formOptions)
            ->createView($view);

        $view->children[$options['honeypot_input_name']] = $formView;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'honeypot_enabled'    => $this->enableGlobally,
                'honeypot_input_name' => $this->inputName,
                'honeypot_css_class'  => $this->cssClass,
            ])
            ->setAllowedTypes('honeypot_enabled', 'bool')
            ->setAllowedTypes('honeypot_input_name', 'string')
            ->setAllowedTypes('honeypot_css_class', 'string')
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class,
        ];
    }

    private function createViewOptions(array $options): array
    {
        return [
            'mapped'   => false,
            'label'    => false,
            'required' => false,
            'attr'     => ['class' => $options['honeypot_css_class']],
        ];
    }
}
