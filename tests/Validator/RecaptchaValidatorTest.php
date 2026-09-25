<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Validator;

use Leapt\CoreBundle\Validator\Constraints\Recaptcha;
use Leapt\CoreBundle\Validator\Constraints\RecaptchaValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class RecaptchaValidatorTest extends ConstraintValidatorTestCase
{
    private bool $enabled = true;

    public function testDisabledWithoutPrivateKeyIsValid(): void
    {
        $this->enabled = false;
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->validator->validate(null, new Recaptcha());

        $this->assertNoViolation();
    }

    public function testEnabledWithoutPrivateKeyThrowsException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageIs('The "leapt_core.recaptcha.private_key" option must be set to use reCAPTCHA validation.');

        $this->validator->validate(null, new Recaptcha());
    }

    protected function createValidator(): RecaptchaValidator
    {
        return new RecaptchaValidator($this->enabled, null, new RequestStack(), ['host' => null, 'port' => null, 'auth' => null], false);
    }
}
