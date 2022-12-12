<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Form\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

final class HoneypotListener implements EventSubscriberInterface
{
    public function __construct(
        private TranslatorInterface $translator,
        private string $fieldName,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        if (!$form->isRoot() || null === $form->getConfig()->getOption('compound')) {
            return;
        }

        $data = $event->getData();

        if (!isset($data[$this->fieldName]) || '' !== (string) $data[$this->fieldName]) {
            $form->addError(new FormError($this->translator->trans('honeypot.should_not_be_filled', [], 'LeaptCoreBundle')));
        }

        if (\is_array($data)) {
            unset($data[$this->fieldName]);
        }

        $event->setData($data);
    }
}
