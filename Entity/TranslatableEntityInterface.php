<?php
namespace Snowcap\CoreBundle\Entity;

interface TranslatableEntityInterface {
    /**
     * @abstract
     * @return Collection
     */
    public function getTranslations();

    /**
     * @abstract
     * @param \Doctrine\Common\Collections\Collection $translations
     */
    public function setTranslations($translations);
}