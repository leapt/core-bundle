<?php
namespace Snowcap\CoreBundle\Entity;

interface TranslatableEntityInterface {
    /**
     * Get all translations for the entity
     *
     * @abstract
     * @return Collection
     */
    public function getTranslations();

    /**
     * Set all translations for the entity
     * @abstract
     * @param \Doctrine\Common\Collections\Collection $translations
     */
    public function setTranslations($translations);
}