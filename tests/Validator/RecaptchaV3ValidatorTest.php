<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Validator;

use Leapt\CoreBundle\Validator\Constraints\RecaptchaV3;
use Leapt\CoreBundle\Validator\Constraints\RecaptchaV3Validator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class RecaptchaV3ValidatorTest extends ConstraintValidatorTestCase
{
    private bool $enabled = true;
    private ?string $secretKey = 'secret';

    public function testDisabledWithoutSecretKeyIsValid(): void
    {
        $this->enabled = false;
        $this->secretKey = null;
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->validator->validate('token', new RecaptchaV3());

        $this->assertNoViolation();
    }

    public function testEnabledWithoutSecretKeyThrowsException(): void
    {
        $this->secretKey = null;
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageIs('The "leapt_core.recaptcha.private_key" option must be set to use reCAPTCHA validation.');

        $this->validator->validate('token', new RecaptchaV3());
    }

    public function testNullTokenRaisesViolation(): void
    {
        $this->validator->validate(null, new RecaptchaV3());

        $this->buildViolation('The submitted captcha is invalid.')
            ->setParameter('{{ string }}', '""')
            ->setParameter('{{ score }}', 'null')
            ->setParameter('{{ errorCodes }}', '"missing-input-response"')
            ->assertRaised();
    }

    protected function createValidator(): RecaptchaV3Validator
    {
        return new RecaptchaV3Validator($this->enabled, $this->secretKey, 0.5, new RequestStack());
    }
}
