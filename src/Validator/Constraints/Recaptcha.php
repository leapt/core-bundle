<?php

namespace Leapt\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Recaptcha extends Constraint
{
    public string $message = 'This value is not a valid captcha.';

    public string $invalidHostMessage = 'The captcha was not resolved on the right domain.';

    public function __construct(array $options = null, string $message = null, string $invalidHostMessage = null, array $groups = null, $payload = null)
    {
        parent::__construct($options ?? [], $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->invalidHostMessage = $invalidHostMessage ?? $this->invalidHostMessage;
    }

    public function validatedBy()
    {
        return RecaptchaValidator::class;
    }
}
