<?php

namespace Leapt\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class Slug extends Regex
{
    /**
     * @var string
     */
    public $message = 'A slug can only contain lowercase letters, numbers and hyphens';

    /**
     * @var string
     */
    public $pattern = '/([^a-z0-9\-])/';

    /**
     * @var bool
     */
    public $match = false;

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return array();
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'Symfony\Component\Validator\Constraints\RegexValidator';
    }
}
