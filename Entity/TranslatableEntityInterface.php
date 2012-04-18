<?php
namespace Snowcap\CoreBundle\Entity;

interface TranslatableEntityInterface {
    /**
     * Retrieve the current translation set
     * Used to get the translation based on the current locale
     *
     * @abstract
     * @return TranslationEntityInterface
     */
    public function getTranslated();

    /**
     * Set the current translation
     * Used to set the translation based on the current locale
     *
     * @abstract
     * @param TranslationEntityInterface $translation
     */
    public function setTranslated($translation);

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