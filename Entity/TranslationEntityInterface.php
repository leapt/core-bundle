<?php
namespace Snowcap\CoreBundle\Entity;

use Snowcap\CoreBundle\Entity\TranslatableEntityInterface;

interface TranslationEntityInterface {
    public function getLocale();
    public function setLocale($locale);
    public function getTranslatedEntity();
    public function setTranslatedEntity(TranslatableEntityInterface $translatedEntity);
}