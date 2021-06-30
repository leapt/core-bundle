<?php

namespace Leapt\CoreBundle\Validator\Constraints;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RecaptchaV3 extends Recaptcha
{
    public string $message = 'The submitted captcha is invalid.';

    public function validatedBy(): string
    {
        return RecaptchaV3Validator::class;
    }
}
